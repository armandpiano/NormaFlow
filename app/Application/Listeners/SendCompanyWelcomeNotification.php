<?php

namespace App\Application\Listeners;

use App\Domain\Companies\Events\CompanyCreatedEvent;

class SendCompanyWelcomeNotification
{
    public function handle(CompanyCreatedEvent $event): void
    {
        // TODO: Send welcome email to company admin
        // Mail::to($event->companyEmail)->send(new CompanyWelcomeMail($event->companyId));
    }
}
