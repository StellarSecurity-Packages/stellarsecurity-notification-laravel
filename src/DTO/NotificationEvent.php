<?php

namespace StellarSecurity\Notifications\DTO;

class NotificationEvent
{
    private array $data = [];

    public static function make(string $eventName): self
    {
        $self = new self();
        $self->data['event_name'] = $eventName;
        return $self;
    }

    public function product(string $product): self
    {
        $this->data['product'] = $product;
        return $this;
    }

    public function email(string $email): self
    {
        $this->data['email'] = $email;
        return $this;
    }

    public function userRef(string $userRef): self
    {
        $this->data['user_ref'] = $userRef;
        return $this;
    }

    public function payload(array $payload): self
    {
        $this->data['payload'] = $payload;
        return $this;
    }

    public function idempotencyKey(string $key): self
    {
        $this->data['idempotency_key'] = $key;
        return $this;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}
