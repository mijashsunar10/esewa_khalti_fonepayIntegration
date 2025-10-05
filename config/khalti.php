<?php

return [
    'secret_key' => env('KHALTI_SECRET_KEY'),
    'public_key' => env('KHALTI_PUBLIC_KEY'),
    'base_url' => env('KHALTI_BASE_URL', 'https://khalti.com/api/v2/'),
    
    // Test credentials
    'test' => [
        'mobile' => ['9800000000', '9800000001', '9800000002', '9800000003', '9800000004', '9800000005'],
        'mpin' => '1111',
        'otp' => '987654'
    ]
];