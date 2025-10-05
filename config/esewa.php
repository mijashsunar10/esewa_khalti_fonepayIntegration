<?php

return [
    'merchant_id' => env('ESEWA_MERCHANT_ID'),
    'secret_key' => env('ESEWA_SECRET_KEY'),
    'base_url' => env('ESEWA_BASE_URL', 'https://rc-epay.esewa.com.np'),
    'production_url' => env('ESEWA_PRODUCTION_URL', 'https://epay.esewa.com.np'),
    
    // Test credentials
    'test' => [
        'merchant_id' => 'EPAYTEST',
        'secret_key' => '8gBm/:&EnhH.1/q'
    ]
];