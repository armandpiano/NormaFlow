<?php

namespace App\Domain\Compliance\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use App\Domain\Shared\ValueObjects\EvidenceType;
use DateTimeImmutable;
use InvalidArgumentException;

class Evidence
{
    public function __construct(
        private readonly UUID $id,
        private int $requirementId,
        private int $userId,
        private ?int $siteId,
        private string $title,
        private ?string $description,
        private string $filePath,
        private string $originalName,
        private string $mimeType,
        private int $fileSize,
        private ?DateTimeImmutable $documentDate,
        private ?DateTimeImmutable $validFrom,
        private ?DateTimeImmutable $validUntil,
        private string $status = 'pending',
        private ?int $verifiedBy = null,
        private ?DateTimeImmutable $verifiedAt = null,
        private ?string $rejectionReason = null,
        private ?array $metadata = null,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getRequirementId(): int
    {
        return $this->requirementId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getSiteId(): ?int
    {
        return $this->siteId;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function isExpired(): bool
    {
        if ($this->validUntil === null) {
            return false;
        }
        return $this->validUntil < new DateTimeImmutable();
    }

    public function approve(int $verifiedBy): void
    {
        if (!$this->isPending()) {
            throw new InvalidArgumentException('Only pending evidences can be approved');
        }
        
        $this->status = 'approved';
        $this->verifiedBy = $verifiedBy;
        $this->verifiedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function reject(int $verifiedBy, string $reason): void
    {
        if (!$this->isPending()) {
            throw new InvalidArgumentException('Only pending evidences can be rejected');
        }
        
        $this->status = 'rejected';
        $this->verifiedBy = $verifiedBy;
        $this->verifiedAt = new DateTimeImmutable();
        $this->rejectionReason = $reason;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function markAsExpired(): void
    {
        $this->status = 'expired';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function daysUntilExpiration(): int
    {
        if ($this->validUntil === null) {
            return PHP_INT_MAX;
        }
        
        $now = new DateTimeImmutable();
        if ($this->validUntil < $now) {
            return 0;
        }
        
        return (int) $now->diff($this->validUntil)->days;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'requirement_id' => $this->requirementId,
            'user_id' => $this->userId,
            'site_id' => $this->siteId,
            'title' => $this->title,
            'description' => $this->description,
            'file_path' => $this->filePath,
            'original_name' => $this->originalName,
            'mime_type' => $this->mimeType,
            'file_size' => $this->fileSize,
            'document_date' => $this->documentDate?->format('Y-m-d'),
            'valid_from' => $this->validFrom?->format('Y-m-d'),
            'valid_until' => $this->validUntil?->format('Y-m-d'),
            'status' => $this->status,
            'verified_by' => $this->verifiedBy,
            'verified_at' => $this->verifiedAt?->format('Y-m-d H:i:s'),
            'rejection_reason' => $this->rejectionReason,
            'metadata' => $this->metadata,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
