<?php

namespace App\Domain\Compliance\Repositories;

use App\Domain\Compliance\Entities\Evidence;

interface EvidenceRepositoryInterface
{
    public function findById(int $id): ?Evidence;
    public function findByRequirement(int $requirementId): array;
    public function findByUser(int $userId): array;
    public function findByStatus(string $status): array;
    public function findExpired(): array;
    public function findExpiringSoon(int $days = 30): array;
    public function save(Evidence $evidence): Evidence;
    public function delete(int $id): void;
}
