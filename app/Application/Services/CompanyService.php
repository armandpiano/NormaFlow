<?php

declare(strict_types=1);

namespace App\Application\Services;

use App\Domain\Companies\Entities\Company;
use App\Domain\Companies\Entities\Site;
use App\Domain\Companies\Events\CompanyCreatedEvent;
use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use App\Domain\Companies\Repositories\SiteRepositoryInterface;
use App\Domain\Identity\Entities\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Uuid;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CompanyService
{
    public function __construct(
        private readonly CompanyRepositoryInterface $companyRepository,
        private readonly SiteRepositoryInterface $siteRepository
    ) {}

    /**
     * Create a new company with initial site
     */
    public function createCompany(array $data): Company
    {
        $company = new Company(
            id: Uuid::generate(),
            name: $data['name'],
            rfc: $data['rfc'],
            industry: $data['industry'] ?? null,
            size: $data['size'] ?? null,
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? 'Mexico',
            postalCode: $data['postal_code'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            website: $data['website'] ?? null,
            logoUrl: $data['logo_url'] ?? null,
            subscriptionPlan: $data['subscription_plan'] ?? 'basic',
            maxUsers: $data['max_users'] ?? 10,
            maxSites: $data['max_sites'] ?? 3,
            status: 'active',
            tenantId: $data['tenant_id']
        );

        $this->companyRepository->save($company);

        // Create initial site if provided
        if (!empty($data['initial_site'])) {
            $this->createSite($company->id->toString(), $data['initial_site']);
        }

        event(new CompanyCreatedEvent($company));

        return $company;
    }

    /**
     * Update company information
     */
    public function updateCompany(string $companyId, array $data): Company
    {
        $company = $this->companyRepository->findById($companyId);

        if (!$company) {
            throw new ModelNotFoundException("Company not found: {$companyId}");
        }

        $company->updateProfile($data);
        $this->companyRepository->save($company);

        return $company;
    }

    /**
     * Suspend a company
     */
    public function suspendCompany(string $companyId, string $reason): Company
    {
        $company = $this->companyRepository->findById($companyId);

        if (!$company) {
            throw new ModelNotFoundException("Company not found: {$companyId}");
        }

        $company->suspend($reason);
        $this->companyRepository->save($company);

        return $company;
    }

    /**
     * Activate a suspended company
     */
    public function activateCompany(string $companyId): Company
    {
        $company = $this->companyRepository->findById($companyId);

        if (!$company) {
            throw new ModelNotFoundException("Company not found: {$companyId}");
        }

        $company->activate();
        $this->companyRepository->save($company);

        return $company;
    }

    /**
     * Get company by ID
     */
    public function getCompany(string $companyId): ?Company
    {
        return $this->companyRepository->findById($companyId);
    }

    /**
     * Get company by RFC
     */
    public function getCompanyByRfc(string $rfc): ?Company
    {
        return $this->companyRepository->findByRfc($rfc);
    }

    /**
     * Get all companies for a tenant
     */
    public function getCompaniesByTenant(string $tenantId): Collection
    {
        return $this->companyRepository->findByTenant($tenantId);
    }

    /**
     * Get all companies
     */
    public function getAllCompanies(): Collection
    {
        return $this->companyRepository->findAll();
    }

    /**
     * Create a new site for a company
     */
    public function createSite(string $companyId, array $data): Site
    {
        $company = $this->companyRepository->findById($companyId);

        if (!$company) {
            throw new ModelNotFoundException("Company not found: {$companyId}");
        }

        $site = new Site(
            id: Uuid::generate(),
            companyId: $company->id,
            name: $data['name'],
            code: $data['code'],
            address: $data['address'] ?? null,
            city: $data['city'] ?? null,
            state: $data['state'] ?? null,
            country: $data['country'] ?? 'Mexico',
            postalCode: $data['postal_code'] ?? null,
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
            managerName: $data['manager_name'] ?? null,
            managerEmail: $data['manager_email'] ?? null,
            geoLatitude: $data['latitude'] ?? null,
            geoLongitude: $data['longitude'] ?? null,
            status: $data['status'] ?? 'active'
        );

        $this->siteRepository->save($site);

        return $site;
    }

    /**
     * Update site information
     */
    public function updateSite(string $siteId, array $data): Site
    {
        $site = $this->siteRepository->findById($siteId);

        if (!$site) {
            throw new ModelNotFoundException("Site not found: {$siteId}");
        }

        $site->updateProfile($data);
        $this->siteRepository->save($site);

        return $site;
    }

    /**
     * Get site by ID
     */
    public function getSite(string $siteId): ?Site
    {
        return $this->siteRepository->findById($siteId);
    }

    /**
     * Get all sites for a company
     */
    public function getSitesByCompany(string $companyId): Collection
    {
        return $this->siteRepository->findByCompany($companyId);
    }

    /**
     * Deactivate a site
     */
    public function deactivateSite(string $siteId): Site
    {
        $site = $this->siteRepository->findById($siteId);

        if (!$site) {
            throw new ModelNotFoundException("Site not found: {$siteId}");
        }

        $site->deactivate();
        $this->siteRepository->save($site);

        return $site;
    }

    /**
     * Activate a site
     */
    public function activateSite(string $siteId): Site
    {
        $site = $this->siteRepository->findById($siteId);

        if (!$site) {
            throw new ModelNotFoundException("Site not found: {$siteId}");
        }

        $site->activate();
        $this->siteRepository->save($site);

        return $site;
    }

    /**
     * Get company statistics
     */
    public function getCompanyStats(string $companyId): array
    {
        $company = $this->companyRepository->findById($companyId);

        if (!$company) {
            throw new ModelNotFoundException("Company not found: {$companyId}");
        }

        return [
            'company' => $company,
            'sites_count' => $this->siteRepository->findByCompany($companyId)->count(),
            'total_users' => 0, // Will be filled by UserService
            'active_requirements' => 0, // Will be filled by RegulationService
            'pending_evidence' => 0, // Will be filled by EvidenceService
        ];
    }
}
