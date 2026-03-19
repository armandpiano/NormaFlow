<?php

namespace App\UI\Policies;

use App\Models\User;
use App\Models\Evidence;

class EvidencePolicy
{
    /**
     * Determine whether the user can view any evidences.
     */
    public function viewAny(User $user): bool
    {
        return !in_array($user->role, ['super_admin']);
    }

    /**
     * Determine whether the user can view the evidence.
     */
    public function view(User $user, Evidence $evidence): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        // User who uploaded the evidence
        if ($user->id === $evidence->user_id) {
            return true;
        }

        // Company admins and auditors can view evidences from their company
        if (in_array($user->role, ['company_admin', 'auditor'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create evidences.
     */
    public function create(User $user): bool
    {
        return !in_array($user->role, ['super_admin']);
    }

    /**
     * Determine whether the user can update the evidence.
     */
    public function update(User $user, Evidence $evidence): bool
    {
        // Only pending evidences can be updated
        if (!$evidence->isPending()) {
            return false;
        }

        // User who uploaded can update
        if ($user->id === $evidence->user_id) {
            return true;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can delete the evidence.
     */
    public function delete(User $user, Evidence $evidence): bool
    {
        // Only pending evidences can be deleted
        if (!$evidence->isPending()) {
            return false;
        }

        // User who uploaded can delete
        if ($user->id === $evidence->user_id) {
            return true;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can approve evidence.
     */
    public function approve(User $user, Evidence $evidence): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return in_array($user->role, ['company_admin', 'auditor']);
    }

    /**
     * Determine whether the user can reject evidence.
     */
    public function reject(User $user, Evidence $evidence): bool
    {
        return $this->approve($user, $evidence);
    }
}
