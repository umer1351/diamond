@extends('storefront.layout', ['title' => 'Payment Success - Azure Luxury', 'cartCount' => $cartCount])

@section('content')
    @php($paypal = $paypal ?? null)
    <section class="surface" style="padding:34px;max-width:900px;margin:40px auto 0">
        <span class="eyebrow">Payment Complete</span>
        <h1 class="display-title" style="font-size:58px;margin-top:10px">
            {{ $paypal ? 'Payment successful' : 'QPay payment ready' }}
        </h1>
        <p class="muted" style="max-width:60ch">
            Thank you {{ $customer['customer_name'] ?? 'Customer' }}.
            {{ $paypal
                ? 'Your PayPal payment was completed and your cart has been cleared.'
                : 'Your order request has been prepared and the cart has been cleared.' }}
        </p>

        <div class="spec-table" style="margin-top:24px">
            @if($paypal)
                <div class="spec-row">
                    <div>PayPal Transaction</div>
                    <div>{{ $paypal['transaction_id'] ?? 'Completed' }}</div>
                </div>
                <div class="spec-row">
                    <div>Order Reference</div>
                    <div>{{ $customer['reference'] ?? 'Pending' }}</div>
                </div>
                <div class="spec-row">
                    <div>Status</div>
                    <div>{{ $paypal['status'] ?? 'COMPLETED' }}</div>
                </div>
                <div class="spec-row">
                    <div>Customer Email</div>
                    <div>{{ $customer['email'] ?? 'Not provided' }}</div>
                </div>
                <div class="spec-row">
                    <div>Order Total</div>
                    <div>{{ number_format(($customer['grand_total'] ?? ($summary['grand_total'] ?? 0)), 0) }} QAR
                        <span class="muted">(charged in {{ $paypal['currency'] ?? 'USD' }})</span>
                    </div>
                </div>
            @else
                <div class="spec-row">
                    <div>QPay Reference</div>
                    <div>{{ $qpayPayload['reference'] ?? 'Pending' }}</div>
                </div>
                <div class="spec-row">
                    <div>Currency</div>
                    <div>{{ strtoupper($qpayPayload['currency'] ?? config('services.qpay.currency', 'qar')) }}</div>
                </div>
                <div class="spec-row">
                    <div>Customer Email</div>
                    <div>{{ $customer['email'] ?? 'Not provided' }}</div>
                </div>
                <div class="spec-row">
                    <div>Total Paid</div>
                    <div>{{ number_format(($summary['grand_total'] ?? 0), 0) }} {{ strtoupper(config('services.qpay.currency', 'qar')) }}</div>
                </div>
            @endif
            <div class="spec-row">
                <div>Shipping To</div>
                <div>{{ ($customer['address'] ?? '') }}{{ !empty($customer['city']) ? ', ' . $customer['city'] : '' }}</div>
            </div>
        </div>

        <div style="display:flex;gap:12px;flex-wrap:wrap;margin-top:24px">
            <a class="gold-btn" href="{{ route('storefront.index') }}">Continue Shopping</a>
            <a class="ghost-btn" href="{{ route('storefront.cart') }}">Open Cart</a>
        </div>
    </section>
@endsection
