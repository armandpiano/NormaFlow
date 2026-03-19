<?php

namespace App\Domain\Compliance\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;
use InvalidArgumentException;

class Audit
{
    public function __construct(
        private readonly UUID $id,
        private int $siteId,
        private int $userId,
        private int $companyId,
        private string $name,
        private ?string $description,
        private string $auditType = 'interna',
        private DateTimeImmutable $plannedStartDate,
        private ?DateTimeImmutable $plannedEndDate,
        private ?DateTimeImmutable $actualStartDate = null,
        private ?DateTimeImmutable $actualEndDate = null,
        private string $status = 'planificada',
        private ?string $scope = null,
        private ?string $methodology = null,
        private ?array $checklist = null,
        private ?array $resultsSummary = null,
        private ?string $conclusions = null,
        private ?string $recommendations = null,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID
    {
        return $this->id;
    }

    public function getSiteId(): int
    {
        return $this->siteId;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function isPlanned(): bool
    {
        return $this->status === 'planificada';
    }

    public function isInProgress(): bool
    {
        return $this->status === 'en_proceso';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completada';
    }

    public function start(): void
    {
        if (!$this->isPlanned()) {
            throw new InvalidArgumentException('Only planned audits can be started');
        }
        
        $this->status = 'en_proceso';
        $this->actualStartDate = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function complete(array $results = []): void
    {
        if (!$this->isInProgress()) {
            throw new InvalidArgumentException('Only audits in progress can be completed');
        }
        
        $this->status = 'completada';
        $this->actualEndDate = new DateTimeImmutable();
        $this->resultsSummary = $results['summary'] ?? null;
        $this->conclusions = $results['conclusions'] ?? null;
        $this->recommendations = $results['recommendations'] ?? null;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function cancel(): void
    {
        if ($this->isCompleted()) {
            throw new InvalidArgumentException('Completed audits cannot be cancelled');
        }
        
        $this->status = 'cancelada';
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getPlannedDurationDays(): int
    {
        if ($this->plannedEndDate === null) {
            return 1;
        }
        return (int) $this->plannedStartDate->diff($this->plannedEndDate)->days + 1;
    }

    public function getActualDurationDays(): ?int
    {
        if ($this->actualStartDate === null || $this->actualEndDate === null) {
            return null;
        }
        return (int) $this->actualStartDate->diff($this->actualEndDate)->days + 1;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'site_id' => $this->siteId,
            'user_id' => $this->userId,
            'company_id' => $this->companyId,
            'name' => $this->name,
            'description' => $this->description,
            'audit_type' => $this->auditType,
            'planned_start_date' => $this->plannedStartDate->format('Y-m-d'),
            'planned_end_date' => $this->plannedEndDate?->format('Y-m-d'),
            'actual_start_date' => $this->actualStartDate?->format('Y-m-d'),
            'actual_end_date' => $this->actualEndDate?->format('Y-m-d'),
            'status' => $this->status,
            'scope' => $this->scope,
            'methodology' => $this->methodology,
            'checklist' => $this->checklist,
            'results_summary' => $this->resultsSummary,
            'conclusions' => $this->conclusions,
            'recommendations' => $this->recommendations,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
