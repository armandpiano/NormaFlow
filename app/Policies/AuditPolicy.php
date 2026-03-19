<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Audit;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Auditorías
 * 
 * Permisos granulares:
 * - audits.view: Ver auditorías
 * - audits.create: Crear auditorías
 * - audits.update: Actualizar auditorías
 * - audits.delete: Eliminar auditorías
 * - audits.manage: Gestionar auditorías
 * - audits.execute: Ejecutar auditorías
 * - audits.view_results: Ver resultados de auditoría
 * - findings.view: Ver hallazgos
 * - findings.create: Crear hallazgos
 * - findings.manage: Gestionar hallazgos
 */
class AuditPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de auditorías
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('audits.view') ||
               $user->isSuperAdmin() ||
               $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isAuditor();
    }

    /**
     * Ver una auditoría específica
     */
    public function view(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.view')) {
            return false;
        }

        // Company admin y Site manager pueden ver auditorías de su empresa/sede
        if ($user->isCompanyAdmin() || $user->isSiteManager()) {
            return $audit->company_id === $user->company_id &&
                   $user->canAccessSite($audit->site_id);
        }

        // Auditores pueden ver auditorías de sus sedes asignadas
        if ($user->isAuditor()) {
            return $audit->company_id === $user->company_id &&
                   $user->canAccessSite($audit->site_id);
        }

        return false;
    }

    /**
     * Crear una nueva auditoría
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.create') && !$user->hasPermission('audits.manage')) {
            return false;
        }

        return $user->isCompanyAdmin() || $user->isSiteManager();
    }

    /**
     * Actualizar una auditoría
     */
    public function update(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.update') && !$user->hasPermission('audits.manage')) {
            return false;
        }

        // No actualizar si la auditoría está completada o cancelada
        if (in_array($audit->status, ['completed', 'cancelled'])) {
            return false;
        }

        if ($user->isCompanyAdmin()) {
            return $audit->company_id === $user->company_id;
        }

        if ($user->isSiteManager()) {
            return $audit->company_id === $user->company_id &&
                   $user->canAccessSite($audit->site_id);
        }

        return false;
    }

    /**
     * Eliminar una auditoría
     */
    public function delete(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.delete')) {
            return false;
        }

        // No eliminar si tiene hallazgos asociados
        if ($audit->findings()->count() > 0) {
            return false;
        }

        return $user->isCompanyAdmin() && $audit->company_id === $user->company_id;
    }

    /**
     * Gestionar auditorías (crear, actualizar, eliminar)
     */
    public function manage(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('audits.manage') &&
               ($user->isCompanyAdmin() || $user->isSiteManager());
    }

    /**
     * Ejecutar una auditoría
     */
    public function execute(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.execute')) {
            return false;
        }

        // Solo ejecutar auditorías planificadas
        if ($audit->status !== 'planned') {
            return false;
        }

        if ($user->isCompanyAdmin() || $user->isAuditor()) {
            return $audit->company_id === $user->company_id &&
                   $user->canAccessSite($audit->site_id);
        }

        return false;
    }

    /**
     * Ver resultados de una auditoría
     */
    public function viewResults(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.view_results') && !$user->hasPermission('audits.view')) {
            return false;
        }

        if ($user->isCompanyAdmin()) {
            return $audit->company_id === $user->company_id;
        }

        return $audit->company_id === $user->company_id &&
               $user->canAccessSite($audit->site_id);
    }

    /**
     * Ver hallazgos de una auditoría
     */
    public function viewFindings(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.view') && !$user->hasPermission('audits.view')) {
            return false;
        }

        return $this->view($user, $audit);
    }

    /**
     * Crear hallazgos para una auditoría
     */
    public function createFindings(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.create') && !$user->hasPermission('audits.execute')) {
            return false;
        }

        // Solo en auditorías en progreso
        return $audit->status === 'in_progress' &&
               $audit->company_id === $user->company_id &&
               $user->canAccessSite($audit->site_id);
    }

    /**
     * Cerrar una auditoría
     */
    public function close(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.manage')) {
            return false;
        }

        // Solo cerrar auditorías en progreso
        return $audit->status === 'in_progress' &&
               $audit->company_id === $user->company_id &&
               $user->isCompanyAdmin();
    }

    /**
     * Exportar auditoría
     */
    public function export(User $user, Audit $audit): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('audits.view_results') && !$user->hasPermission('reports.export')) {
            return false;
        }

        return $audit->company_id === $user->company_id;
    }
}
