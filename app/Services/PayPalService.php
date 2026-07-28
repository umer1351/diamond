<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PayPalService
{
    private string $mode;
    private ?string $clientId;
    private ?string $secret;
    private string $currency;
    private float $qarPerUnit;

    public function __construct()
    {
        $this->mode = (string) config('services.paypal.mode', 'live');
        $this->clientId = config('services.paypal.client_id');
        $this->secret = config('services.paypal.secret');
        $this->currency = strtoupper((string) config('services.paypal.currency', 'USD'));
        $this->qarPerUnit = (float) config('services.paypal.qar_per_unit', 3.64);
    }

    public function isConfigured(): bool
    {
        return filled($this->clientId) && filled($this->secret);
    }

    public function currency(): string
    {
        return $this->currency;
    }

    private function baseUrl(): string
    {
        return $this->mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    /**
     * Convert an amount expressed in QAR into the PayPal settlement currency.
     */
    public function convertFromQar(float $amountQar): string
    {
        $rate = $this->qarPerUnit > 0 ? $this->qarPerUnit : 3.64;

        return number_format($amountQar / $rate, 2, '.', '');
    }

    private function accessToken(): ?string
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->secret)
            ->post($this->baseUrl() . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials',
            ]);

        if (! $response->successful()) {
            Log::error('PayPal token request failed', ['body' => $response->body()]);

            return null;
        }

        return $response->json('access_token');
    }

    /**
     * Create a PayPal order. Returns [id, approve_url] on success or null on failure.
     *
     * @param float  $amountQar   Grand total expressed in QAR.
     * @param string $reference   Internal order reference (invoice id).
     * @param string $returnUrl   Where PayPal sends the buyer after approval.
     * @param string $cancelUrl   Where PayPal sends the buyer on cancel.
     */
    public function createOrder(float $amountQar, string $reference, string $returnUrl, string $cancelUrl): ?array
    {
        $token = $this->accessToken();

        if (! $token) {
            return null;
        }

        $value = $this->convertFromQar($amountQar);

        $response = Http::withToken($token)
            ->post($this->baseUrl() . '/v2/checkout/orders', [
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'reference_id' => $reference,
                    'custom_id' => $reference,
                    'description' => 'Azure Luxury order ' . $reference,
                    'amount' => [
                        'currency_code' => $this->currency,
                        'value' => $value,
                    ],
                ]],
                'application_context' => [
                    'brand_name' => 'Azure Luxury',
                    'user_action' => 'PAY_NOW',
                    'shipping_preference' => 'NO_SHIPPING',
                    'return_url' => $returnUrl,
                    'cancel_url' => $cancelUrl,
                ],
            ]);

        if (! $response->successful()) {
            Log::error('PayPal create order failed', ['body' => $response->body()]);

            return null;
        }

        $links = collect($response->json('links', []));
        $approve = $links->firstWhere('rel', 'approve');

        return [
            'id' => $response->json('id'),
            'approve_url' => $approve['href'] ?? null,
        ];
    }

    /**
     * Capture an approved PayPal order. Returns the decoded response array
     * on success (status COMPLETED), or null on failure.
     */
    public function captureOrder(string $orderId): ?array
    {
        $token = $this->accessToken();

        if (! $token) {
            return null;
        }

        $response = Http::withToken($token)
            ->withHeaders(['Content-Type' => 'application/json'])
            ->post($this->baseUrl() . '/v2/checkout/orders/' . $orderId . '/capture');

        if (! $response->successful()) {
            Log::error('PayPal capture failed', ['order' => $orderId, 'body' => $response->body()]);

            return null;
        }

        $data = $response->json();

        if (($data['status'] ?? null) !== 'COMPLETED') {
            Log::warning('PayPal capture not completed', ['order' => $orderId, 'status' => $data['status'] ?? null]);

            return null;
        }

        return $data;
    }
}
