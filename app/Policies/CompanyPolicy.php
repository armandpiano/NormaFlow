<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Company;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Empresas
 * 
 * Permisos granulares:
 * - companies.view: Ver empresas
 * - companies.create: Crear empresas
 * - companies.update: Actualizar empresas
 * - companies.delete: Eliminar empresas
 * - companies.manage_settings: Gestionar configuración de empresa
 * - companies.view_users: Ver usuarios de empresa
 * - companies.manage_users: Gestionar usuarios de empresa
 * - companies.view_sites: Ver sedes de empresa
 * - companies.manage_sites: Gestionar sedes de empresa
 * - companies.view_reports: Ver reportes de empresa
 * - companies.export: Exportar datos de empresa
 */
class CompanyPolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de empresas
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('companies.view');
    }

    /**
     * Ver una empresa específica
     */
    public function view(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.view')) {
            return false;
        }

        // Super admin puede ver todas
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Company admin y otros usuarios de la empresa pueden ver su propia empresa
        return $company->id === $user->company_id;
    }

    /**
     * Crear una nueva empresa
     */
    public function create(User $user): bool
    {
        return $user->isSuperAdmin() || $user->hasPermission('companies.create');
    }

    /**
     * Actualizar una empresa
     */
    public function update(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.update')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        // Company admin puede actualizar su propia empresa
        return $company->id === $user->company_id && $user->isCompanyAdmin();
    }

    /**
     * Eliminar una empresa
     */
    public function delete(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.delete')) {
            return false;
        }

        // Solo super admin puede eliminar
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Company admin solo puede eliminar si no tiene sedes activas
        if ($company->id === $user->company_id && $user->isCompanyAdmin()) {
            return $company->sites()->where('status', 'active')->count() === 0;
        }

        return false;
    }

    /**
     * Gestionar configuración de empresa
     */
    public function manageSettings(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.manage_settings')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id && $user->isCompanyAdmin();
    }

    /**
     * Ver usuarios de una empresa
     */
    public function viewUsers(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.view_users')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id;
    }

    /**
     * Gestionar usuarios de una empresa
     */
    public function manageUsers(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.manage_users')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id && $user->isCompanyAdmin();
    }

    /**
     * Ver sedes de una empresa
     */
    public function viewSites(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.view_sites')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id;
    }

    /**
     * Gestionar sedes de una empresa
     */
    public function manageSites(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.manage_sites')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id && $user->isCompanyAdmin();
    }

    /**
     * Ver reportes de una empresa
     */
    public function viewReports(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.view_reports')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id;
    }

    /**
     * Exportar datos de empresa
     */
    public function export(User $user, Company $company): bool
    {
        if (!$user->hasPermission('companies.export')) {
            return false;
        }

        if ($user->isSuperAdmin()) {
            return true;
        }

        return $company->id === $user->company_id;
    }
}
