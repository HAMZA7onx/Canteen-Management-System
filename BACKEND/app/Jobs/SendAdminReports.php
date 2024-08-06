<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\AdminReportSubscription;
use App\Mail\DetailedAdminReportMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\RecordsDashboardController;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendAdminReports implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $frequency;

    public function __construct($frequency)
    {
        $this->frequency = $frequency;
    }

    public function handle(RecordsDashboardController $recordsController)
    {
        try {
            Log::info("SendAdminReports job started for frequency: {$this->frequency}");

            $subscriptions = AdminReportSubscription::where('frequency', $this->frequency)
                ->where('is_active', true)
                ->get();

            Log::info("Found " . $subscriptions->count() . " active subscriptions for {$this->frequency} reports");

            foreach ($subscriptions as $subscription) {
                try {
                    $dateRange = $this->getDateRange();
                    Log::info("Date range: " . json_encode($dateRange));

                    $records = $this->getRecords($recordsController, $dateRange);
                    Log::info("Records fetched successfully");

                    Log::info("Sending {$this->frequency} report to: {$subscription->admin->email}");
                    Mail::to($subscription->admin->email)
                        ->send(new DetailedAdminReportMail($records, $this->frequency, $dateRange));
                    Log::info("Email sent successfully");
                } catch (\Exception $e) {
                    Log::error("Error processing subscription: " . $e->getMessage());
                }
            }

            Log::info("SendAdminReports job completed for frequency: {$this->frequency}");
        } catch (\Exception $e) {
            Log::error("SendAdminReports job failed: " . $e->getMessage());
            throw $e;
        }
    }

    private function getDateRange()
    {
        $end = Carbon::now();
        $start = $end->copy();

        switch ($this->frequency) {
            case 'daily':
                $start->subDay();
                break;
            case 'weekly':
                $start->subWeek();
                break;
            case 'monthly':
                $start->subMonth()->startOfMonth();
                $end = $end->subMonth()->endOfMonth();
                break;
            case 'yearly':
                $start->startOfYear();
                $end->subDay(); // Yesterday, as we're likely running this at the start of a new year
                break;
        }

        return [
            'start' => $start->startOfDay(),
            'end' => $end->endOfDay(),
        ];
    }

    private function getRecords($recordsController, $dateRange)
    {
        $request = new \Illuminate\Http\Request([
            'start_date' => $dateRange['start']->toDateString(),
            'end_date' => $dateRange['end']->toDateString(),
        ]);

        return $recordsController->getAdvancedRecords($request)->getData();
    }
}
