<?php

namespace App\Domain\Compliance\Repositories;

use App\Domain\Compliance\Entities\Regulation;

interface RegulationRepositoryInterface
{
    public function findById(int $id): ?Regulation;
    public function findByCode(string $code): ?Regulation;
    public function findActive(): array;
    public function findByType(string $type): array;
    public function findByAuthority(string $authority): array;
    public function save(Regulation $regulation): Regulation;
    public function delete(int $id): void;
}
