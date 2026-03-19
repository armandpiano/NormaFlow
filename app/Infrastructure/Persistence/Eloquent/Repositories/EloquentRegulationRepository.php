<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Compliance\Entities\Regulation;
use App\Domain\Compliance\Repositories\RegulationRepositoryInterface;
use App\Models\Regulation as RegulationModel;

class EloquentRegulationRepository implements RegulationRepositoryInterface
{
    public function findById(int $id): ?Regulation
    {
        $model = RegulationModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByCode(string $code): ?Regulation
    {
        $model = RegulationModel::where('code', $code)->first();
        return $model ? $this->toEntity($model) : null;
    }

    public function findActive(): array
    {
        return RegulationModel::where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByType(string $type): array
    {
        return RegulationModel::where('type', $type)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByAuthority(string $authority): array
    {
        return RegulationModel::where('authority', $authority)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function save(Regulation $regulation): Regulation
    {
        $model = RegulationModel::updateOrCreate(
            ['id' => $regulation->getIdInt()],
            [
                'code' => $regulation->getCode(),
                'name' => $regulation->getName(),
                'description' => $regulation->description,
                'type' => $regulation->type,
                'authority' => $regulation->authority,
                'scope' => $regulation->scope,
                'effective_date' => $regulation->effectiveDate,
                'review_date' => $regulation->reviewDate,
                'url' => $regulation->url,
                'metadata' => $regulation->metadata,
                'is_active' => $regulation->isActive,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        RegulationModel::destroy($id);
    }

    private function toEntity(RegulationModel $model): Regulation
    {
        return new Regulation(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            code: $model->code,
            name: $model->name,
            description: $model->description,
            type: $model->type,
            authority: $model->authority,
            scope: $model->scope,
            effectiveDate: $model->effective_date,
            reviewDate: $model->review_date,
            url: $model->url,
            metadata: $model->metadata,
            isActive: $model->is_active,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
