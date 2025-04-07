<?php

return [
    // POSNET API Endpoints
    'api_url' => env('POSNET_API_URL', 'https://setmpos.ykb.com/PosnetWebService/XML'),
    'threed_url' => env('POSNET_3D_URL', 'https://setmpos.ykb.com/3DSWebService/YKBPaymentService'),
    
    // Merchant Information
    'merchant_id' => env('POSNET_MERCHANT_ID','6700972656'),
    'terminal_id' => env('POSNET_TERMINAL_ID','67C31344'),
    'posnet_id' => env('POSNET_ID','1010073415007134'),
    'merchant_url' => env('APP_URL'),
    'merchant_return_url' => env('POSNET_MERCHANT_RETURN_URL', 'http://localhost:8000/payment/result'),
    'xml_service_url' => env('POSNET_XML_SERVICE_URL', 'https://setmpos.ykb.com/PosnetWebService/XML'),
    
    // Test Mode
    'test_mode' => env('POSNET_TEST_MODE', true),
    
    // Timeout Settings
    'timeout' => env('POSNET_TIMEOUT', 90),
    
    // Currency
    'default_currency' => 'TL',
    'enc_key' => env('POSNET_ENC_KEY', '10,10,10,10,10,10,10,10'),
];