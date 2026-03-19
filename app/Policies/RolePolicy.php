<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Role;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Roles
 * 
 * Permisos granulares:
 * - roles.view: Ver roles
 * - roles.create: Crear roles
 * - roles.update: Actualizar roles
 * - roles.delete: Eliminar roles
 * - roles.manage: Gestionar roles
 */
class RolePolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de roles
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('roles.view') ||
               $user->hasPermission('users.assign_role') ||
               $user->isSuperAdmin();
    }

    /**
     * Ver un rol específico
     */
    public function view(User $user, Role $role): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('roles.view');
    }

    /**
     * Crear un nuevo rol
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('roles.create') || $user->hasPermission('roles.manage');
    }

    /**
     * Actualizar un rol
     */
    public function update(User $user, Role $role): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('roles.update') && !$user->hasPermission('roles.manage')) {
            return false;
        }

        // No modificar roles del sistema
        $systemRoles = ['super_admin', 'company_admin', 'site_manager', 'auditor', 'employee'];
        return !in_array($role->slug, $systemRoles);
    }

    /**
     * Eliminar un rol
     */
    public function delete(User $user, Role $role): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('roles.delete')) {
            return false;
        }

        // No eliminar roles del sistema
        $systemRoles = ['super_admin', 'company_admin', 'site_manager', 'auditor', 'employee'];
        if (in_array($role->slug, $systemRoles)) {
            return false;
        }

        // No eliminar si tiene usuarios asignados
        return $role->users()->count() === 0;
    }

    /**
     * Gestionar roles
     */
    public function manage(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('roles.manage');
    }

    /**
     * Asignar permisos a un rol
     */
    public function assignPermissions(User $user, Role $role): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('roles.manage')) {
            return false;
        }

        // No modificar permisos de roles del sistema
        $systemRoles = ['super_admin', 'company_admin'];
        return !in_array($role->slug, $systemRoles);
    }
}
