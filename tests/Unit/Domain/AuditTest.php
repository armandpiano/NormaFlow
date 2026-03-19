<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use PHPUnit\Framework\TestCase;
use App\Domain\Compliance\Entities\Audit;
use App\Domain\Compliance\Entities\Finding;
use App\Domain\Shared\ValueObjects\UUID;

class AuditTest extends TestCase
{
    /**
     * Test audit creation
     */
    public function test_can_create_audit(): void
    {
        $audit = new Audit(
            id: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Auditoría NOM-035 Q4 2024',
            description: 'Auditoría trimestral de factores de riesgo psicosocial',
            type: 'internal',
            startDate: new \DateTime('2024-10-01'),
            endDate: new \DateTime('2024-10-15'),
            status: 'planned'
        );

        $this->assertEquals('Auditoría NOM-035 Q4 2024', $audit->title->toString());
        $this->assertEquals('planned', $audit->status->value);
        $this->assertEquals('internal', $audit->type->value);
    }

    /**
     * Test audit start
     */
    public function test_can_start_audit(): void
    {
        $audit = $this->createTestAudit();
        
        $audit->start();
        
        $this->assertEquals('in_progress', $audit->status->value);
    }

    /**
     * Test audit completion
     */
    public function test_can_complete_audit(): void
    {
        $audit = $this->createTestAudit();
        $audit->start();
        
        $audit->complete(new \DateTime('2024-10-15'));
        
        $this->assertEquals('completed', $audit->status->value);
    }

    /**
     * Test audit cancellation
     */
    public function test_can_cancel_audit(): void
    {
        $audit = $this->createTestAudit();
        
        $audit->cancel('Schedules conflict with other audits');
        
        $this->assertEquals('cancelled', $audit->status->value);
    }

    /**
     * Test adding participant
     */
    public function test_can_add_participant(): void
    {
        $audit = $this->createTestAudit();
        $participantId = UUID::generate();
        
        $audit->addParticipant($participantId);
        
        $this->assertCount(1, $audit->getParticipants());
        $this->assertTrue($audit->hasParticipant($participantId));
    }

    /**
     * Test removing participant
     */
    public function test_can_remove_participant(): void
    {
        $audit = $this->createTestAudit();
        $participantId = UUID::generate();
        
        $audit->addParticipant($participantId);
        $audit->removeParticipant($participantId);
        
        $this->assertCount(0, $audit->getParticipants());
    }

    /**
     * Test adding finding
     */
    public function test_can_add_finding(): void
    {
        $audit = $this->createTestAudit();
        $finding = new Finding(
            id: UUID::generate(),
            auditId: $audit->id,
            title: 'Falta de evidencia de capacitación',
            description: 'No se encontró evidencia de la capacitación anual',
            severity: 'major',
            status: 'open',
            detectedAt: new \DateTime()
        );
        
        $audit->addFinding($finding);
        
        $this->assertCount(1, $audit->getFindings());
    }

    /**
     * Test audit cannot be started if already completed
     */
    public function test_cannot_start_completed_audit(): void
    {
        $audit = $this->createTestAudit();
        $audit->start();
        $audit->complete();
        
        $this->expectException(\RuntimeException::class);
        $audit->start();
    }

    /**
     * Helper method to create a test audit
     */
    private function createTestAudit(): Audit
    {
        return new Audit(
            id: UUID::generate(),
            companyId: UUID::generate(),
            title: 'Test Audit',
            type: 'internal',
            startDate: new \DateTime()
        );
    }
}
