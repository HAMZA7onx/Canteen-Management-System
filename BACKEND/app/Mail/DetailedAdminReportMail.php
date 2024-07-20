<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DetailedAdminReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $records;
    public $frequency;
    public $dateRange;

    public function __construct($records, $frequency, $dateRange)
    {
        $this->records = $records;
        $this->frequency = $frequency;
        $this->dateRange = $dateRange;
    }

    public function build()
    {
        return $this->view('emails.detailed-admin-report')
            ->subject("{$this->frequency} Detailed Record Report");
    }
}
