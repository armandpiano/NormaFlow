<?php

namespace App\Domain\Companies\Repositories;

use App\Domain\Companies\Entities\Site;

interface SiteRepositoryInterface
{
    public function findById(int $id): ?Site;
    public function findByCompany(int $companyId): array;
    public function findMainSite(int $companyId): ?Site;
    public function save(Site $site): Site;
    public function delete(int $id): void;
}
