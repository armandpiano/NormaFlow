<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Inspiring quotes
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Scheduled tasks
Schedule::job(new \App\Application\Jobs\CheckExpiringRequirementsJob)->daily();
Schedule::job(new \App\Application\Jobs\GenerateComplianceReportsJob)->weekly();
Schedule::job(new \App\Application\Jobs\CleanupOldNotificationsJob)->daily();
Schedule::job(new \App\Application\Jobs\SendWeeklyDigestJob)->weeklyOn(1, '09:00');
