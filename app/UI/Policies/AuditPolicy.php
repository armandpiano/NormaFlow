<?php

namespace App\UI\Policies;

use App\Models\User;
use App\Models\Audit;

class AuditPolicy
{
    /**
     * Determine whether the user can view any audits.
     */
    public function viewAny(User $user): bool
    {
        return !in_array($user->role, ['super_admin']);
    }

    /**
     * Determine whether the user can view the audit.
     */
    public function view(User $user, Audit $audit): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        // Auditor assigned to the audit
        if ($user->id === $audit->user_id) {
            return true;
        }

        // Company admin and site manager can view
        return in_array($user->role, ['company_admin', 'site_manager']);
    }

    /**
     * Determine whether the user can create audits.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'company_admin', 'auditor']);
    }

    /**
     * Determine whether the user can update the audit.
     */
    public function update(User $user, Audit $audit): bool
    {
        // Cannot update completed or cancelled audits
        if (in_array($audit->status, ['completada', 'cancelada'])) {
            return false;
        }

        // Auditor assigned can update
        if ($user->id === $audit->user_id) {
            return true;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can start the audit.
     */
    public function start(User $user, Audit $audit): bool
    {
        if (!$audit->isPlanned()) {
            return false;
        }

        if ($user->id === $audit->user_id) {
            return true;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can complete the audit.
     */
    public function complete(User $user, Audit $audit): bool
    {
        if (!$audit->isInProgress()) {
            return false;
        }

        if ($user->id === $audit->user_id) {
            return true;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can cancel the audit.
     */
    public function cancel(User $user, Audit $audit): bool
    {
        if ($audit->isCompleted()) {
            return false;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can delete the audit.
     */
    public function delete(User $user, Audit $audit): bool
    {
        // Can only delete planned audits
        if (!$audit->isPlanned()) {
            return false;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }
}
