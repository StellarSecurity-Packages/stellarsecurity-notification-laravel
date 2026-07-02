<?php

namespace StellarSecurity\Notifications;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use StellarSecurity\Notifications\DTO\NotificationEvent;
use StellarSecurity\Notifications\Exceptions\NotificationException;
use Throwable;

class StellarNotificationClient
{
    private const DEFAULT_TIMEOUT_SECONDS = 30;
    private const DEFAULT_CONNECT_TIMEOUT_SECONDS = 10;
    private const DEFAULT_RETRY_TIMES = 5;
    private const DEFAULT_RETRY_SLEEP_MS = 1000;
    private const DEFAULT_RETRY_MULTIPLIER = 2;
    private const DEFAULT_RETRY_MAX_SLEEP_MS = 10000;

    /**
     * HTTP status codes that are safe to retry for notification ingest.
     *
     * The Notification API should treat idempotency_key as the duplicate guard,
     * so retrying transient failures is safe for all normal package calls.
     */
    private const RETRYABLE_STATUS_CODES = [
        408,
        429,
        500,
        502,
        503,
        504,
    ];

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

        $response = $this->makeRequest($username, $password)
            ->post($url, $event->toArray());

        if (! $response->successful()) {
            throw new NotificationException('Notification API error: ' . $response->body());
        }
    }

    private function makeRequest(string $username, string $password): PendingRequest
    {
        return Http::timeout($this->intConfig('timeout', self::DEFAULT_TIMEOUT_SECONDS, 1))
            ->connectTimeout($this->intConfig('connect_timeout', self::DEFAULT_CONNECT_TIMEOUT_SECONDS, 1))
            ->retry(
                $this->intConfig('retry.times', self::DEFAULT_RETRY_TIMES, 0),
                fn (int $attempt, Throwable $exception): int => $this->retrySleepMilliseconds($attempt),
                fn (Throwable $exception, PendingRequest $request): bool => $this->shouldRetry($exception),
                true
            )
            ->withBasicAuth($username, $password)
            ->acceptJson();
    }

    private function shouldRetry(Throwable $exception): bool
    {
        if ($exception instanceof ConnectionException) {
            return true;
        }

        if ($exception instanceof RequestException) {
            $response = $exception->response;
            return $response instanceof Response
                && in_array($response->status(), self::RETRYABLE_STATUS_CODES, true);
        }

        return false;
    }

    private function retrySleepMilliseconds(int $attempt): int
    {
        $base = $this->intConfig('retry.sleep_ms', self::DEFAULT_RETRY_SLEEP_MS, 0);
        $multiplier = $this->intConfig('retry.multiplier', self::DEFAULT_RETRY_MULTIPLIER, 1);
        $max = $this->intConfig('retry.max_sleep_ms', self::DEFAULT_RETRY_MAX_SLEEP_MS, 0);

        $sleep = $base * ($multiplier ** max(0, $attempt - 1));

        if ($max > 0) {
            $sleep = min($sleep, $max);
        }

        return (int) max(0, $sleep);
    }

    private function intConfig(string $key, int $default, int $min): int
    {
        $value = config('stellar-notifications.' . $key, $default);

        if (! is_numeric($value)) {
            return $default;
        }

        return max($min, (int) $value);
    }
}
