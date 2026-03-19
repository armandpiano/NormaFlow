<?php

namespace App\Infrastructure\Persistence\Eloquent\Repositories;

use App\Domain\Compliance\Entities\Evidence;
use App\Domain\Compliance\Repositories\EvidenceRepositoryInterface;
use App\Models\Evidence as EvidenceModel;
use Carbon\Carbon;

class EloquentEvidenceRepository implements EvidenceRepositoryInterface
{
    public function findById(int $id): ?Evidence
    {
        $model = EvidenceModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function findByRequirement(int $requirementId): array
    {
        return EvidenceModel::where('requirement_id', $requirementId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByUser(int $userId): array
    {
        return EvidenceModel::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findByStatus(string $status): array
    {
        return EvidenceModel::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findExpired(): array
    {
        return EvidenceModel::where('status', 'approved')
            ->where('valid_until', '<', Carbon::now())
            ->orderBy('valid_until')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function findExpiringSoon(int $days = 30): array
    {
        $future = Carbon::now()->addDays($days);
        return EvidenceModel::where('status', 'approved')
            ->whereNotNull('valid_until')
            ->whereBetween('valid_until', [Carbon::now(), $future])
            ->orderBy('valid_until')
            ->get()
            ->map(fn($m) => $this->toEntity($m))
            ->toArray();
    }

    public function save(Evidence $evidence): Evidence
    {
        $model = EvidenceModel::updateOrCreate(
            ['id' => $evidence->getIdInt()],
            [
                'requirement_id' => $evidence->requirementId,
                'user_id' => $evidence->userId,
                'site_id' => $evidence->siteId,
                'title' => $evidence->getTitle(),
                'description' => $evidence->description,
                'file_path' => $evidence->filePath,
                'original_name' => $evidence->originalName,
                'mime_type' => $evidence->mimeType,
                'file_size' => $evidence->fileSize,
                'document_date' => $evidence->documentDate,
                'valid_from' => $evidence->validFrom,
                'valid_until' => $evidence->validUntil,
                'status' => $evidence->status,
                'verified_by' => $evidence->verifiedBy,
                'verified_at' => $evidence->verifiedAt,
                'rejection_reason' => $evidence->rejectionReason,
                'metadata' => $evidence->metadata,
            ]
        );
        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        EvidenceModel::destroy($id);
    }

    private function toEntity(EvidenceModel $model): Evidence
    {
        return new Evidence(
            id: \App\Domain\Shared\ValueObjects\UUID::fromString((string) $model->id),
            requirementId: $model->requirement_id,
            userId: $model->user_id,
            siteId: $model->site_id,
            title: $model->title,
            description: $model->description,
            filePath: $model->file_path,
            originalName: $model->original_name,
            mimeType: $model->mime_type,
            fileSize: $model->file_size,
            documentDate: $model->document_date,
            validFrom: $model->valid_from,
            validUntil: $model->valid_until,
            status: $model->status,
            verifiedBy: $model->verified_by,
            verifiedAt: $model->verified_at,
            rejectionReason: $model->rejection_reason,
            metadata: $model->metadata,
            createdAt: $model->created_at,
            updatedAt: $model->updated_at
        );
    }
}
