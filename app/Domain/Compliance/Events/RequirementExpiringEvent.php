<?php

namespace App\Domain\Compliance\Events;

use DateTimeImmutable;

class RequirementExpiringEvent
{
    public function __construct(
        public readonly int $requirementId,
        public readonly int $daysUntilExpiration,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {}
}
