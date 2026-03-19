<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Compliance\Entities\Finding;
use App\Domain\Compliance\Entities\ActionPlan;
use App\Domain\Compliance\Repositories\AuditRepositoryInterface;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FindingService
{
    public function __construct(
        private readonly AuditRepositoryInterface $auditRepository,
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    /**
     * Create a new finding
     */
    public function createFinding(array $data): Finding
    {
        $audit = $this->auditRepository->findById($data['audit_id']);
        if (!$audit) {
            throw new ModelNotFoundException("Audit not found: {$data['audit_id']}");
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
     * Update finding
     */
    public function updateFinding(string $findingId, array $data): Finding
    {
        $finding = $this->findFinding($findingId);

        if (!$finding) {
            throw new ModelNotFoundException("Finding not found: {$findingId}");
        }

        $finding->updateProfile([
            'title' => $data['title'] ?? $finding->title->toString(),
            'description' => $data['description'] ?? $finding->description?->toString(),
            'severity' => $data['severity'] ?? $finding->severity->value,
            'evidence_urls' => $data['evidence_urls'] ?? $finding->evidenceUrls,
            'root_cause' => $data['root_cause'] ?? $finding->rootCause?->toString(),
            'notes' => $data['notes'] ?? $finding->notes?->toString(),
        ]);

        if (!empty($data['due_date'])) {
            $finding->updateDueDate(new \DateTime($data['due_date']));
        }

        $this->auditRepository->save($finding->audit);

        return $finding;
    }

    /**
     * Assign finding
     */
    public function assignFinding(string $findingId, string $assignedTo, ?string $dueDate = null): Finding
    {
        $finding = $this->findFinding($findingId);

        if (!$finding) {
            throw new ModelNotFoundException("Finding not found: {$findingId}");
        }

        $finding->assign($assignedTo, !empty($dueDate) ? new \DateTime($dueDate) : null);
        $this->auditRepository->save($finding->audit);

        return $finding;
    }

    /**
     * Close finding
     */
    public function closeFinding(string $findingId, array $data): Finding
    {
        $finding = $this->findFinding($findingId);

        if (!$finding) {
            throw new ModelNotFoundException("Finding not found: {$findingId}");
        }

        $finding->close($data['closure_notes'] ?? null);
        $this->auditRepository->save($finding->audit);

        return $finding;
    }

    /**
     * Reopen finding
     */
    public function reopenFinding(string $findingId, string $reason): Finding
    {
        $finding = $this->findFinding($findingId);

        if (!$finding) {
            throw new ModelNotFoundException("Finding not found: {$findingId}");
        }

        $finding->reopen($reason);
        $this->auditRepository->save($finding->audit);

        return $finding;
    }

    /**
     * Get finding by ID
     */
    public function getFinding(string $findingId): ?Finding
    {
        return $this->findFinding($findingId);
    }

    /**
     * Get findings by audit
     */
    public function getFindingsByAudit(string $auditId): Collection
    {
        $audit = $this->auditRepository->findById($auditId);
        if (!$audit) {
            return collect();
        }

        return collect($audit->getFindings());
    }

    /**
     * Get findings by severity
     */
    public function getFindingsBySeverity(string $severity): Collection
    {
        $allFindings = collect();

        foreach ($this->auditRepository->findAll() as $audit) {
            $findings = collect($audit->getFindings())->filter(function ($f) use ($severity) {
                return $f->severity->value === $severity;
            });
            $allFindings = $allFindings->merge($findings);
        }

        return $allFindings;
    }

    /**
     * Get open findings
     */
    public function getOpenFindings(): Collection
    {
        $allFindings = collect();

        foreach ($this->auditRepository->findAll() as $audit) {
            $findings = collect($audit->getFindings())->filter(function ($f) {
                return $f->status->value === 'open';
            });
            $allFindings = $allFindings->merge($findings);
        }

        return $allFindings;
    }

    /**
     * Get overdue findings
     */
    public function getOverdueFindings(): Collection
    {
        $allFindings = collect();
        $today = new \DateTime();

        foreach ($this->auditRepository->findAll() as $audit) {
            $findings = collect($audit->getFindings())->filter(function ($f) use ($today) {
                return $f->status->value === 'open' 
                    && $f->dueDate !== null 
                    && $f->dueDate < $today;
            });
            $allFindings = $allFindings->merge($findings);
        }

        return $allFindings;
    }

    /**
     * Get findings statistics
     */
    public function getFindingsStats(string $companyId): array
    {
        $audits = $this->auditRepository->findByCompany($companyId);

        $stats = [
            'total' => 0,
            'by_status' => [
                'open' => 0,
                'in_progress' => 0,
                'resolved' => 0,
                'closed' => 0,
            ],
            'by_severity' => [
                'critical' => 0,
                'major' => 0,
                'minor' => 0,
                'observation' => 0,
            ],
            'overdue' => 0,
        ];

        $today = new \DateTime();

        foreach ($audits as $audit) {
            foreach ($audit->getFindings() as $finding) {
                $stats['total']++;
                $stats['by_status'][$finding->status->value]++;
                $stats['by_severity'][$finding->severity->value]++;

                if ($finding->status->value === 'open' 
                    && $finding->dueDate !== null 
                    && $finding->dueDate < $today) {
                    $stats['overdue']++;
                }
            }
        }

        return $stats;
    }

    /**
     * Create action plan for finding
     */
    public function createActionPlan(string $findingId, array $data): ActionPlan
    {
        $finding = $this->findFinding($findingId);

        if (!$finding) {
            throw new ModelNotFoundException("Finding not found: {$findingId}");
        }

        $actionPlan = new ActionPlan(
            id: Uuid::generate(),
            findingId: $finding->id,
            title: $data['title'],
            description: $data['description'] ?? null,
            assignedTo: $data['assigned_to'],
            dueDate: !empty($data['due_date']) ? new \DateTime($data['due_date']) : null,
            priority: $data['priority'] ?? 'medium',
            status: 'planned',
            progress: 0,
            createdBy: $data['created_by'] ?? null
        );

        $finding->addActionPlan($actionPlan);
        $this->auditRepository->save($finding->audit);

        return $actionPlan;
    }

    /**
     * Update action plan
     */
    public function updateActionPlan(string $actionPlanId, array $data): ActionPlan
    {
        $actionPlan = $this->findActionPlan($actionPlanId);

        if (!$actionPlan) {
            throw new ModelNotFoundException("Action plan not found: {$actionPlanId}");
        }

        $actionPlan->updateProfile([
            'title' => $data['title'] ?? $actionPlan->title->toString(),
            'description' => $data['description'] ?? $actionPlan->description?->toString(),
            'priority' => $data['priority'] ?? $actionPlan->priority->value,
            'progress' => $data['progress'] ?? $actionPlan->progress,
        ]);

        if (!empty($data['due_date'])) {
            $actionPlan->updateDueDate(new \DateTime($data['due_date']));
        }

        $this->auditRepository->save($actionPlan->finding->audit);

        return $actionPlan;
    }

    /**
     * Complete action plan
     */
    public function completeActionPlan(string $actionPlanId): ActionPlan
    {
        $actionPlan = $this->findActionPlan($actionPlanId);

        if (!$actionPlan) {
            throw new ModelNotFoundException("Action plan not found: {$actionPlanId}");
        }

        $actionPlan->complete();
        $this->auditRepository->save($actionPlan->finding->audit);

        return $actionPlan;
    }

    /**
     * Find finding by ID across all audits
     */
    private function findFinding(string $findingId): ?Finding
    {
        foreach ($this->auditRepository->findAll() as $audit) {
            foreach ($audit->getFindings() as $finding) {
                if ($finding->id->toString() === $findingId) {
                    return $finding;
                }
            }
        }
        return null;
    }

    /**
     * Find action plan by ID
     */
    private function findActionPlan(string $actionPlanId): ?ActionPlan
    {
        foreach ($this->auditRepository->findAll() as $audit) {
            foreach ($audit->getFindings() as $finding) {
                foreach ($finding->getActionPlans() as $actionPlan) {
                    if ($actionPlan->id->toString() === $actionPlanId) {
                        return $actionPlan;
                    }
                }
            }
        }
        return null;
    }
}
