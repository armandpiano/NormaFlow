<?php

namespace App\Domain\Identity\Events;

use DateTimeImmutable;

class UserCreatedEvent
{
    public function __construct(
        public readonly int $userId,
        public readonly string $email,
        public readonly int $tenantId,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {}
}
