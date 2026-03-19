<?php

namespace App\Domain\Compliance\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use App\Domain\Shared\ValueObjects\EvidenceType;
use DateTimeImmutable;

class Requirement
{
    public function __construct(
        private readonly UUID $id,
        private int $regulationId,
        private string $code,
        private string $description,
        private string $obligationType = 'obligatorio',
        private ?string $frequency = null,
        private string $evidenceType = 'documento',
        private int $expirationDays = 365,
        private ?array $criteria = null,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getRegulationId(): int
    {
        return $this->regulationId;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function isObligatory(): bool
    {
        return $this->obligationType === 'obligatorio';
    }

    public function getFrequency(): ?string
    {
        return $this->frequency;
    }

    public function getEvidenceType(): EvidenceType
    {
        return EvidenceType::from($this->evidenceType);
    }

    public function getExpirationDays(): int
    {
        return $this->expirationDays;
    }

    public function calculateExpirationDate(\DateTimeImmutable $from): \DateTimeImmutable
    {
        return $from->modify("+{$this->expirationDays} days");
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'regulation_id' => $this->regulationId,
            'code' => $this->code,
            'description' => $this->description,
            'obligation_type' => $this->obligationType,
            'frequency' => $this->frequency,
            'evidence_type' => $this->evidenceType,
            'expiration_days' => $this->expirationDays,
            'criteria' => $this->criteria,
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
