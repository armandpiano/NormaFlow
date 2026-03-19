<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Domain\Identity\Entities\User;
use App\Domain\Companies\Entities\Company;
use App\Domain\Compliance\Entities\Evidence;
use App\Domain\Compliance\Entities\Audit;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => \App\UI\Policies\UserPolicy::class,
        Company::class => \App\UI\Policies\CompanyPolicy::class,
        Evidence::class => \App\UI\Policies\EvidencePolicy::class,
        Audit::class => \App\UI\Policies\AuditPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Define Gates for permissions
        Gate::define('manage-companies', fn (User $user) => $user->canManageCompanies());
        Gate::define('manage-users', fn (User $user) => $user->canManageUsers());
        Gate::define('manage-regulations', fn (User $user) => $user->canManageRegulations());
        Gate::define('upload-evidence', fn (User $user) => $user->canUploadEvidence());
        Gate::define('conduct-audits', fn (User $user) => $user->canConductAudits());
        Gate::define('view-reports', fn (User $user) => $user->canViewReports());
        Gate::define('manage-settings', fn (User $user) => $user->isSuperAdmin());
    }
}
