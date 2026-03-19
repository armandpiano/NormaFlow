<?php

declare(strict_types=1);

namespace App\Traits;

use App\Models\Role;
use App\Models\Permission;
use App\Models\Site;
use Illuminate\Support\Collection;

/**
 * Trait HasPermissions
 * 
 * Proporciona capacidades de control de acceso granular multi-tenant
 * con soporte para:
 * - Permisos heredados de roles
 * - Permisos explícitos por usuario
 * - Acceso por sede
 * - Jerarquía de roles
 */
trait HasPermissions
{
    /**
     * Obtener todos los permisos efectivos del usuario
     * (heredados del rol + explícitos)
     */
    public function getAllPermissions(): Collection
    {
        $permissions = collect();

        // Permisos del rol
        $rolePermissions = $this->getRolePermissions();
        
        // Permisos explícitos del usuario
        $explicitPermissions = $this->getExplicitPermissions();
        
        // Combinar y eliminar duplicados
        return $rolePermissions->merge($explicitPermissions)->unique('slug');
    }

    /**
     * Verificar si el usuario tiene un permiso específico
     */
    public function hasPermission(string $permissionSlug): bool
    {
        // Super admin siempre tiene todos los permisos
        if ($this->isSuperAdmin()) {
            return true;
        }

        // Verificar en permisos explícitos primero
        if ($this->hasExplicitPermission($permissionSlug)) {
            // Verificar si el permiso explícito ha expirado
            if ($this->isExplicitPermissionExpired($permissionSlug)) {
                return false;
            }
            return true;
        }

        // Verificar en permisos heredados del rol
        return $this->hasRolePermission($permissionSlug);
    }

