<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Usuarios
 * 
 * Permisos granulares:
 * - users.view: Ver usuarios
 * - users.create: Crear usuarios
 * - users.update: Actualizar usuarios
 * - users.delete: Eliminar usuarios
 * - users.assign_role: Asignar roles a usuarios
 * - users.assign_sites: Asignar sedes a usuarios
 * - users.reset_password: Resetear contraseña
 * - users.deactivate: Desactivar usuarios
 * - users.view_profile: Ver perfiles
 * - users.update_profile: Actualizar perfil propio
 */
class UserPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de usuarios
     */
    public function viewAny(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('users.view');
    }

    /**
     * Ver un usuario específico
     */
    public function view(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isSuperAdmin()) {
            return true;
        }

        if (!$currentUser->hasPermission('users.view')) {
            return false;
        }

        // Verificar que ambos usuarios estén en la misma empresa
        return $currentUser->company_id === $targetUser->company_id;
    }

    /**
     * Crear un nuevo usuario
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('users.create') && 
               ($user->isCompanyAdmin() || $user->isSiteManager());
    }

    /**
     * Actualizar un usuario
     */
    public function update(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isSuperAdmin()) {
            return true;
        }

        if (!$currentUser->hasPermission('users.update')) {
            return false;
        }

        // Usuario puede actualizar su propio perfil
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        // Company admin puede actualizar usuarios de su empresa
        if ($currentUser->isCompanyAdmin()) {
            return $currentUser->company_id === $targetUser->company_id;
        }

        // Site manager puede actualizar empleados de sus sedes
        if ($currentUser->isSiteManager()) {
            return $currentUser->company_id === $targetUser->company_id &&
                   $currentUser->canAccessSite($targetUser->site_id);
        }

        return false;
    }

    /**
     * Eliminar un usuario
     */
    public function delete(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isSuperAdmin()) {
            // Super admin no puede eliminarse a sí mismo
            return $currentUser->id !== $targetUser->id;
        }

        if (!$currentUser->hasPermission('users.delete')) {
            return false;
        }

        // No puede eliminarse a sí mismo
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        // Company admin puede eliminar usuarios de su empresa (excepto otros admins)
        if ($currentUser->isCompanyAdmin() && $currentUser->company_id === $targetUser->company_id) {
            return !$targetUser->isSuperAdmin() && !$targetUser->isCompanyAdmin();
        }

        return false;
    }

    /**
     * Asignar roles a un usuario
     */
    public function assignRole(User $currentUser, User $targetUser): bool
    {
if ($currentUser->isSuperAdmin()) {
            return true;
        }

        if (!$currentUser->hasPermission('users.assign_role')) {
            return false;
        }

        // Company admin puede asignar roles dentro de su empresa
        if ($currentUser->isCompanyAdmin() && $currentUser->company_id === $targetUser->company_id) {
            // No puede asignar rol de super_admin o company_admin
            return true;
        }

        return false;
    }

    /**
     * Asignar sedes a un usuario
     */
    public function assignSites(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isSuperAdmin()) {
            return true;
        }

        if (!$currentUser->hasPermission('users.assign_sites')) {
            return false;
        }

        if ($currentUser->isCompanyAdmin()) {
            return $currentUser->company_id === $targetUser->company_id;
        }

        if ($currentUser->isSiteManager()) {
            return $currentUser->company_id === $targetUser->company_id &&
                   $currentUser->canAccessSite($targetUser->site_id);
        }

        return false;
    }

    /**
     * Resetear contraseña de un usuario
     */
    public function resetPassword(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isSuperAdmin()) {
            return true;
        }

        if (!$currentUser->hasPermission('users.reset_password')) {
            return false;
        }

        return $currentUser->company_id === $targetUser->company_id;
    }

    /**
     * Desactivar un usuario
     */
    public function deactivate(User $currentUser, User $targetUser): bool
    {
        if ($currentUser->isSuperAdmin()) {
            return $currentUser->id !== $targetUser->id;
        }

        if (!$currentUser->hasPermission('users.deactivate')) {
            return false;
        }

        // No puede desactivarse a sí mismo
        if ($currentUser->id === $targetUser->id) {
            return false;
        }

        return $currentUser->isCompanyAdmin() && $currentUser->company_id === $targetUser->company_id;
    }

    /**
     * Ver perfil de usuario
     */
    public function viewProfile(User $currentUser, User $targetUser): bool
    {
        // Cualquier usuario puede ver su propio perfil
        if ($currentUser->id === $targetUser->id) {
            return true;
        }

        if ($currentUser->isSuperAdmin()) {
            return true;
        }

        if (!$currentUser->hasPermission('users.view_profile')) {
            return false;
        }

        return $currentUser->company_id === $targetUser->company_id;
    }

    /**
     * Actualizar perfil propio
     */
    public function updateProfile(User $user): bool
    {
        // Cualquier usuario puede actualizar su propio perfil
        return true;
    }

    /**
     * Cambiar contraseña propia
     */
    public function changePassword(User $user): bool
    {
        return true;
    }
}
