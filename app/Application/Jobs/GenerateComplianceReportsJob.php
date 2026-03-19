<?php

namespace App\Application\Jobs;

use App\Models\Company;
use App\Notifications\WeeklyComplianceReportNotification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;

class GenerateComplianceReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->onQueue('reports');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Generating weekly compliance reports...');

        $companies = Company::where('status', 'active')
            ->with(['sites', 'users' => fn($q) => $q->where('role', 'company_admin')])
            ->get();

        foreach ($companies as $company) {
            try {
                $reportData = $this->generateCompanyReport($company);
                
                // Send to all company admins
                foreach ($company->users as $admin) {
                    Notification::send(
                        $admin,
                        new WeeklyComplianceReportNotification($reportData)
                    );
                }

                Log::info("Report generated for company: {$company->name}");
            } catch (\Exception $e) {
                Log::error("Failed to generate report for company {$company->id}: " . $e->getMessage());
            }
        }

        Log::info('Weekly compliance reports completed.');
    }

    /**
     * Generate compliance report data for a company.
     */
    private function generateCompanyReport(Company $company): array
    {
        $startDate = Carbon::now()->subWeek();
        $endDate = Carbon::now();

        return [
            'company_id' => $company->id,
            'company_name' => $company->name,
            'period_start' => $startDate->toDateString(),
            'period_end' => $endDate->toDateString(),
            'generated_at' => Carbon::now()->toIso8601String(),
            'summary' => [
                'total_sites' => $company->sites->count(),
                'active_requirements' => 0, // TODO: Calculate
                'compliant_requirements' => 0,
                'pending_evidences' => 0,
                'open_findings' => 0,
                'overdue_actions' => 0,
            ],
            'compliance_rate' => 0, // TODO: Calculate
            'trends' => [
                'improvements' => [],
                'declines' => [],
                'new_issues' => [],
            ],
        ];
    }
}
