<?php

return [
    'base_url' => env('STELLAR_NOTIFICATION_API'),
    'timeout' => (int) env('STELLAR_NOTIFICATION_TIMEOUT', 5),

    // Optional shared secret if you add auth later
    'token' => env('STELLAR_NOTIFICATION_TOKEN'),
];
