<?php

namespace App\Domain\Compliance\Repositories;

use App\Domain\Compliance\Entities\Requirement;

interface RequirementRepositoryInterface
{
    public function findById(int $id): ?Requirement;
    public function findByRegulation(int $regulationId): array;
    public function findByObligationType(string $type): array;
    public function findActive(): array;
    public function save(Requirement $requirement): Requirement;
    public function delete(int $id): void;
}
