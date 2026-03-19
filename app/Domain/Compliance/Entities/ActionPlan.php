<?php

namespace App\Domain\Compliance\Entities;

use App\Domain\Shared\ValueObjects\UUID;
use DateTimeImmutable;
use InvalidArgumentException;

class ActionPlan
{
    public function __construct(
        private readonly UUID $id,
        private int $findingId,
        private int $userId,
        private ?int $createdBy,
        private string $title,
        private ?string $description,
        private ?DateTimeImmutable $startDate,
        private ?DateTimeImmutable $dueDate,
        private ?DateTimeImmutable $completedAt,
        private string $status = 'pendiente',
        private string $priority = 'media',
        private ?string $evidence = null,
        private ?string $notes = null,
        private readonly DateTimeImmutable $createdAt,
        private DateTimeImmutable $updatedAt
    ) {}

    public function getId(): UUID { return $this->id; }
    public function getFindingId(): int { return $this->findingId; }
    public function getUserId(): int { return $this->userId; }
    public function getStatus(): string { return $this->status; }
    public function getPriority(): string { return $this->priority; }
    public function getDueDate(): ?DateTimeImmutable { return $this->dueDate; }

    public function isPending(): bool { return $this->status === 'pendiente'; }
    public function isOverdue(): bool
    {
        if ($this->dueDate === null || $this->isCompleted()) {
            return false;
        }
        return $this->dueDate < new DateTimeImmutable();
    }
    public function isCompleted(): bool { return $this->status === 'completado'; }

    public function start(): void
    {
        $this->status = 'en_proceso';
        $this->startDate = $this->startDate ?? new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function complete(string $evidence = null): void
    {
        $this->status = 'completado';
        $this->completedAt = new DateTimeImmutable();
        if ($evidence) $this->evidence = $evidence;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function markAsOverdue(): void
    {
        if ($this->isOverdue() && !$this->isCompleted()) {
            $this->status = 'vencido';
            $this->updatedAt = new DateTimeImmutable();
        }
    }

    public function addNote(string $note): void
    {
        $this->notes = ($this->notes ? $this->notes . "\n" : '') . $note;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'finding_id' => $this->findingId,
            'user_id' => $this->userId,
            'created_by' => $this->createdBy,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->startDate?->format('Y-m-d'),
            'due_date' => $this->dueDate?->format('Y-m-d'),
            'completed_at' => $this->completedAt?->format('Y-m-d H:i:s'),
            'status' => $this->status,
            'priority' => $this->priority,
            'evidence' => $this->evidence,
            'notes' => $this->notes,
            'created_at' => $this->createdAt->format('Y-m-d H:i:s'),
            'updated_at' => $this->updatedAt->format('Y-m-d H:i:s'),
        ];
    }
}
