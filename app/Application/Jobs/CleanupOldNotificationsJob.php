<?php

namespace App\Application\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanupOldNotificationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('maintenance');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Delete read notifications older than 90 days
        $cutoffDate = Carbon::now()->subDays(90);

        $deleted = DB::table('notifications')
            ->whereNotNull('read_at')
            ->where('read_at', '<', $cutoffDate)
            ->delete();

        // Keep unread notifications for 1 year
        $unreadCutoff = Carbon::now()->subYear();
        
        $deletedUnread = DB::table('notifications')
            ->whereNull('read_at')
            ->where('created_at', '<', $unreadCutoff)
            ->delete();

        // Optionally: Archive old activity logs
        // This would typically move to cold storage
    }
}
