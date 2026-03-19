<?php

namespace App\UI\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'company_admin', 'site_manager']);
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $targetUser): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        // Can view users in same company
        return $user->company_id === $targetUser->company_id;
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $targetUser): bool
    {
        // Can update own profile
        if ($user->id === $targetUser->id) {
            return true;
        }

        // Super admin can update anyone
        if ($user->role === 'super_admin') {
            return true;
        }

        // Company admin can update users in their company
        if ($user->role === 'company_admin' && $user->company_id === $targetUser->company_id) {
            return true;
        }

        // Site manager can update employees in their site
        if ($user->role === 'site_manager' && $user->site_id === $targetUser->site_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $targetUser): bool
    {
        // Cannot delete self
        if ($user->id === $targetUser->id) {
            return false;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can activate/deactivate users.
     */
    public function toggleStatus(User $user, User $targetUser): bool
    {
        if ($user->id === $targetUser->id) {
            return false;
        }

        return in_array($user->role, ['super_admin', 'company_admin']);
    }
}
