<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Domain\Shared\Context\TenantContext;

class TenancyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Singleton for tenant context
        $this->app->singleton(TenantContext::class, function ($app) {
            return new TenantContext(
                tenantId: $app['request']->header('X-Tenant-ID') 
                    ? (int) $app['request']->header('X-Tenant-ID') 
                    : null,
                tenantSlug: $app['request']->route('tenant') ?? null
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Apply tenant scope globally to all models
        Model::addGlobalScope('tenant', function ($builder) {
            if ($tenantId = app(TenantContext::class)->getTenantId()) {
                $builder->where('tenant_id', $tenantId);
            }
        });
    }
}
