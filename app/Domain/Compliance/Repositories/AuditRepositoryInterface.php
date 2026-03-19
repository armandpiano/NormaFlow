<?php

namespace App\Domain\Compliance\Repositories;

use App\Domain\Compliance\Entities\Audit;

interface AuditRepositoryInterface
{
    public function findById(int $id): ?Audit;
    public function findBySite(int $siteId): array;
    public function findByAuditor(int $userId): array;
    public function findByStatus(string $status): array;
    public function findPlanned(): array;
    public function findInProgress(): array;
    public function save(Audit $audit): Audit;
    public function delete(int $id): void;
}
