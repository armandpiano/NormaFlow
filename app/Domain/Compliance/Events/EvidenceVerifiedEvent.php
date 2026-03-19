<?php

namespace App\Domain\Compliance\Events;

use DateTimeImmutable;

class EvidenceVerifiedEvent
{
    public function __construct(
        public readonly int $evidenceId,
        public readonly int $verifiedBy,
        public readonly string $status,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {}
}
