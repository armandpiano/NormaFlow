<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Compliance\Entities\Requirement;
use App\Domain\Compliance\Repositories\RequirementRepositoryInterface;
use App\Models\Requirement as RequirementModel;

class EloquentRequirementRepository implements RequirementRepositoryInterface
{
    public function findById(int $id): ?Requirement
    {
        $model = RequirementModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByRegulation(int $regulationId): array
    {
        return RequirementModel::where('regulation_id', $regulationId)
            ->orderBy('code')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByObligationType(string $type): array
    {
        return RequirementModel::where('obligation_type', $type)
            ->where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findActive(): array
    {
        return RequirementModel::where('is_active', true)
            ->orderBy('code')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function save(Requirement $requirement): Requirement
    {
        $model = RequirementModel::updateOrCreate(
            ['id' => $requirement->getIdInt()],
            [
                'regulation_id' => $requirement->regulationId,
                'code' => $requirement->getCode(),
                'description' => $requirement->getDescription(),
                'obligation_type' => $requirement->obligationType,
                'frequency' => $requirement->frequency,
                'evidence_type' => $requirement->evidenceType,
                'expiration_days' => $requirement->expirationDays,
                'criteria' => $requirement->criteria,
                'is_active' => $requirement->isActive,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        RequirementModel::destroy($id);
    }

    private function toEntity(RequirementModel $model): Requirement
    {
        return new Requirement(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            regulationId: $model->regulation_id,
            code: $model->code,
            description: $model->description,
            obligationType: $model->obligation_type,
            frequency: $model->frequency,
            evidenceType: $model->evidence_type,
            expirationDays: $model->expiration_days,
            criteria: $model->criteria,
            isActive: $model->is_active,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
