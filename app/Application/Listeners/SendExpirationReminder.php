<?php

namespace App\Application\Listeners;

use App\Domain\Compliance\Events\RequirementExpiringEvent;

class SendExpirationReminder
{
    public function handle(RequirementExpiringEvent $event): void
    {
        // TODO: Send reminder notifications
    }
}
