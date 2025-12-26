<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Stellar Notifications API Client
    |--------------------------------------------------------------------------
    |
    | This package is a lightweight client for calling your Stellar Notifications
    | API from other Laravel apps (VPN, Antivirus, etc).
    |
    | NOTE: No production URLs are shipped as defaults. Configure your own.
    |
    */

    'base_url' => env('STELLAR_NOTIFICATIONS_BASE_URL', ''),

    /*
    |--------------------------------------------------------------------------
    | Basic Authentication
    |--------------------------------------------------------------------------
    |
    | The Notifications API is protected with HTTP Basic Auth.
    | Set these in your consuming application's environment.
    |
    */

    'basic_username' => env('STELLAR_NOTIFICATIONS_BASIC_USERNAME', ''),
    'basic_password' => env('STELLAR_NOTIFICATIONS_BASIC_PASSWORD', ''),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Options
    |--------------------------------------------------------------------------
    */

    'timeout' => (int) env('STELLAR_NOTIFICATIONS_TIMEOUT', 8),
];
