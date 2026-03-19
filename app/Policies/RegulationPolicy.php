<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Regulation;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Normativas
 * 
 * Permisos granulares:
 * - regulations.view: Ver normativas
 * - regulations.manage: Gestionar normativas
 * - regulations.create: Crear normativas
 * - regulations.update: Actualizar normativas
 * - regulations.delete: Eliminar normativas
 * - requirements.view: Ver requisitos
 * - requirements.manage: Gestionar requisitos
 */
class RegulationPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de normativas
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('regulations.view') || 
               $user->hasPermission('requirements.view') ||
               $user->isSuperAdmin();
    }

    /**
     * Ver una normativa específica
     */
    public function view(User $user, Regulation $regulation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('regulations.view') || 
               $user->hasPermission('requirements.view');
    }

    /**
     * Crear una nueva normativa
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('regulations.manage');
    }

    /**
     * Actualizar una normativa
     */
    public function update(User $user, Regulation $regulation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('regulations.manage');
    }

    /**
     * Eliminar una normativa
     */
    public function delete(User $user, Regulation $regulation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Solo eliminar si no tiene requisitos asociados
        if ($regulation->requirements()->count() > 0) {
            return false;
        }

        return $user->hasPermission('regulations.manage');
    }

    /**
     * Gestionar normativas (crear, actualizar, eliminar)
     */
    public function manage(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('regulations.manage');
    }

    /**
     * Ver requisitos de una normativa
     */
    public function viewRequirements(User $user, Regulation $regulation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.view') ||
               $user->hasPermission('regulations.view') ||
               $user->hasPermission('reports.view');
    }

    /**
     * Gestionar requisitos de una normativa
     */
    public function manageRequirements(User $user, Regulation $regulation): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('requirements.manage') ||
               $user->hasPermission('regulations.manage');
    }
}
