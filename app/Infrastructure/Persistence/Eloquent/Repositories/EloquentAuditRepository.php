<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Compliance\Entities\Audit;
use App\Domain\Compliance\Repositories\AuditRepositoryInterface;
use App\Models\Audit as AuditModel;

class EloquentAuditRepository implements AuditRepositoryInterface
{
    public function findById(int $id): ?Audit
    {
        $model = AuditModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findBySite(int $siteId): array
    {
        return AuditModel::where('site_id', $siteId)
            ->orderBy('planned_start_date', 'desc')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByAuditor(int $userId): array
    {
        return AuditModel::where('user_id', $userId)
            ->orderBy('planned_start_date', 'desc')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByStatus(string $status): array
    {
        return AuditModel::where('status', $status)
            ->orderBy('planned_start_date')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findPlanned(): array
    {
        return AuditModel::where('status', 'planificada')
            ->orderBy('planned_start_date')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findInProgress(): array
    {
        return AuditModel::where('status', 'en_proceso')
            ->orderBy('actual_start_date')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function save(Audit $audit): Audit
    {
        $model = AuditModel::updateOrCreate(
            ['id' => $audit->getIdInt()],
            [
                'site_id' => $audit->siteId,
                'user_id' => $audit->userId,
                'company_id' => $audit->companyId,
                'name' => $audit->name,
                'description' => $audit->description,
                'audit_type' => $audit->auditType,
                'planned_start_date' => $audit->plannedStartDate,
                'planned_end_date' => $audit->plannedEndDate,
                'actual_start_date' => $audit->actualStartDate,
                'actual_end_date' => $audit->actualEndDate,
                'status' => $audit->status,
                'scope' => $audit->scope,
                'methodology' => $audit->methodology,
                'checklist' => $audit->checklist,
                'results_summary' => $audit->resultsSummary,
                'conclusions' => $audit->conclusions,
                'recommendations' => $audit->recommendations,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        AuditModel::destroy($id);
    }

    private function toEntity(AuditModel $model): Audit
    {
        return new Audit(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            siteId: $model->site_id,
            userId: $model->user_id,
            companyId: $model->company_id,
            name: $model->name,
            description: $model->description,
            auditType: $model->audit_type,
            plannedStartDate: $model->planned_start_date,
            plannedEndDate: $model->planned_end_date,
            actualStartDate: $model->actual_start_date,
            actualEndDate: $model->actual_end_date,
            status: $model->status,
            scope: $model->scope,
            methodology: $model->methodology,
            checklist: $model->checklist,
            resultsSummary: $model->results_summary,
            conclusions: $model->conclusions,
            recommendations: $model->recommendations,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
