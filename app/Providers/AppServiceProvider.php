<?php

namespace App\Providers;

use App\Domain\Companies\Repositories\CompanyRepositoryInterface;
use App\Domain\Companies\Repositories\SiteRepositoryInterface;
use App\Domain\Compliance\Repositories\RegulationRepositoryInterface;
use App\Domain\Compliance\Repositories\RequirementRepositoryInterface;
use App\Domain\Compliance\Repositories\EvidenceRepositoryInterface;
use App\Domain\Compliance\Repositories\AuditRepositoryInterface;
use App\Domain\Identity\Repositories\UserRepositoryInterface;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentCompanyRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentSiteRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentRegulationRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentRequirementRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentEvidenceRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentAuditRepository;
use App\Infrastructure\Persistence\Eloquent\Repositories\EloquentUserRepository;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind Repository Interfaces to Implementations
        $this->app->bind(CompanyRepositoryInterface::class, EloquentCompanyRepository::class);
        $this->app->bind(SiteRepositoryInterface::class, EloquentSiteRepository::class);
        $this->app->bind(RegulationRepositoryInterface::class, EloquentRegulationRepository::class);
        $this->app->bind(RequirementRepositoryInterface::class, EloquentRequirementRepository::class);
        $this->app->bind(EvidenceRepositoryInterface::class, EloquentEvidenceRepository::class);
        $this->app->bind(AuditRepositoryInterface::class, EloquentAuditRepository::class);
        $this->app->bind(UserRepositoryInterface::class, EloquentUserRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register events
        Event::listen(
            \App\Domain\Companies\Events\CompanyCreatedEvent::class,
            \App\Application\Listeners\SendCompanyWelcomeNotification::class
        );

        Event::listen(
            \App\Domain\Compliance\Events\EvidenceUploadedEvent::class,
            \App\Application\Listeners\NotifyEvidenceUploaded::class
        );

        Event::listen(
            \App\Domain\Compliance\Events\RequirementExpiringEvent::class,
            \App\Application\Listeners\SendExpirationReminder::class
        );
    }
}
