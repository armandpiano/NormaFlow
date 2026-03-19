<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\User;
use App\Models\Evidence;
use App\Traits\HasPermissions;

/**
 * Policy para gestión de Evidencias
 * 
 * Permisos granulares:
 * - evidences.view: Ver evidencias
 * - evidences.upload: Subir evidencias
 * - evidences.update: Actualizar evidencias
 * - evidences.delete: Eliminar evidencias
 * - evidences.approve: Aprobar evidencias
 * - evidences.reject: Rechazar evidencias
 * - evidences.download: Descargar evidencias
 */
class EvidencePolicy
{
    use HasPermissions;

    /**
     * Ver si el usuario puede ver la lista de evidencias
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('evidences.view') ||
               $user->isSuperAdmin() ||
               $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isComplianceOfficer() ||
               $user->isAuditor();
    }

    /**
     * Ver una evidencia específica
     */
    public function view(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.view')) {
            return false;
        }

        return $evidence->company_id === $user->company_id &&
               $user->canAccessSite($evidence->site_id);
    }

    /**
     * Crear/Subir una nueva evidencia
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.upload')) {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isEmployee() ||
               $user->isComplianceOfficer();
    }

    /**
     * Actualizar una evidencia
     */
    public function update(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.update')) {
            return false;
        }

        // Solo si la evidencia está pendiente o rechazada
        if (!in_array($evidence->status, ['pending', 'rejected'])) {
            return false;
        }

        // El creador de la evidencia puede actualizarla
        if ($evidence->user_id === $user->id) {
            return true;
        }

        // Company admin, site manager o compliance officer pueden actualizar
        return $user->isCompanyAdmin() ||
               $user->isSiteManager() ||
               $user->isComplianceOfficer();
    }

    /**
     * Eliminar una evidencia
     */
    public function delete(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.delete')) {
            return false;
        }

        // Solo si la evidencia está pendiente o rechazada
        if (!in_array($evidence->status, ['pending', 'rejected'])) {
            return false;
        }

        // El creador puede eliminar su propia evidencia
        if ($evidence->user_id === $user->id) {
            return true;
        }

        return $user->isCompanyAdmin() || $user->isComplianceOfficer();
    }

    /**
     * Aprobar una evidencia
     */
    public function approve(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.approve')) {
            return false;
        }

        // Solo aprobar evidencias pendientes
        if ($evidence->status !== 'pending') {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isComplianceOfficer() ||
               ($user->isSiteManager() && $user->canAccessSite($evidence->site_id));
    }

    /**
     * Rechazar una evidencia
     */
    public function reject(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.reject')) {
            return false;
        }

        // Solo rechazar evidencias pendientes
        if ($evidence->status !== 'pending') {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isComplianceOfficer() ||
               ($user->isSiteManager() && $user->canAccessSite($evidence->site_id));
    }

    /**
     * Descargar una evidencia
     */
    public function download(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.download') && !$user->hasPermission('evidences.view')) {
            return false;
        }

        return $evidence->company_id === $user->company_id &&
               $user->canAccessSite($evidence->site_id);
    }

    /**
     * Verificar una evidencia
     */
    public function verify(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        if (!$user->hasPermission('evidences.approve')) {
            return false;
        }

        return $user->isCompanyAdmin() ||
               $user->isComplianceOfficer();
    }

    /**
     * Agregar comentario a evidencia
     */
    public function comment(User $user, Evidence $evidence): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $evidence->company_id === $user->company_id &&
               $user->canAccessSite($evidence->site_id);
    }
}
