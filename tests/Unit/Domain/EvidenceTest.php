<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Compliance\Entities\Evidence;
use App\Domain\Shared\ValueObjects\UUID;

class EvidenceTest extends TestCase
{
    /**
     * Test evidence creation
     */
    public function test_can_create_evidence(): void
    {
        $evidence = new Evidence(
            id: UUID::generate(),
            requirementId: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Capacitación NOM-035 2024',
            description: 'Evidencia de capacitación anual a todos los trabajadores',
            type: 'document',
            fileUrl: '/storage/evidence/capacitacion-2024.pdf',
            fileName: 'capacitacion-2024.pdf',
            fileSize: 1048576,
            mimeType: 'application/pdf',
            uploadedBy: UUID::generate()->toString(),
            verificationStatus: 'pending',
            expirationDate: new \DateTime('2025-10-01')
        );

        $this->assertEquals('Capacitación NOM-035 2024', $evidence->title->toString());
        $this->assertEquals('pending', $evidence->verificationStatus->value);
        $this->assertEquals('document', $evidence->type->value);
    }

    /**
     * Test evidence approval
     */
    public function test_can_approve_evidence(): void
    {
        $evidence = $this->createTestEvidence();
        
        $evidence->approve('auditor-uuid', 'Evidencia completa y válida');
        
        $this->assertEquals('approved', $evidence->verificationStatus->value);
        $this->assertEquals('auditor-uuid', $evidence->verifiedBy);
        $this->assertEquals('Evidencia completa y válida', $evidence->verificationNotes);
    }

    /**
     * Test evidence rejection
     */
    public function test_can_reject_evidence(): void
    {
        $evidence = $this->createTestEvidence();
        
        $evidence->reject('auditor-uuid', 'Documento incompleto, falta página de firmas');
        
        $this->assertEquals('rejected', $evidence->verificationStatus->value);
        $this->assertEquals('Documento incompleto, falta página de firmas', $evidence->verificationNotes);
    }

    /**
     * Test evidence revision request
     */
    public function test_can_request_revision(): void
    {
        $evidence = $this->createTestEvidence();
        
        $evidence->requestRevision('auditor-uuid', 'Por favor agregar fecha de elaboración');
        
        $this->assertEquals('revision', $evidence->verificationStatus->value);
        $this->assertEquals('Por favor agregar fecha de elaboración', $evidence->verificationNotes);
    }

    /**
     * Test evidence expiration check
     */
    public function test_can_check_if_evidence_is_expired(): void
    {
        $expiredEvidence = new Evidence(
            id: UUID::generate(),
            requirementId: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Expired Evidence',
            type: 'document',
            fileUrl: '/test.pdf',
            uploadedBy: UUID::generate()->toString(),
            verificationStatus: 'approved',
            expirationDate: new \DateTime('2020-01-01')
        );

        $this->assertTrue($expiredEvidence->isExpired());
        
        $validEvidence = new Evidence(
            id: UUID::generate(),
            requirementId: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Valid Evidence',
            type: 'document',
            fileUrl: '/test.pdf',
            uploadedBy: UUID::generate()->toString(),
            verificationStatus: 'approved',
            expirationDate: new \DateTime('2099-01-01')
        );

        $this->assertFalse($validEvidence->isExpired());
    }

    /**
     * Test evidence expiration warning
     */
    public function test_can_check_if_evidence_expiring_soon(): void
    {
        $expiringSoon = new Evidence(
            id: UUID::generate(),
            requirementId: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Expiring Soon',
            type: 'document',
            fileUrl: '/test.pdf',
            uploadedBy: UUID::generate()->toString(),
            verificationStatus: 'approved',
            expirationDate: (new \DateTime())->modify('+15 days')
        );

        $this->assertTrue($expiringSoon->isExpiringSoon(30));
        $this->assertFalse($expiringSoon->isExpiringSoon(10));
    }

    /**
     * Test evidence update
     */
    public function test_can_update_evidence_profile(): void
    {
        $evidence = $this->createTestEvidence();
        
        $evidence->updateProfile([
            'title' => 'Updated Title',
            'description' => 'Updated description',
            'expiration_date' => '2025-12-31',
        ]);
        
        $this->assertEquals('Updated Title', $evidence->title->toString());
        $this->assertEquals('Updated description', $evidence->description->toString());
    }

    /**
     * Helper method to create test evidence
     */
    private function createTestEvidence(): Evidence
    {
        return new Evidence(
            id: UUID::generate(),
            requirementId: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Test Evidence',
            type: 'document',
            fileUrl: '/test.pdf',
            uploadedBy: UUID::generate()->toString(),
            verificationStatus: 'pending'
        );
    }
}
