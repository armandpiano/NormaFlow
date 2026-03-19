<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Company;
use App\Models\Site;
use App\Models\User;
use App\Models\Audit;
use App\Models\Evidence;
use App\Models\Regulation;
use App\Models\Requirement;
use App\Models\Finding;
use App\Models\ActionPlan;
use App\Models\Role;
use App\Policies\CompanyPolicy;
use App\Policies\SitePolicy;
use App\Policies\UserPolicy;
use App\Policies\AuditPolicy;
use App\Policies\EvidencePolicy;
use App\Policies\RegulationPolicy;
use App\Policies\RequirementPolicy;
use App\Policies\FindingPolicy;
use App\Policies\ActionPlanPolicy;
use App\Policies\RolePolicy;
use App\Policies\ReportPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Company::class => CompanyPolicy::class,
        Site::class => SitePolicy::class,
        User::class => UserPolicy::class,
        Audit::class => AuditPolicy::class,
        Evidence::class => EvidencePolicy::class,
        Regulation::class => RegulationPolicy::class,
        Requirement::class => RequirementPolicy::class,
        Finding::class => FindingPolicy::class,
        ActionPlan::class => ActionPlanPolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        // Gate global para reportes
        \Gate::define('view-report', function (User $user, string $type) {
            $policy = new ReportPolicy();
            return $policy->view($user, $type);
        });

        // Gate para verificar acceso multi-tenant
        \Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
