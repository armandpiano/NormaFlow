<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Site;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Sedes
 * 
 * Permisos granulares:
 * - sites.view: Ver sedes
 * - sites.create: Crear sedes
 * - sites.update: Actualizar sedes
 * - sites.delete: Eliminar sedes
 * - sites.assign_users: Asignar usuarios a sedes
 * - sites.view_audits: Ver auditorías de sede
 * - sites.manage_audits: Gestionar auditorías de sede
 * - sites.view_compliance: Ver cumplimiento de sede
 */
class SitePolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de sedes
     */
    public function viewAny(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('sites.view');
    }

    /**
     * Ver una sede específica
     */
    public function view(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.view')) {
            return false;
        }

        // Company admin puede ver todas las sedes de su empresa
        if ($user->isCompanyAdmin()) {
            return $site->company_id === $user->company_id;
        }

        // Site manager y empleados: verificar acceso directo
        return $user->canAccessSite($site->id);
    }

    /**
     * Crear una nueva sede
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->hasPermission('sites.create') && $user->isCompanyAdmin();
    }

    /**
     * Actualizar una sede
     */
    public function update(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.update')) {
            return false;
        }

        // Company admin puede actualizar cualquier sede de su empresa
        if ($user->isCompanyAdmin()) {
            return $site->company_id === $user->company_id;
        }

        // Site manager solo puede actualizar su sede asignada
        if ($user->isSiteManager()) {
            return $user->canAccessSite($site->id);
        }

        return false;
    }

    /**
     * Eliminar una sede
     */
    public function delete(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.delete')) {
            return false;
        }

        // Company admin puede eliminar sedes de su empresa sin auditorías activas
        if ($user->isCompanyAdmin() && $site->company_id === $user->company_id) {
            return $site->audits()->whereNotIn('status', ['completed', 'cancelled'])->count() === 0;
        }

        return false;
    }

    /**
     * Asignar usuarios a una sede
     */
    public function assignUsers(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.assign_users')) {
            return false;
        }

        if ($user->isCompanyAdmin()) {
            return $site->company_id === $user->company_id;
        }

        if ($user->isSiteManager()) {
            return $user->canAccessSite($site->id);
        }

        return false;
    }

    /**
     * Ver auditorías de una sede
     */
    public function viewAudits(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.view_audits') && !$user->hasPermission('audits.view')) {
            return false;
        }

        if ($user->isCompanyAdmin()) {
            return $site->company_id === $user->company_id;
        }

        return $user->canAccessSite($site->id);
    }

    /**
     * Gestionar auditorías de una sede
     */
    public function manageAudits(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.manage_audits') && !$user->hasPermission('audits.manage')) {
            return false;
        }

        if ($user->isCompanyAdmin()) {
            return $site->company_id === $user->company_id;
        }

        return $user->isSiteManager() && $user->canAccessSite($site->id);
    }

    /**
     * Ver cumplimiento de una sede
     */
    public function viewCompliance(User $user, Site $site): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('sites.view_compliance') && !$user->hasPermission('reports.view')) {
            return false;
        }

        if ($user->isCompanyAdmin() || $user->isComplianceOfficer()) {
            return $site->company_id === $user->company_id;
        }

        return $user->canAccessSite($site->id);
    }
}
