<?php

namespace App\Application\Jobs;

use App\Models\User;
use App\Notifications\WeeklyDigestNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendWeeklyDigestJob implements ShouldQueue
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
        $users = User::where('is_active', true)
            ->whereNotNull('email_verified_at')
            ->get();

        foreach ($users as $user) {
            $digestData = $this->prepareDigestForUser($user);

            // Only send if there's activity
            if ($this->hasActivity($digestData)) {
                Notification::send($user, new WeeklyDigestNotification($digestData));
            }
        }
    }

    /**
     * Prepare digest data for a specific user.
     */
    private function prepareDigestForUser(User $user): array
    {
        return [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'period' => [
                'start' => Carbon::now()->subWeek()->startOfWeek()->toDateString(),
                'end' => Carbon::now()->subWeek()->endOfWeek()->toDateString(),
            ],
            'activities' => [
                'pending_approvals' => $this->getPendingApprovals($user),
                'upcoming_audits' => $this->getUpcomingAudits($user),
                'expiring_items' => $this->getExpiringItems($user),
                'action_items' => $this->getActionItems($user),
            ],
            'stats' => [
                'evidences_uploaded' => 0,
                'audits_completed' => 0,
                'findings_resolved' => 0,
            ],
        ];
    }

    private function getPendingApprovals(User $user): int
    {
        // TODO: Implement based on user's role and company
        return 0;
    }

    private function getUpcomingAudits(User $user): array
    {
        // TODO: Implement
        return [];
    }

    private function getExpiringItems(User $user): array
    {
        // TODO: Implement
        return [];
    }

    private function getActionItems(User $user): array
    {
        // TODO: Implement
        return [];
    }

    private function hasActivity(array $digestData): bool
    {
        return $digestData['activities']['pending_approvals'] > 0
            || $digestData['activities']['upcoming_audits'] > 0
            || $digestData['activities']['expiring_items'] > 0
            || $digestData['activities']['action_items'] > 0
            || $digestData['stats']['evidences_uploaded'] > 0;
    }
}
