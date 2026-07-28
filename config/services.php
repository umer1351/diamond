<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
        'currency' => env('STRIPE_CURRENCY', 'qar'),
    ],

    'qpay' => [
        'merchant_id' => env('QPAY_MERCHANT_ID'),
        'secret' => env('QPAY_SECRET'),
        'gateway_url' => env('QPAY_GATEWAY_URL', 'https://qpay.example.com/checkout'),
        'currency' => env('QPAY_CURRENCY', 'qar'),
    ],

    'paypal' => [
        // 'sandbox' or 'live'
        'mode' => env('PAYPAL_MODE', 'live'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        // PayPal does not support QAR, so charges settle in this currency.
        'currency' => env('PAYPAL_CURRENCY', 'USD'),
        // How many QAR make up 1 unit of the PayPal currency above.
        // Qatari Riyal is pegged at ~3.64 QAR = 1 USD.
        'qar_per_unit' => (float) env('PAYPAL_QAR_PER_UNIT', 3.64),
    ],

];
