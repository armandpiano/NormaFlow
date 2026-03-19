<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        // Company Events
        \App\Domain\Companies\Events\CompanyCreatedEvent::class => [
            \App\Application\Listeners\SendCompanyWelcomeNotification::class,
            \App\Application\Listeners\InitializeDefaultData::class,
        ],
        \App\Domain\Companies\Events\CompanyStatusChangedEvent::class => [
            \App\Application\Listeners\NotifyCompanyStatusChange::class,
        ],

        // Compliance Events
        \App\Domain\Compliance\Events\EvidenceUploadedEvent::class => [
            \App\Application\Listeners\NotifyEvidenceUploaded::class,
            \App\Application\Listeners\LogEvidenceUpload::class,
        ],
        \App\Domain\Compliance\Events\EvidenceVerifiedEvent::class => [
            \App\Application\Listeners\NotifyEvidenceVerification::class,
        ],
        \App\Domain\Compliance\Events\RequirementExpiringEvent::class => [
            \App\Application\Listeners\SendExpirationReminder::class,
        ],
        \App\Domain\Compliance\Events\RequirementExpiredEvent::class => [
            \App\Application\Listeners\NotifyRequirementExpired::class,
            \App\Application\Listeners\CreateComplianceAlert::class,
        ],

        // Audit Events
        \App\Domain\Compliance\Events\AuditCreatedEvent::class => [
            \App\Application\Listeners\NotifyAuditParticipants::class,
        ],
        \App\Domain\Compliance\Events\FindingCreatedEvent::class => [
            \App\Application\Listeners\NotifyFindingResponsible::class,
        ],
        \App\Domain\Compliance\Events\ActionPlanOverdueEvent::class => [
            \App\Application\Listeners\NotifyActionPlanOverdue::class,
        ],

        // User Events
        \App\Domain\Identity\Events\UserCreatedEvent::class => [
            \App\Application\Listeners\SendWelcomeEmail::class,
        ],
        \App\Domain\Identity\Events\UserLoginEvent::class => [
            \App\Application\Listeners\UpdateLastLogin::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
