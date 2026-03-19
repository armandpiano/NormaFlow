<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\ActionPlan;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Planes de Acción
 * 
 * Permisos granulares:
 * - action_plans.view: Ver planes de acción
 * - action_plans.create: Crear planes de acción
 * - action_plans.update: Actualizar planes de acción
 * - action_plans.delete: Eliminar planes de acción
 * - action_plans.manage: Gestionar planes de acción
 * - action_plans.complete: Completar planes de acción
 */
class ActionPlanPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de planes de acción
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('action_plans.view') ||
               $user->hasPermission('findings.view') ||
               $user->hasPermission('audits.view') ||
               $user->isSuperAdmin() ||
               $user->isCompanyAdmin() ||
               $user->isSiteManager();
    }

    /**
     * Ver un plan de acción específico
     */
    public function view(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.view') &&
            !$user->hasPermission('findings.view') &&
            !$user->hasPermission('audits.view')) {
            return false;
        }

        return $actionPlan->company_id === $user->company_id &&
               $user->canAccessSite($actionPlan->site_id);
    }

    /**
     * Crear un nuevo plan de acción
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.create') && !$user->hasPermission('action_plans.manage')) {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isAuditor();
    }

    /**
     * Actualizar un plan de acción
     */
    public function update(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.update') && !$user->hasPermission('action_plans.manage')) {
            return false;
        }

        // Planes completados no se pueden actualizar
        if (in_array($actionPlan->status, ['completed', 'cancelled'])) {
            return false;
        }

        return $actionPlan->company_id === $user->company_id &&
               $user->canAccessSite($actionPlan->site_id);
    }

    /**
     * Eliminar un plan de acción
     */
    public function delete(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.delete')) {
            return false;
        }

        return $user->isCompanyAdmin() && $actionPlan->company_id === $user->company_id;
    }

    /**
     * Gestionar planes de acción
     */
    public function manage(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('action_plans.manage') &&
               ($user->isCompanyAdmin() || $user->isSiteManager());
    }

    /**
     * Completar un plan de acción
     */
    public function complete(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.complete') && !$user->hasPermission('action_plans.manage')) {
            return false;
        }

        // Solo completar planes en progreso
        if ($actionPlan->status !== 'in_progress') {
            return false;
        }

        return $actionPlan->company_id === $user->company_id &&
               $user->canAccessSite($actionPlan->site_id);
    }

    /**
     * Asignar responsable
     */
    public function assignResponsible(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.manage')) {
            return false;
        }

        return $actionPlan->company_id === $user->company_id;
    }

    /**
     * Agregar evidencia de cierre
     */
    public function addClosureEvidence(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.upload') && !$user->hasPermission('action_plans.manage')) {
            return false;
        }

        return $actionPlan->company_id === $user->company_id &&
               $user->canAccessSite($actionPlan->site_id);
    }

    /**
     * Aprobar cierre de plan de acción
     */
    public function approveClosure(User $user, ActionPlan $actionPlan): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('action_plans.manage')) {
            return false;
        }

        return $user->isCompanyAdmin() || $user->isAuditor();
    }
}
