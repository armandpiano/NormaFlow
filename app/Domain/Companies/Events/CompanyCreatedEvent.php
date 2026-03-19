<?php

namespace App\Domain\Companies\Events;

use DateTimeImmutable;

class CompanyCreatedEvent
{
    public function __construct(
        public readonly int $companyId,
        public readonly string $companyName,
        public readonly int $tenantId,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {}
}
