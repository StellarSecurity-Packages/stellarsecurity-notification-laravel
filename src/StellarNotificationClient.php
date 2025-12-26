<?php

namespace StellarSecurity\Notifications;

use Illuminate\Support\Facades\Http;
use StellarSecurity\Notifications\DTO\NotificationEvent;
use StellarSecurity\Notifications\Exceptions\NotificationException;

class StellarNotificationClient
{
    public function send(NotificationEvent $event): void
    {
        $base = rtrim((string) config('stellar-notifications.base_url'), '/');
        $url = $base . '/api/v1/notification-events/ingest';

        $req = Http::timeout((int) config('stellar-notifications.timeout', 5));

        // Optional: shared token as header, if you implement it server-side
        $token = (string) (config('stellar-notifications.token') ?? '');
        if ($token !== '') {
            $req = $req->withHeaders([
                'X-Stellar-Notification-Token' => $token,
            ]);
        }

        $response = $req->post($url, $event->toArray());

        if (! $response->successful()) {
            throw new NotificationException('Notification API error: ' . $response->body());
        }
    }
}
