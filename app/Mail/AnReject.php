<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AnReject extends Mailable
{
    use Queueable, SerializesModels;
    protected $advanceNotice;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advanceNotice)
    {
        $this->advanceNotice = $advanceNotice;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = "transaksi no.".$this->advanceNotice->code." ditolak";

        return $this->view('notification_mails.AnReject', [
            'advanceNotice' => $this->advanceNotice
        ])->subject($subject);
    }
}
