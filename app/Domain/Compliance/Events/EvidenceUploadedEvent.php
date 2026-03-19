<?php

namespace App\Domain\Compliance\Events;

use DateTimeImmutable;

class EvidenceUploadedEvent
{
    public function __construct(
        public readonly int $evidenceId,
        public readonly int $requirementId,
        public readonly int $userId,
        public readonly DateTimeImmutable $occurredAt = new DateTimeImmutable()
    ) {}
}
