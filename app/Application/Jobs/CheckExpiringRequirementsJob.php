<?php

namespace App\Application\Jobs;

use App\Models\Evidence;
use App\Models\NormativeMatrixRequirement;
use App\Notifications\EvidenceExpiringNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class CheckExpiringRequirementsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('notifications');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Check for evidences expiring in 30, 15, 7 days
        $daysToCheck = [30, 15, 7];

        foreach ($daysToCheck as $days) {
            $expiringDate = Carbon::now()->addDays($days)->startOfDay();
            $endOfDay = Carbon::now()->addDays($days)->endOfDay();

            $evidences = Evidence::where('status', 'approved')
                ->whereNotNull('valid_until')
                ->whereBetween('valid_until', [$expiringDate, $endOfDay])
                ->with(['user', 'requirement'])
                ->get();

            foreach ($evidences as $evidence) {
                // Get site manager or company admin to notify
                $users = $evidence->site
                    ?$evidence->site->company->users()
                        ->whereIn('role', ['company_admin', 'site_manager'])
                        ->get()
                    : collect();

                // Also notify the user who uploaded
                $users->push($evidence->user);

                Notification::send(
                    $users->unique(),
                    new EvidenceExpiringNotification($evidence, $days)
                );
            }
        }

        // Check for requirements expiring soon
        $matrixRequirements = NormativeMatrixRequirement::where('status', 'compliant')
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [Carbon::now()->startOfDay(), Carbon::now()->addDays(30)->endOfDay()])
            ->with(['site.company', 'requirement'])
            ->get();

        foreach ($matrixRequirements as $matrixReq) {
            $users = $matrixReq->site
                ?$matrixReq->site->company->users()
                    ->whereIn('role', ['company_admin', 'site_manager'])
                    ->get()
                : collect();

            Notification::send(
                $users,
                new RequirementExpiringNotification($matrixReq)
            );
        }
    }
}
