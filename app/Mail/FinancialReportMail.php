<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FinancialReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $revenueTrend;
    public $revenueDistribution;

    public function __construct($revenueTrend, $revenueDistribution)
    {
        $this->revenueTrend = $revenueTrend;
        $this->revenueDistribution = $revenueDistribution;
    }

    public function build()
    {
        return $this->subject('Finansal Rapor')
            ->view('emails.financial-report')
            ->with([
                'revenueTrend' => $this->revenueTrend,
                'revenueDistribution' => $this->revenueDistribution
            ]);
    }
} 