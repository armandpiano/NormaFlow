<?php

namespace App\Application\Listeners;

use App\Domain\Compliance\Events\EvidenceUploadedEvent;

class NotifyEvidenceUploaded
{
    public function handle(EvidenceUploadedEvent $event): void
    {
        // TODO: Notify compliance managers about new evidence
    }
}
