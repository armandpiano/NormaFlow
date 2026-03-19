<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Compliance\Entities\Regulation;
use App\Domain\Compliance\Entities\Requirement;
use App\Domain\Compliance\Repositories\RegulationRepositoryInterface;
use App\Domain\Compliance\Repositories\RequirementRepositoryInterface;
use App\Domain\Compliance\Events\RequirementExpiringEvent;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RegulationService
{
    public function __construct(
        private readonly RegulationRepositoryInterface $regulationRepository,
        private readonly RequirementRepositoryInterface $requirementRepository,
        private readonly CompanyRepositoryInterface $companyRepository
    ) {}

    /**
     * Create a new regulation
     */
    public function createRegulation(array $data): Regulation
    {
        $regulation = new Regulation(
            id: Uuid::generate(),
            code: $data['code'],
            name: $data['name'],
            description: $data['description'] ?? null,
            type: $data['type'] ?? 'NOM',
            issuingAuthority: $data['issuing_authority'] ?? 'STPS',
            scope: $data['scope'] ?? 'Federal',
            effectiveDate: !empty($data['effective_date']) ? new \DateTime($data['effective_date']) : null,
            publicationDate: !empty($data['publication_date']) ? new \DateTime($data['publication_date']) : null,
            lastAmendmentDate: !empty($data['last_amendment_date']) ? new \DateTime($data['last_amendment_date']) : null,
            url: $data['url'] ?? null,
            keywords: $data['keywords'] ?? [],
            isActive: $data['is_active'] ?? true
        );

        $this->regulationRepository->save($regulation);

        return $regulation;
    }

    /**
     * Update regulation
     */
    public function updateRegulation(string $regulationId, array $data): Regulation
    {
        $regulation = $this->regulationRepository->findById($regulationId);

        if (!$regulation) {
            throw new ModelNotFoundException("Regulation not found: {$regulationId}");
        }

        $regulation->updateProfile($data);
        $this->regulationRepository->save($regulation);

        return $regulation;
    }

    /**
     * Get regulation by ID
     */
    public function getRegulation(string $regulationId): ?Regulation
    {
        return $this->regulationRepository->findById($regulationId);
    }

    /**
     * Get regulation by code
     */
    public function getRegulationByCode(string $code): ?Regulation
    {
        return $this->regulationRepository->findByCode($code);
    }

    /**
     * Get all regulations
     */
    public function getAllRegulations(): Collection
    {
        return $this->regulationRepository->findAll();
    }

    /**
     * Get regulations by type
     */
    public function getRegulationsByType(string $type): Collection
    {
        return $this->regulationRepository->findByType($type);
    }

    /**
     * Search regulations
     */
    public function searchRegulations(string $query): Collection
    {
        return $this->regulationRepository->search($query);
    }

    /**
     * Create a requirement for a regulation
     */
    public function createRequirement(string $regulationId, array $data): Requirement
    {
        $regulation = $this->regulationRepository->findById($regulationId);

        if (!$regulation) {
            throw new ModelNotFoundException("Regulation not found: {$regulationId}");
        }

        $requirement = new Requirement(
            id: Uuid::generate(),
            regulationId: $regulation->id,
            code: $data['code'],
            description: $data['description'],
            category: $data['category'] ?? null,
            subcategory: $data['subcategory'] ?? null,
            complianceCriteria: $data['compliance_criteria'] ?? null,
            evidenceType: $data['evidence_type'] ?? null,
            evidenceDescription: $data['evidence_description'] ?? null,
            verificationFrequency: $data['verification_frequency'] ?? null,
            responsibleRole: $data['responsible_role'] ?? null,
            isMandatory: $data['is_mandatory'] ?? true,
            penaltyDescription: $data['penalty_description'] ?? null,
            additionalNotes: $data['additional_notes'] ?? null,
            version: $data['version'] ?? '1.0',
            isActive: $data['is_active'] ?? true
        );

        $this->requirementRepository->save($requirement);

        return $requirement;
    }

    /**
     * Update requirement
     */
    public function updateRequirement(string $requirementId, array $data): Requirement
    {
        $requirement = $this->requirementRepository->findById($requirementId);

        if (!$requirement) {
            throw new ModelNotFoundException("Requirement not found: {$requirementId}");
        }

        $requirement->updateProfile($data);
        $this->requirementRepository->save($requirement);

        return $requirement;
    }

    /**
     * Get requirement by ID
     */
    public function getRequirement(string $requirementId): ?Requirement
    {
        return $this->requirementRepository->findById($requirementId);
    }

    /**
     * Get requirements by regulation
     */
    public function getRequirementsByRegulation(string $regulationId): Collection
    {
        return $this->requirementRepository->findByRegulation($regulationId);
    }

    /**
     * Get requirements by category
     */
    public function getRequirementsByCategory(string $category): Collection
    {
        return $this->requirementRepository->findByCategory($category);
    }

    /**
     * Get requirements by company
     */
    public function getRequirementsByCompany(string $companyId): Collection
    {
        return $this->requirementRepository->findByCompany($companyId);
    }

    /**
     * Get expiring requirements
     */
    public function getExpiringRequirements(int $days = 30): Collection
    {
        $expiringRequirements = $this->requirementRepository->findExpiringWithin($days);

        // Dispatch events for expiring requirements
        foreach ($expiringRequirements as $requirement) {
            event(new RequirementExpiringEvent($requirement));
        }

        return $expiringRequirements;
    }

    /**
     * Check requirements expiration and send notifications
     */
    public function checkRequirementsExpiration(): array
    {
        $notified = [];
        $expiringRequirements = $this->getExpiringRequirements(30);

        foreach ($expiringRequirements as $requirement) {
            $notified[] = [
                'requirement_id' => $requirement->id->toString(),
                'code' => $requirement->code->toString(),
                'regulation_id' => $requirement->regulationId->toString(),
            ];
        }

        return $notified;
    }

    /**
     * Get compliance summary for a company
     */
    public function getComplianceSummary(string $companyId): array
    {
        $requirements = $this->requirementRepository->findByCompany($companyId);

        $summary = [
            'total_requirements' => $requirements->count(),
            'compliant' => 0,
            'non_compliant' => 0,
            'pending_evidence' => 0,
            'expiring_soon' => 0,
            'by_category' => [],
            'by_regulation' => [],
        ];

        foreach ($requirements as $requirement) {
            // Count by compliance status
            $status = $requirement->complianceStatus->value;
            if (isset($summary[$status])) {
                $summary[$status]++;
            }

            // Count by category
            $category = $requirement->category ?? 'uncategorized';
            if (!isset($summary['by_category'][$category])) {
                $summary['by_category'][$category] = 0;
            }
            $summary['by_category'][$category]++;

            // Count by regulation
            $regId = $requirement->regulationId->toString();
            if (!isset($summary['by_regulation'][$regId])) {
                $summary['by_regulation'][$regId] = 0;
            }
            $summary['by_regulation'][$regId]++;
        }

        return $summary;
    }

    /**
     * Activate/deactivate regulation
     */
    public function setRegulationStatus(string $regulationId, bool $isActive): Regulation
    {
        $regulation = $this->regulationRepository->findById($regulationId);

        if (!$regulation) {
            throw new ModelNotFoundException("Regulation not found: {$regulationId}");
        }

        $regulation->setActive($isActive);
        $this->regulationRepository->save($regulation);

        return $regulation;
    }

    /**
     * Activate/deactivate requirement
     */
    public function setRequirementStatus(string $requirementId, bool $isActive): Requirement
    {
        $requirement = $this->requirementRepository->findById($requirementId);

        if (!$requirement) {
            throw new ModelNotFoundException("Requirement not found: {$requirementId}");
        }

        $requirement->setActive($isActive);
        $this->requirementRepository->save($requirement);

        return $requirement;
    }
}
