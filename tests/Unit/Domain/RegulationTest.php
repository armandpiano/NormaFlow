<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Compliance\Entities\Regulation;
use App\Domain\Compliance\Entities\Requirement;
use App\Domain\Shared\ValueObjects\UUID;

class RegulationTest extends TestCase
{
    /**
     * Test regulation creation
     */
    public function test_can_create_regulation(): void
    {
        $regulation = new Regulation(
            id: UUID::generate(),
            code: 'NOM-035-STPS-2018',
            name: 'Factores de riesgo psicosocial en el trabajo',
            description: 'Identificación, análisis y prevención de factores de riesgo psicosocial',
            type: 'NOM',
            issuingAuthority: 'STPS',
            scope: 'Federal',
            effectiveDate: new \DateTime('2019-10-23'),
            isActive: true
        );

        $this->assertEquals('NOM-035-STPS-2018', $regulation->code->toString());
        $this->assertEquals('NOM', $regulation->type->value);
        $this->assertTrue($regulation->isActive);
    }

    /**
     * Test regulation update profile
     */
    public function test_can_update_regulation_profile(): void
    {
        $regulation = $this->createTestRegulation();
        
        $regulation->updateProfile([
            'name' => 'Updated Regulation Name',
            'description' => 'Updated description',
            'url' => 'https://nueva-url.gob.mx',
        ]);
        
        $this->assertEquals('Updated Regulation Name', $regulation->name->toString());
        $this->assertEquals('Updated description', $regulation->description->toString());
    }

    /**
     * Test regulation activation
     */
    public function test_can_activate_regulation(): void
    {
        $regulation = new Regulation(
            id: UUID::generate(),
            code: 'NOM-035-STPS-2018',
            name: 'Test Regulation',
            isActive: false
        );
        
        $regulation->setActive(true);
        
        $this->assertTrue($regulation->isActive);
    }

    /**
     * Test requirement creation
     */
    public function test_can_create_requirement(): void
    {
        $regulation = $this->createTestRegulation();
        
        $requirement = new Requirement(
            id: UUID::generate(),
            regulationId: $regulation->id,
            code: '5.1',
            description: 'El patrón debe establecer políticas para la prevención de factores de riesgo psicosocial',
            category: 'Organización',
            complianceCriteria: 'Presencia de política documentada',
            evidenceType: 'Document',
            verificationFrequency: 'annual',
            responsibleRole: 'Recursos Humanos',
            isMandatory: true
        );

        $this->assertEquals('5.1', $requirement->code->toString());
        $this->assertTrue($requirement->isMandatory);
        $this->assertEquals('Organización', $requirement->category);
    }

    /**
     * Test requirement status update
     */
    public function test_can_update_requirement_compliance_status(): void
    {
        $requirement = $this->createTestRequirement();
        
        $requirement->updateComplianceStatus('compliant');
        
        $this->assertEquals('compliant', $requirement->complianceStatus->value);
    }

    /**
     * Helper method to create a test regulation
     */
    private function createTestRegulation(): Regulation
    {
        return new Regulation(
            id: UUID::generate(),
            code: 'NOM-035-STPS-2018',
            name: 'Test Regulation',
            type: 'NOM',
            issuingAuthority: 'STPS'
        );
    }

    /**
     * Helper method to create a test requirement
     */
    private function createTestRequirement(): Requirement
    {
        return new Requirement(
            id: UUID::generate(),
            regulationId: UUID::generate(),
            code: '1.0',
            description: 'Test requirement',
            isMandatory: true
        );
    }
}
