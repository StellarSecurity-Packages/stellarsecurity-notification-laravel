<?php

namespace StellarSecurity\Notifications\DTO;

final readonly class PushNotification
{
    /** @param array<string,string|int|bool> $data */
    public function __construct(
        public string $dedupeKey,
        public string $title,
        public string $body,
        public array $data = [],
        public string $product = 'support',
    ) {}

    /** @return array<string,mixed> */
    public function toArray(): array
    {
        return [
            'dedupe_key' => $this->dedupeKey,
            'product' => $this->product,
            'title' => $this->title,
            'body' => $this->body,
            'data' => $this->data,
        ];
    }
}