    /**
     * Verificar si el usuario tiene TODOS los permisos especificados
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Verificar si el usuario tiene AL MENOS UNO de los permisos
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Asignar un permiso explícito al usuario
     */
    public function givePermission(string $permissionSlug, ?\DateTime $expiresAt = null): void
    {
        $permission = Permission::where('slug', $permissionSlug)->first();
        
        if ($permission && !$this->permissions()->where('slug', $permissionSlug)->exists()) {
            $this->permissions()->attach($permission->id, [
                'expires_at' => $expiresAt?->format('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Revocar un permiso explícito del usuario
     */
    public function revokePermission(string $permissionSlug): void
    {
        $permission = Permission::where('slug', $permissionSlug)->first();
        
        if ($permission) {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * Sincronizar permisos explícitos
     */
    public function syncPermissions(array $permissionSlugs, array $expiresAt = []): void
    {
        $permissionIds = Permission::whereIn('slug', $permissionSlugs)->pluck('id')->toArray();
        
        $syncData = [];
        foreach ($permissionIds as $permissionId) {
            $permission = Permission::find($permissionId);
            $syncData[$permissionId] = [
                'expires_at' => $expiresAt[$permission->slug] ?? null
            ];
        }
        
        $this->permissions()->sync($syncData);
    }

    /**
     * Obtener los roles del usuario
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    /**
     * Obtener los permisos explícitos del usuario
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'user_permissions', 'user_id', 'permission_id')
            ->withPivot('expires_at');
    }

    /**
     * Obtener las sedes asignadas al usuario
     */
    public function sites()
    {
        return $this->belongsToMany(Site::class, 'user_sites', 'user_id', 'site_id');
    }

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $roleSlug): bool
    {
        return $this->roles()->where('slug', $roleSlug)->exists();
    }

    /**
     * Verificar si el usuario tiene alguno de los roles especificados
     */
    public function hasAnyRole(array $roleSlugs): bool
    {
        return $this->roles()->whereIn('slug', $roleSlugs)->exists();
    }

    /**
     * Asignar un rol al usuario
     */
    public function assignRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        
        if ($role && !$this->hasRole($roleSlug)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Revocar un rol del usuario
     */
    public function removeRole(string $roleSlug): void
    {
        $role = Role::where('slug', $roleSlug)->first();
        
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Verificar acceso a una sede específica
     */
    public function canAccessSite(?int $siteId): bool
    {
        if (!$siteId) {
            return true;
        }

        // Super admin y Admin empresa tienen acceso a todas las sedes de su empresa
        if ($this->isSuperAdmin()) {
            return true;
        }

        if ($this->isCompanyAdmin()) {
            // Verificar que la sede pertenece a su empresa
            $site = Site::find($siteId);
            return $site && $site->company_id === $this->company_id;
        }

        // Site manager y empleados: verificar en tabla pivote
        return $this->sites()->where('sites.id', $siteId)->exists();
    }

    /**
     * Verificar acceso a múltiples sedes
     */
    public function canAccessSites(array $siteIds): bool
    {
        foreach ($siteIds as $siteId) {
            if (!$this->canAccessSite($siteId)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Asignar una sede al usuario
     */
    public function assignSite(int $siteId): void
    {
        if (!$this->sites()->where('sites.id', $siteId)->exists()) {
            $this->sites()->attach($siteId);
        }
    }

    /**
     * Revocar acceso a una sede
     */
    public function revokeSite(int $siteId): void
    {
        $this->sites()->detach($siteId);
    }

    /**
     * Obtener todas las sedes accesibles por el usuario
     */
    public function getAccessibleSites(): Collection
    {
        if ($this->isSuperAdmin()) {
            return Site::all();
        }

        if ($this->isCompanyAdmin()) {
            return Site::where('company_id', $this->company_id)->get();
        }

        return $this->sites()->get();
    }

    /**
     * Verificar si es Super Admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin') || $this->role === 'super_admin';
    }

    /**
     * Verificar si es Admin de Empresa
     */
    public function isCompanyAdmin(): bool
    {
        return $this->hasRole('company_admin') || $this->role === 'company_admin';
    }

    /**
     * Verificar si es Responsable de Sede
     */
    public function isSiteManager(): bool
    {
        return $this->hasRole('site_manager') || $this->role === 'site_manager';
    }

    /**
     * Verificar si es Auditor
     */
    public function isAuditor(): bool
    {
        return $this->hasAnyRole(['internal_auditor', 'external_auditor']) || 
               in_array($this->role, ['internal_auditor', 'external_auditor']);
    }

    /**
     * Verificar si es Auditor Interno
     */
    public function isInternalAuditor(): bool
    {
        return $this->hasRole('internal_auditor') || $this->role === 'internal_auditor';
    }

    /**
     * Verificar si es Auditor Externo
     */
    public function isExternalAuditor(): bool
    {
        return $this->hasRole('external_auditor') || $this->role === 'external_auditor';
    }

    /**
     * Verificar si es Responsable de Cumplimiento
     */
    public function isComplianceOfficer(): bool
    {
        return $this->hasRole('compliance_officer') || $this->role === 'compliance_officer';
    }

    /**
     * Verificar si es Responsable de Capacitación
     */
    public function isTrainingManager(): bool
    {
        return $this->hasRole('training_manager') || $this->role === 'training_manager';
    }

    /**
     * Verificar si es Empleado
     */
    public function isEmployee(): bool
    {
        return $this->hasRole('employee') || $this->role === 'employee';
    }

    /**
     * Verificar si es Consulta/Lector
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer') || $this->role === 'viewer';
    }

    /**
     * Obtener permisos del rol del usuario
     */
    protected function getRolePermissions(): Collection
    {
        $permissions = collect();

        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
            
            // Incluir permisos de roles superiores en la jerarquía
            $permissions = $permissions->merge($this->getInheritedPermissions($role));
        }

        // Si no tiene roles pero tiene rol directo (legacy)
        if ($permissions->isEmpty() && isset($this->role)) {
            $directRole = Role::where('slug', $this->role)->first();
            if ($directRole) {
                $permissions = $permissions->merge($directRole->permissions);
            }
        }

        return $permissions->unique('id');
    }

    /**
     * Obtener permisos heredados de roles superiores en la jerarquía
     */
    protected function getInheritedPermissions(Role $role): Collection
    {
        $hierarchy = config('permissions.hierarchy', []);
        $inheritedPermissions = collect();

        if (!isset($hierarchy[$role->slug])) {
            return $inheritedPermissions;
        }

        foreach ($hierarchy[$role->slug] as $inheritedRoleSlug) {
            $inheritedRole = Role::where('slug', $inheritedRoleSlug)->first();
            if ($inheritedRole) {
                $inheritedPermissions = $inheritedPermissions->merge($inheritedRole->permissions);
            }
        }

        return $inheritedPermissions;
    }

    /**
     * Obtener permisos explícitos del usuario
     */
    protected function getExplicitPermissions(): Collection
    {
        return $this->permissions()
            ->where(function ($query) {
                $query->whereNull('user_permissions.expires_at')
                    ->orWhere('user_permissions.expires_at', '>', now());
            })
            ->get();
    }

    /**
     * Verificar si el usuario tiene un permiso explícito
     */
    protected function hasExplicitPermission(string $permissionSlug): bool
    {
        return $this->permissions()
            ->where('slug', $permissionSlug)
            ->exists();
    }

    /**
     * Verificar si un permiso explícito ha expirado
     */
    protected function isExplicitPermissionExpired(string $permissionSlug): bool
    {
        $permission = $this->permissions()
            ->where('slug', $permissionSlug)
            ->first();

        if (!$permission) {
            return true;
        }

        $expiresAt = $permission->pivot->expires_at;
        
        if (!$expiresAt) {
            return false;
        }

        return now()->greaterThan($expiresAt);
    }

    /**
     * Verificar si el usuario tiene un permiso heredado del rol
     */
    protected function hasRolePermission(string $permissionSlug): bool
    {
        // Verificar en roles del usuario
        if ($this->roles()->whereHas('permissions', function ($query) use ($permissionSlug) {
            $query->where('slug', $permissionSlug);
        })->exists()) {
            return true;
        }

        // Verificar en roles heredados
        foreach ($this->roles as $role) {
            $inheritedPermissions = $this->getInheritedPermissions($role);
            if ($inheritedPermissions->where('slug', $permissionSlug)->isNotEmpty()) {
                return true;
            }
        }

        // Legacy: verificar en rol directo
        if (isset($this->role)) {
            $directRole = Role::where('slug', $this->role)->first();
            if ($directRole && $directRole->permissions()->where('slug', $permissionSlug)->exists()) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->is_active === true || $this->is_active === 1;
    }

    /**
     * Verificar si el usuario puede realizar acciones
     */
    public function canPerformAction(string $permission, ?int $resourceSiteId = null): bool
    {
        if (!$this->isActive()) {
            return false;
        }

        if (!$this->hasPermission($permission)) {
            return false;
        }

        if ($resourceSiteId !== null && !$this->canAccessSite($resourceSiteId)) {
            return false;
        }

        return true;
    }

    /**
     * Obtener nivel de acceso (para logs/auditoría)
     */
    public function getAccessLevel(): string
    {
        if ($this->isSuperAdmin()) {
            return 'platform';
        }

        if ($this->isCompanyAdmin()) {
            return 'company';
        }

        if ($this->isSiteManager()) {
            return 'site';
        }

        return 'basic';
    }

    /**
     * Scope para filtrar por tenant
     */
    public function scopeTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope para filtrar por empresa
     */
    public function scopeCompany($query, int $companyId)
    {
        return $query->where('company_id', $companyId);
    }

    /**
     * Boot del trait
     */
    public static function bootHasPermissions(): void
    {
        // Al crear un usuario, asignar rol por defecto
        static::created(function ($user) {
            if (!$user->roles()->exists() && isset($user->role)) {
                $role = Role::where('slug', $user->role)->first();
                if ($role) {
                    $user->roles()->attach($role->id);
                }
            }
        });
    }
}
