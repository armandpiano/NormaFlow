<?php

namespace App\Domain\Compliance\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;
use InvalidArgumentException;

class Finding
{
    public function __construct(
        private readonly UUID $id,
        private int $auditId,
        private ?int $requirementId,
        private ?int $siteId,
        private string $severity,
        private string $title,
        private string $description,
        private ?string $rootCause = null,
        private ?string $immediateAction = null,
        private ?string $recommendation = null,
        private string $status = 'abierto',
        private ?int $createdBy = null,
        private ?DateTimeImmutable $closedAt = null,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID { return $this->id; }
    public function getAuditId(): int { return $this->auditId; }
    public function getSeverity(): string { return $this->severity; }
    public function getStatus(): string { return $this->status; }

    public function isCritical(): bool { return $this->severity === 'critico'; }
    public function isOpen(): bool { return $this->status === 'abierto'; }
    public function isClosed(): bool { return $this->status === 'cerrado'; }

    public function close(): void
    {
        if ($this->isClosed()) {
            throw new InvalidArgumentException('Finding is already closed');
        }
        $this->status = 'cerrado';
        $this->closedAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function startAction(): void
    {
        $this->status = 'en_accion';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function verify(): void
    {
        $this->status = 'verificado';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'audit_id' => $this->auditId,
            'requirement_id' => $this->requirementId,
            'site_id' => $this->siteId,
            'severity' => $this->severity,
            'title' => $this->title,
            'description' => $this->description,
            'root_cause' => $this->rootCause,
            'immediate_action' => $this->immediateAction,
            'recommendation' => $this->recommendation,
            'status' => $this->status,
            'created_by' => $this->createdBy,
            'closed_at' => $this->closedAt?->format('Y-m-d H:i:s'),
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
