<?php

namespace StellarSecurity\Notifications;

use Illuminate\Support\Facades\Http;
use StellarSecurity\Notifications\DTO\NotificationEvent;
use StellarSecurity\Notifications\Exceptions\NotificationException;

class StellarNotificationClient
{
    public function send(NotificationEvent $event): void
    {
        $base = trim((string) config('stellar-notifications.base_url', ''));
        if ($base === '') {
            throw new NotificationException('stellar-notifications.base_url is not configured.');
        }

        $username = (string) config('stellar-notifications.basic_username', '');
        $password = (string) config('stellar-notifications.basic_password', '');
        if ($username === '' || $password === '') {
            throw new NotificationException('Basic auth is not configured (stellar-notifications.basic_username/basic_password).');
        }

        $base = rtrim($base, '/');
        $url = $base . '/api/v1/notification-events/ingest';

        $req = Http::timeout((int) config('stellar-notifications.timeout', 8))
            ->withBasicAuth($username, $password)
            ->acceptJson();

        $response = $req->post($url, $event->toArray());

        if (! $response->successful()) {
            throw new NotificationException('Notification API error: ' . $response->body());
        }
    }
}
