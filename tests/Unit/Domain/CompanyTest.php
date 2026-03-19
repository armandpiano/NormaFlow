<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Companies\Entities\Company;
use App\Domain\Shared\ValueObjects\UUID;
use App\Domain\Shared\ValueObjects\ComplianceStatus;

class CompanyTest extends TestCase
{
    /**
     * Test company creation
     */
    public function test_can_create_company(): void
    {
        $company = new Company(
            id: UUID::generate(),
            name: 'Acme Corporation',
            rfc: 'ACM930101AB1',
            industry: 'manufacturing',
            size: 'medium',
            address: '123 Industrial Ave',
            city: 'Mexico City',
            state: 'CDMX',
            country: 'Mexico',
            postalCode: '06600',
            phone: '+52 55 1234 5678',
            email: 'contact@acme.com',
            website: 'https://acme.com',
            logoUrl: null,
            subscriptionPlan: 'professional',
            maxUsers: 50,
            maxSites: 10,
            status: 'active',
            tenantId: UUID::generate()
        );

        $this->assertEquals('Acme Corporation', $company->name->toString());
        $this->assertEquals('ACM930101AB1', $company->rfc->toString());
        $this->assertEquals('active', $company->status);
    }

    /**
     * Test company suspension
     */
    public function test_can_suspend_company(): void
    {
        $company = $this->createTestCompany();
        
        $company->suspend('Non-payment of subscription');
        
        $this->assertEquals('suspended', $company->status);
        $this->assertEquals('Non-payment of subscription', $company->suspensionReason);
    }

    /**
     * Test company activation
     */
    public function test_can_activate_suspended_company(): void
    {
        $company = $this->createTestCompany();
        $company->suspend('Temporary suspension');
        
        $company->activate();
        
        $this->assertEquals('active', $company->status);
        $this->assertNull($company->suspensionReason);
    }

    /**
     * Test company profile update
     */
    public function test_can_update_company_profile(): void
    {
        $company = $this->createTestCompany();
        
        $company->updateProfile([
            'name' => 'Acme Corp Updated',
            'industry' => 'technology',
            'phone' => '+52 55 9876 5432',
        ]);
        
        $this->assertEquals('Acme Corp Updated', $company->name->toString());
        $this->assertEquals('technology', $company->industry->toString());
        $this->assertEquals('+52 55 9876 5432', $company->phone->toString());
    }

    /**
     * Test company has available slots for users
     */
    public function test_has_available_user_slots(): void
    {
        $company = $this->createTestCompany();
        $company->currentUsersCount = 5;
        
        $this->assertTrue($company->hasAvailableUserSlots());
        
        $company->currentUsersCount = 50;
        $this->assertFalse($company->hasAvailableUserSlots());
    }

    /**
     * Test company has available slots for sites
     */
    public function test_has_available_site_slots(): void
    {
        $company = $this->createTestCompany();
        $company->currentSitesCount = 3;
        
        $this->assertTrue($company->hasAvailableSiteSlots());
        
        $company->currentSitesCount = 10;
        $this->assertFalse($company->hasAvailableSiteSlots());
    }

    /**
     * Helper method to create a test company
     */
    private function createTestCompany(): Company
    {
        return new Company(
            id: UUID::generate(),
            name: 'Test Company',
            rfc: 'TEST930101AB1',
            tenantId: UUID::generate()
        );
    }
}
