<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Requirement;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Requisitos
 * 
 * Permisos granulares:
 * - requirements.view: Ver requisitos
 * - requirements.create: Crear requisitos
 * - requirements.update: Actualizar requisitos
 * - requirements.delete: Eliminar requisitos
 * - requirements.manage: Gestionar requisitos
 * - evidences.upload: Subir evidencias
 */
class RequirementPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de requisitos
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('requirements.view') ||
               $user->hasPermission('regulations.view') ||
               $user->isSuperAdmin();
    }

    /**
     * Ver un requisito específico
     */
    public function view(User $user, Requirement $requirement): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.view') ||
               $user->hasPermission('regulations.view') ||
               $user->hasPermission('reports.view');
    }

    /**
     * Crear un nuevo requisito
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.manage');
    }

    /**
     * Actualizar un requisito
     */
    public function update(User $user, Requirement $requirement): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.manage');
    }

    /**
     * Eliminar un requisito
     */
    public function delete(User $user, Requirement $requirement): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // No eliminar si tiene evidencias asociadas
        if ($requirement->evidences()->count() > 0) {
            return false;
        }

        return $user->hasPermission('requirements.manage');
    }

    /**
     * Gestionar requisitos
     */
    public function manage(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.manage');
    }

    /**
     * Subir evidencias para un requisito
     */
    public function uploadEvidence(User $user, Requirement $requirement): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('evidences.upload');
    }

    /**
     * Ver cumplimiento del requisito
     */
    public function viewCompliance(User $user, Requirement $requirement): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.view') ||
               $user->hasPermission('reports.view') ||
               $user->isCompanyAdmin() ||
               $user->isComplianceOfficer();
    }
}
