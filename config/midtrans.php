<?php

return [
    'server_key' => env('MIDTRANS_SERVER_KEY'),
    'client_key' => env('MIDTRANS_CLIENT_KEY'),
    'is_production' => env('MIDTRANS_IS_PROD', false),
    'callback_url' => env('MIDTRANS_CALLBACK_URL'),
];
