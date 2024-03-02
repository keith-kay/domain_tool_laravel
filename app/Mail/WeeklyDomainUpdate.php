<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WeeklyDomainUpdate extends Mailable
{
    use Queueable, SerializesModels;

    public $totalDomains;
    public $activeDomains;
    public $expiredDomains;
    public $timestamp;

    /**
     * Create a new message instance.
     *
     * @param int $totalDomains
     * @param int $activeDomains
     * @param int $expiredDomains
     * @param string $timestamp
     * @return void
     */
    public function __construct($totalDomains, $activeDomains, $expiredDomains, $timestamp)
    {
        $this->totalDomains = $totalDomains;
        $this->activeDomains = $activeDomains;
        $this->expiredDomains = $expiredDomains;
        $this->timestamp = $timestamp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Weekly Domain Update')
                    ->view('emails.weekly_domain_update')
                    ->with([
                        'totalDomains' => $this->totalDomains,
                        'activeDomains' => $this->activeDomains,
                        'expiredDomains' => $this->expiredDomains,
                        'timestamp' => $this->timestamp,
                    ]);
    }
}