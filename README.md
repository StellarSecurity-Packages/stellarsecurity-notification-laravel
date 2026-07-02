# Stellar Notifications (Laravel)

A lightweight Laravel package for sending events to the **Stellar Notification API**.

## Install

```bash
composer require stellarsecurity/notification-laravel
```

## Publish config

```bash
php artisan vendor:publish --tag=stellar-notifications-config
```

## Configure

In your `.env`:

```env
STELLAR_NOTIFICATIONS_BASE_URL=https://your-notifications-api.example
STELLAR_NOTIFICATIONS_BASIC_USERNAME=your-basic-username
STELLAR_NOTIFICATIONS_BASIC_PASSWORD=your-basic-password
```

Optional HTTP settings. These defaults are already built into the package, even if you do not publish config:

```env
STELLAR_NOTIFICATIONS_TIMEOUT=30
STELLAR_NOTIFICATIONS_CONNECT_TIMEOUT=10
STELLAR_NOTIFICATIONS_RETRY_TIMES=5
STELLAR_NOTIFICATIONS_RETRY_SLEEP_MS=1000
STELLAR_NOTIFICATIONS_RETRY_MULTIPLIER=2
STELLAR_NOTIFICATIONS_RETRY_MAX_SLEEP_MS=10000
```

Retry is applied to transient failures:

- connection timeouts / connection errors
- 408 Request Timeout
- 429 Too Many Requests
- 500 Internal Server Error
- 502 Bad Gateway
- 503 Service Unavailable
- 504 Gateway Timeout

With the defaults, retry backoff is:

```text
1s -> 2s -> 4s -> 8s -> 10s max
```

Each request attempt can use up to 10 seconds to connect and 30 seconds total request time.

## Usage

```php
use StellarSecurity\Notifications\DTO\NotificationEvent;
use Notification;

Notification::send(
    NotificationEvent::make('welcome')
        ->product('stellar-antivirus')
        ->email($user->email)
        ->userRef((string) $user->id)
        ->payload(['app_name' => 'Stellar Antivirus'])
        ->idempotencyKey('welcome-'.$user->id)
);
```
