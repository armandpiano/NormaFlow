<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Reportes
 * 
 * Permisos granulares:
 * - reports.view: Ver reportes
 * - reports.create: Crear reportes
 * - reports.export: Exportar reportes
 * - reports.dashboard: Ver dashboard
 * - reports.compliance: Ver reportes de cumplimiento
 * - reports.audit: Ver reportes de auditoría
 * - reports.financial: Ver reportes financieros
 */
class ReportPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de reportes
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('reports.view') ||
               $user->isSuperAdmin() ||
               $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isComplianceOfficer();
    }

    /**
     * Ver un tipo específico de reporte
     */
    public function view(User $user, string $reportType): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.view')) {
            return false;
        }

        // Company admin y superiores pueden ver todos los reportes de su empresa
        if ($user->isCompanyAdmin()) {
            return true;
        }

        // Site manager puede ver reportes de sus sedes
        if ($user->isSiteManager()) {
            return true;
        }

        // Compliance officer puede ver reportes de cumplimiento
        if ($user->isComplianceOfficer() && in_array($reportType, ['compliance', 'evidence', 'requirements'])) {
            return true;
        }

        // Auditors pueden ver reportes de auditoría
        if ($user->isAuditor() && $reportType === 'audit') {
            return true;
        }

        return false;
    }

    /**
     * Crear un nuevo reporte
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.create')) {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isComplianceOfficer();
    }

    /**
     * Exportar reportes
     */
    public function export(User $user, ?int $siteId = null): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.export')) {
            return false;
        }

        // Verificar acceso a la sede si se especifica
        if ($siteId !== null && !$user->canAccessSite($siteId)) {
            return false;
        }

        return true;
    }

    /**
     * Ver dashboard
     */
    public function viewDashboard(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.dashboard')) {
            return false;
        }

        return true;
    }

    /**
     * Ver reportes de cumplimiento
     */
    public function viewComplianceReport(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.compliance') && !$user->hasPermission('reports.view')) {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isComplianceOfficer();
    }

    /**
     * Ver reportes de auditoría
     */
    public function viewAuditReport(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.audit') && !$user->hasPermission('audits.view')) {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isAuditor();
    }

    /**
     * Ver reportes de tendencias
     */
    public function viewTrendsReport(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('reports.view')) {
            return false;
        }

        return $user->isCompanyAdmin() || $user->isComplianceOfficer();
    }

    /**
     * Programar reportes automáticos
     */
    public function schedule(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('reports.create') &&
               ($user->isCompanyAdmin() || $user->isComplianceOfficer());
    }
}
