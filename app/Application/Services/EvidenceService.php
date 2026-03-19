<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Compliance\Entities\Evidence;
use App\Domain\Compliance\Events\EvidenceUploadedEvent;
use App\Domain\Compliance\Events\EvidenceVerifiedEvent;
use App\Domain\Compliance\Repositories\EvidenceRepositoryInterface;
use App\Domain\Compliance\Repositories\RequirementRepositoryInterface;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use App\Domain\Identity\Repositories\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class EvidenceService
{
    public function __construct(
        private readonly EvidenceRepositoryInterface $evidenceRepository,
        private readonly RequirementRepositoryInterface $requirementRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Upload evidence for a requirement
     */
    public function uploadEvidence(array $data): Evidence
    {
        $requirement = $this->requirementRepository->findById($data['requirement_id']);
        if (!$requirement) {
            throw new ModelNotFoundException("Requirement not found: {$data['requirement_id']}");
        }

        $evidence = new Evidence(
            id: Uuid::generate(),
            requirementId: $requirement->id,
            companyId: $data['company_id'],
            siteId: $data['site_id'] ?? null,
            title: $data['title'],
            description: $data['description'] ?? null,
            type: $data['type'] ?? 'document',
            fileUrl: $data['file_url'],
            fileName: $data['file_name'] ?? null,
            fileSize: $data['file_size'] ?? null,
            mimeType: $data['mime_type'] ?? null,
            uploadedBy: $data['uploaded_by'],
            verificationStatus: 'pending',
            verificationNotes: null,
            verifiedBy: null,
            verifiedAt: null,
            expirationDate: !empty($data['expiration_date']) ? new \DateTime($data['expiration_date']) : null,
            tags: $data['tags'] ?? [],
            metadata: $data['metadata'] ?? []
        );

        $this->evidenceRepository->save($evidence);

        event(new EvidenceUploadedEvent($evidence));

        return $evidence;
    }

    /**
     * Update evidence
     */
    public function updateEvidence(string $evidenceId, array $data): Evidence
    {
        $evidence = $this->evidenceRepository->findById($evidenceId);

        if (!$evidence) {
            throw new ModelNotFoundException("Evidence not found: {$evidenceId}");
        }

        $evidence->updateProfile([
            'title' => $data['title'] ?? $evidence->title->toString(),
            'description' => $data['description'] ?? $evidence->description?->toString(),
            'expiration_date' => $data['expiration_date'] ?? ($evidence->expirationDate?->format('Y-m-d')),
            'tags' => $data['tags'] ?? $evidence->tags,
        ]);

        $this->evidenceRepository->save($evidence);

        return $evidence;
    }

    /**
     * Approve evidence
     */
    public function approveEvidence(string $evidenceId, string $verifiedBy, ?string $notes = null): Evidence
    {
        $evidence = $this->evidenceRepository->findById($evidenceId);

        if (!$evidence) {
            throw new ModelNotFoundException("Evidence not found: {$evidenceId}");
        }

        $evidence->approve($verifiedBy, $notes);
        $this->evidenceRepository->save($evidence);

        event(new EvidenceVerifiedEvent($evidence));

        return $evidence;
    }

    /**
     * Reject evidence
     */
    public function rejectEvidence(string $evidenceId, string $verifiedBy, string $reason): Evidence
    {
        $evidence = $this->evidenceRepository->findById($evidenceId);

        if (!$evidence) {
            throw new ModelNotFoundException("Evidence not found: {$evidenceId}");
        }

        $evidence->reject($verifiedBy, $reason);
        $this->evidenceRepository->save($evidence);

        return $evidence;
    }

    /**
     * Request revision for evidence
     */
    public function requestRevision(string $evidenceId, string $verifiedBy, string $feedback): Evidence
    {
        $evidence = $this->evidenceRepository->findById($evidenceId);

        if (!$evidence) {
            throw new ModelNotFoundException("Evidence not found: {$evidenceId}");
        }

        $evidence->requestRevision($verifiedBy, $feedback);
        $this->evidenceRepository->save($evidence);

        return $evidence;
    }

    /**
     * Get evidence by ID
     */
    public function getEvidence(string $evidenceId): ?Evidence
    {
        return $this->evidenceRepository->findById($evidenceId);
    }

    /**
     * Get evidence by requirement
     */
    public function getEvidenceByRequirement(string $requirementId): Collection
    {
        return $this->evidenceRepository->findByRequirement($requirementId);
    }

    /**
     * Get evidence by company
     */
    public function getEvidenceByCompany(string $companyId): Collection
    {
        return $this->evidenceRepository->findByCompany($companyId);
    }

    /**
     * Get evidence by site
     */
    public function getEvidenceBySite(string $siteId): Collection
    {
        return $this->evidenceRepository->findBySite($siteId);
    }

    /**
     * Get pending evidence for verification
     */
    public function getPendingEvidence(): Collection
    {
        return $this->evidenceRepository->findPending();
    }

    /**
     * Get approved evidence
     */
    public function getApprovedEvidence(): Collection
    {
        return $this->evidenceRepository->findApproved();
    }

    /**
     * Get rejected evidence
     */
    public function getRejectedEvidence(): Collection
    {
        return $this->evidenceRepository->findRejected();
    }

    /**
     * Get expiring evidence
     */
    public function getExpiringEvidence(int $days = 30): Collection
    {
        return $this->evidenceRepository->findExpiringWithin($days);
    }

    /**
     * Get evidence by type
     */
    public function getEvidenceByType(string $type): Collection
    {
        return $this->evidenceRepository->findByType($type);
    }

    /**
     * Get evidence uploaded by user
     */
    public function getEvidenceByUploader(string $userId): Collection
    {
        return $this->evidenceRepository->findByUploader($userId);
    }

    /**
     * Delete evidence
     */
    public function deleteEvidence(string $evidenceId): bool
    {
        $evidence = $this->evidenceRepository->findById($evidenceId);

        if (!$evidence) {
            throw new ModelNotFoundException("Evidence not found: {$evidenceId}");
        }

        return $this->evidenceRepository->delete($evidenceId);
    }

    /**
     * Get evidence statistics
     */
    public function getEvidenceStats(string $companyId): array
    {
        $evidence = $this->evidenceRepository->findByCompany($companyId);

        $stats = [
            'total' => $evidence->count(),
            'by_status' => [],
            'by_type' => [],
            'expiring_soon' => 0,
            'total_size' => 0,
        ];

        $today = new \DateTime();
        $threshold = (clone $today)->modify('+30 days');

        foreach ($evidence as $item) {
            // Count by status
            $status = $item->verificationStatus->value;
            if (!isset($stats['by_status'][$status])) {
                $stats['by_status'][$status] = 0;
            }
            $stats['by_status'][$status]++;

            // Count by type
            $type = $item->type->value;
            if (!isset($stats['by_type'][$type])) {
                $stats['by_type'][$type] = 0;
            }
            $stats['by_type'][$type]++;

            // Check expiration
            if ($item->expirationDate && $item->expirationDate <= $threshold) {
                $stats['expiring_soon']++;
            }

            // Sum file sizes
            if ($item->fileSize) {
                $stats['total_size'] += $item->fileSize;
            }
        }

        return $stats;
    }

    /**
     * Get compliance evidence summary
     */
    public function getComplianceSummary(string $companyId): array
    {
        $evidence = $this->evidenceRepository->findByCompany($companyId);
        $requirements = $this->requirementRepository->findByCompany($companyId);

        $coveredRequirements = $evidence->filter(function ($item) {
            return $item->verificationStatus->value === 'approved';
        })->map(fn($item) => $item->requirementId->toString())->unique()->count();

        return [
            'total_evidence' => $evidence->count(),
            'requirements_with_evidence' => $coveredRequirements,
            'total_requirements' => $requirements->count(),
            'coverage_percentage' => $requirements->count() > 0 
                ? round(($coveredRequirements / $requirements->count()) * 100, 2) 
                : 0,
            'pending_verification' => $evidence->filter(fn($item) => $item->verificationStatus->value === 'pending')->count(),
            'approved' => $evidence->filter(fn($item) => $item->verificationStatus->value === 'approved')->count(),
            'rejected' => $evidence->filter(fn($item) => $item->verificationStatus->value === 'rejected')->count(),
        ];
    }
}
