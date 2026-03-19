<?php

namespace App\UI\Policies;

use App\Models\User;
use App\Models\Company;

class CompanyPolicy
{
    /**
     * Determine whether the user can view any companies.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['super_admin', 'company_admin']);
    }

    /**
     * Determine whether the user can view the company.
     */
    public function view(User $user, Company $company): bool
    {
        // Super admin can view all
        if ($user->role === 'super_admin') {
            return true;
        }

        // Company admin can view their company
        if ($user->role === 'company_admin' && $user->company_id === $company->id) {
            return true;
        }

        // Site manager and auditor can view if they're assigned to the company
        return $user->company_id === $company->id;
    }

    /**
     * Determine whether the user can create companies.
     */
    public function create(User $user): bool
    {
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can update the company.
     */
    public function update(User $user, Company $company): bool
    {
        if ($user->role === 'super_admin') {
            return true;
        }

        return $user->role === 'company_admin' && $user->company_id === $company->id;
    }

    /**
     * Determine whether the user can delete the company.
     */
    public function delete(User $user, Company $company): bool
    {
        return $user->role === 'super_admin';
    }

    /**
     * Determine whether the user can suspend the company.
     */
    public function suspend(User $user, Company $company): bool
    {
        return $user->role === 'super_admin';
    }
}
