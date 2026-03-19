<?php

namespace App\Domain\Compliance\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;

class Regulation
{
    public function __construct(
        private readonly UUID $id,
        private string $code,
        private string $name,
        private ?string $description = null,
        private string $type = 'NOM',
        private string $authority = 'STPS',
        private string $scope = 'Federal',
        private ?DateTimeImmutable $effectiveDate = null,
        private ?DateTimeImmutable $reviewDate = null,
        private ?string $url = null,
        private ?array $metadata = null,
        private bool $isActive = true,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isExpired(): bool
    {
        if ($this->reviewDate === null) {
            return false;
        }
        return $this->reviewDate < new DateTimeImmutable();
    }

    public function needsReview(): bool
    {
        if ($this->reviewDate === null) {
            return false;
        }
        
        $threeMonthsFromNow = new DateTimeImmutable('+3 months');
        return $this->reviewDate <= $threeMonthsFromNow;
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

    public function updateReviewDate(DateTimeImmutable $date): void
    {
        $this->reviewDate = $date;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'type' => $this->type,
            'authority' => $this->authority,
            'scope' => $this->scope,
            'effective_date' => $this->effectiveDate?->format('Y-m-d'),
            'review_date' => $this->reviewDate?->format('Y-m-d'),
            'url' => $this->url,
            'metadata' => $this->metadata,
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
