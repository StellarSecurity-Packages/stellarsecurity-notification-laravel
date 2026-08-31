<?php

namespace StellarSecurity\Notifications\DTO;

use InvalidArgumentException;

final readonly class PushSubscriptionRegistration
{
    public function __construct(
        public string $application,
        public string $platform,
        public string $token,
        public ?string $externalReference = null,
    ) {
        if (! in_array($platform, ['ios', 'android'], true)) {
            throw new InvalidArgumentException('Push platform must be ios or android.');
        }
        if (mb_strlen($token) < 32 || preg_match('/\s/', $token) === 1) {
            throw new InvalidArgumentException('Push token is invalid.');
        }
    }

    /** @return array<string,string> */
    public function toArray(): array
    {
        return array_filter([
            'application' => $this->application,
            'platform' => $this->platform,
            'token' => $this->token,
            'external_reference' => $this->externalReference,
        ], static fn (?string $value): bool => $value !== null);
    }
}
