<?php

namespace App\Domain\Compliance\Events;

use DateTimeImmutable;

class AuditCreatedEvent
{
    public function __construct(
        public readonly int $auditId,
        public readonly string $auditName,
        public readonly int $siteId,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {}
}
