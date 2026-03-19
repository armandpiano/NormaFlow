<?php

declare(strict_types=1);

namespace App\UI\WEB\Controllers;

use App\Application\Services\CompanyService;
use App\Application\Services\UserService;
use App\Application\Services\RegulationService;
use App\Application\Services\AuditService;
use App\Application\Services\EvidenceService;
use App\Application\Services\FindingService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        private readonly CompanyService $companyService,
        private readonly UserService $userService,
        private readonly RegulationService $regulationService,
        private readonly AuditService $auditService,
        private readonly EvidenceService $evidenceService,
        private readonly FindingService $findingService
    ) {}

    /**
     * Show dashboard
     */
    public function index(Request $request): View
    {
        $user = Auth::user();
        $companyId = $user->companyId?->toString();

        $stats = [
            'user' => [
                'name' => $user->name->toString(),
                'role' => $user->role->value,
            ],
        ];

        if ($companyId) {
            // Company-specific stats
            $stats['company'] = $this->companyService->getCompanyStats($companyId);
            $stats['users'] = $this->userService->getUserStats($companyId);
            $stats['compliance'] = $this->regulationService->getComplianceSummary($companyId);
            $stats['audits'] = $this->auditService->getAuditStats($companyId);
            $stats['evidence'] = $this->evidenceService->getEvidenceStats($companyId);
            $stats['findings'] = $this->findingService->getFindingsStats($companyId);
        }

        // Get recent activity
        $recentActivity = $this->getRecentActivity($companyId);

        // Get upcoming tasks
        $upcomingTasks = $this->getUpcomingTasks($companyId);

        return view('dashboard.index', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'upcomingTasks' => $upcomingTasks,
        ]);
    }

    /**
     * Get recent activity
     */
    private function getRecentActivity(?string $companyId): array
    {
        // This would typically come from activity logs
        return [
            [
                'type' => 'audit_completed',
                'message' => 'Auditoría NOM-035 completada',
                'time' => now()->subHours(2)->diffForHumans(),
            ],
            [
                'type' => 'evidence_approved',
                'message' => 'Evidencia aprobada: Capacitación Q4 2024',
                'time' => now()->subHours(5)->diffForHumans(),
            ],
            [
                'type' => 'finding_created',
                'message' => 'Nueva observación encontrada',
                'time' => now()->subDays(1)->diffForHumans(),
            ],
        ];
    }

    /**
     * Get upcoming tasks
     */
    private function getUpcomingTasks(?string $companyId): array
    {
        // This would typically come from scheduled tasks
        return [
            [
                'type' => 'audit',
                'title' => 'Auditoría de seguimiento',
                'due_date' => now()->addDays(7)->format('d/m/Y'),
                'priority' => 'high',
            ],
            [
                'type' => 'evidence',
                'title' => 'Renovar evidencia: Políticas de desempeño',
                'due_date' => now()->addDays(14)->format('d/m/Y'),
                'priority' => 'medium',
            ],
            [
                'type' => 'training',
                'title' => 'Capacitación trimestral',
                'due_date' => now()->addDays(30)->format('d/m/Y'),
                'priority' => 'low',
            ],
        ];
    }
}
