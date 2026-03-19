<?php

namespace App\Domain\Identity\Repositories;

use App\Domain\Identity\Entities\User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;
    public function findByEmail(string $email, int $tenantId): ?User;
    public function findByTenant(int $tenantId): array;
    public function findByCompany(int $companyId): array;
    public function findActiveByTenant(int $tenantId): array;
    public function save(User $user): User;
    public function delete(int $id): void;
}
