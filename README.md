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
STELLAR_NOTIFICATION_API=https://your-notifications-api.example
STELLAR_NOTIFICATIONS_BASIC_USERNAME=your-basic-username
STELLAR_NOTIFICATIONS_BASIC_PASSWORD=your-basic-password
STELLAR_NOTIFICATION_TIMEOUT=5
```

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

