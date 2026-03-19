<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Finding;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Hallazgos
 * 
 * Permisos granulares:
 * - findings.view: Ver hallazgos
 * - findings.create: Crear hallazgos
 * - findings.update: Actualizar hallazgos
 * - findings.delete: Eliminar hallazgos
 * - findings.manage: Gestionar hallazgos
 * - findings.close: Cerrar hallazgos
 * - action_plans.view: Ver planes de acción
 * - action_plans.create: Crear planes de acción
 * - action_plans.manage: Gestionar planes de acción
 */
class FindingPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de hallazgos
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('findings.view') ||
               $user->hasPermission('audits.view') ||
               $user->isSuperAdmin() ||
               $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isAuditor();
    }

    /**
     * Ver un hallazgo específico
     */
    public function view(User $user, Finding $finding): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.view') && !$user->hasPermission('audits.view')) {
            return false;
        }

        return $finding->company_id === $user->company_id &&
               $user->canAccessSite($finding->site_id);
    }

    /**
     * Crear un nuevo hallazgo
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.create')) {
            return false;
        }

        return $user->isAuditor() || $user->isCompanyAdmin() || $user->isSiteManager();
    }

    /**
     * Actualizar un hallazgo
     */
    public function update(User $user, Finding $finding): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.update') && !$user->hasPermission('findings.manage')) {
            return false;
        }

        // Hallazgos cerrados no se pueden actualizar
        if ($finding->status === 'closed') {
            return false;
        }

        return $finding->company_id === $user->company_id &&
               $user->canAccessSite($finding->site_id);
    }

    /**
     * Eliminar un hallazgo
     */
    public function delete(User $user, Finding $finding): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.delete')) {
            return false;
        }

        // Solo audiores y admins pueden eliminar
        return ($user->isAuditor() || $user->isCompanyAdmin()) &&
               $finding->company_id === $user->company_id;
    }

    /**
     * Gestionar hallazgos
     */
    public function manage(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('findings.manage') &&
               ($user->isCompanyAdmin() || $user->isAuditor());
    }

    /**
     * Cerrar un hallazgo
     */
    public function close(User $user, Finding $finding): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.close') && !$user->hasPermission('findings.manage')) {
            return false;
        }

        // Verificar que todos los planes de acción estén cerrados
        $openActions = $finding->actionPlans()->whereNotIn('status', ['completed', 'cancelled'])->count();
        if ($openActions > 0) {
            return false;
        }

        return $user->isCompanyAdmin() || $user->isAuditor();
    }

    /**
     * Asignar responsable a un hallazgo
     */
    public function assignResponsible(User $user, Finding $finding): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('findings.manage')) {
            return false;
        }

        return $finding->company_id === $user->company_id;
    }

    /**
     * Ver planes de acción de un hallazgo
     */
    public function viewActionPlans(User $user, Finding $finding): bool
    {
        return $this->view($user, $finding);
    }

    /**
     * Crear plan de acción para un hallazgo
     */
    public function createActionPlan(User $user, Finding $finding): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.create') && !$user->hasPermission('findings.manage')) {
            return false;
        }

        return $finding->company_id === $user->company_id &&
               $user->canAccessSite($finding->site_id);
    }
}
