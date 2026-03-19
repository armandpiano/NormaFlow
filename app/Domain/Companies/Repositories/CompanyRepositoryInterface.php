<?php

namespace App\Domain\Companies\Repositories;

use App\Domain\Companies\Entities\Company;

interface CompanyRepositoryInterface
{
    public function findById(int $id): ?Company;
    public function findByTenant(int $tenantId): array;
    public function findByRfc(string $rfc): ?Company;
    public function save(Company $company): Company;
    public function delete(int $id): void;
    public function findActiveByTenant(int $tenantId): array;
}
