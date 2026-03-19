<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Compliance\Entities\Audit;
use App\Domain\Compliance\Entities\Finding;
use App\Domain\Compliance\Events\AuditCreatedEvent;
use App\Domain\Compliance\Repositories\AuditRepositoryInterface;
use App\Domain\Compliance\Repositories\RequirementRepositoryInterface;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use App\Domain\Identity\Repositories\UserRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AuditService
{
    public function __construct(
        private readonly AuditRepositoryInterface $auditRepository,
        private readonly RequirementRepositoryInterface $requirementRepository,
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly UserRepositoryInterface $userRepository
    ) {}

    /**
     * Create a new audit
     */
    public function createAudit(array $data): Audit
    {
        $company = $this->companyRepository->findById($data['company_id']);
        if (!$company) {
            throw new ModelNotFoundException("Company not found: {$data['company_id']}");
        }

        $audit = new Audit(
            id: Uuid::generate(),
            companyId: $company->id,
            siteId: !empty($data['site_id']) ? $data['site_id'] : null,
            title: $data['title'],
            description: $data['description'] ?? null,
            type: $data['type'] ?? 'internal',
            scope: $data['scope'] ?? null,
            startDate: new \DateTime($data['start_date']),
            endDate: !empty($data['end_date']) ? new \DateTime($data['end_date']) : null,
            status: 'planned',
            leadAuditorId: $data['lead_auditor_id'] ?? null,
            createdBy: $data['created_by'] ?? null
        );

        $this->auditRepository->save($audit);

        // Add participants if provided
        if (!empty($data['participant_ids'])) {
            foreach ($data['participant_ids'] as $participantId) {
                $audit->addParticipant($participantId);
            }
            $this->auditRepository->save($audit);
        }

        // Link requirements if provided
        if (!empty($data['requirement_ids'])) {
            foreach ($data['requirement_ids'] as $requirementId) {
                $audit->addRequirement($requirementId);
            }
            $this->auditRepository->save($audit);
        }

        event(new AuditCreatedEvent($audit));

        return $audit;
    }

    /**
     * Update audit
     */
    public function updateAudit(string $auditId, array $data): Audit
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $audit->updateProfile([
            'title' => $data['title'] ?? $audit->title->toString(),
            'description' => $data['description'] ?? $audit->description?->toString(),
            'scope' => $data['scope'] ?? $audit->scope?->toString(),
        ]);

        if (!empty($data['end_date'])) {
            $audit->setEndDate(new \DateTime($data['end_date']));
        }

        $this->auditRepository->save($audit);

        return $audit;
    }

    /**
     * Start an audit
     */
    public function startAudit(string $auditId): Audit
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $audit->start();
        $this->auditRepository->save($audit);

        return $audit;
    }

    /**
     * Complete an audit
     */
    public function completeAudit(string $auditId, array $data): Audit
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $audit->complete($data['end_date'] ?? null);
        $this->auditRepository->save($audit);

        return $audit;
    }

    /**
     * Cancel an audit
     */
    public function cancelAudit(string $auditId, string $reason): Audit
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $audit->cancel($reason);
        $this->auditRepository->save($audit);

        return $audit;
    }

    /**
     * Get audit by ID
     */
    public function getAudit(string $auditId): ?Audit
    {
        return $this->auditRepository->findById($auditId);
    }

    /**
     * Get all audits for a company
     */
    public function getAuditsByCompany(string $companyId): Collection
    {
        return $this->auditRepository->findByCompany($companyId);
    }

    /**
     * Get audits by status
     */
    public function getAuditsByStatus(string $status): Collection
    {
        return $this->auditRepository->findByStatus($status);
    }

    /**
     * Get audits by type
     */
    public function getAuditsByType(string $type): Collection
    {
        return $this->auditRepository->findByType($type);
    }

    /**
     * Get audits within date range
     */
    public function getAuditsInRange(string $startDate, string $endDate): Collection
    {
        return $this->auditRepository->findInDateRange(
            new \DateTime($startDate),
            new \DateTime($endDate)
        );
    }

    /**
     * Add participant to audit
     */
    public function addParticipant(string $auditId, string $userId): Audit
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $audit->addParticipant($userId);
        $this->auditRepository->save($audit);

        return $audit;
    }

    /**
     * Remove participant from audit
     */
    public function removeParticipant(string $auditId, string $userId): Audit
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $audit->removeParticipant($userId);
        $this->auditRepository->save($audit);

        return $audit;
    }

    /**
     * Add finding to audit
     */
    public function addFinding(string $auditId, array $data): Finding
    {
        $audit = $this->auditRepository->findById($auditId);

        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$auditId}");
        }

        $finding = new Finding(
            id: Uuid::generate(),
            auditId: $audit->id,
            requirementId: !empty($data['requirement_id']) ? $data['requirement_id'] : null,
            title: $data['title'],
            description: $data['description'] ?? null,
            severity: $data['severity'] ?? 'minor',
            status: 'open',
            detectedBy: $data['detected_by'] ?? null,
            detectedAt: new \DateTime($data['detected_at'] ?? 'now'),
            dueDate: !empty($data['due_date']) ? new \DateTime($data['due_date']) : null,
            evidenceUrls: $data['evidence_urls'] ?? [],
            rootCause: $data['root_cause'] ?? null,
            notes: $data['notes'] ?? null
        );

        $audit->addFinding($finding);
        $this->auditRepository->save($audit);

        return $finding;
    }

    /**
     * Get audit statistics
     */
    public function getAuditStats(string $companyId): array
    {
        $audits = $this->auditRepository->findByCompany($companyId);

        $stats = [
            'total' => $audits->count(),
            'by_status' => [],
            'by_type' => [],
            'total_findings' => 0,
            'open_findings' => 0,
            'closed_findings' => 0,
        ];

        foreach ($audits as $audit) {
            // Count by status
            $status = $audit->status->value;
            if (!isset($stats['by_status'][$status])) {
                $stats['by_status'][$status] = 0;
            }
            $stats['by_status'][$status]++;

            // Count by type
            $type = $audit->type->value;
            if (!isset($stats['by_type'][$type])) {
                $stats['by_type'][$type] = 0;
            }
            $stats['by_type'][$type]++;

            // Count findings
            $findings = $audit->getFindings();
            $stats['total_findings'] += count($findings);
            foreach ($findings as $finding) {
                if ($finding->status->value === 'open') {
                    $stats['open_findings']++;
                } else {
                    $stats['closed_findings']++;
                }
            }
        }

        return $stats;
    }
}
