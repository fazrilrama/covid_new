<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InboundSPBM extends Mailable
{
    use Queueable, SerializesModels;

    protected $advanceNotice, $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($advanceNotice, $user)
    {
        $this->advanceNotice = $advanceNotice;
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   
        $pdf = \PDF::loadView('advance_notices.print_sptb', [
            'advanceNotice' => $this->advanceNotice,
            'from_job' => true,
            'job_user_id' => $this->user->id
            ]);
        $filename = 'aon/' .str_slug($this->advanceNotice->code)  . uniqid() . '.pdf';

        \Storage::disk('public')->put($filename, $pdf->stream());

        return $this->subject('Inbound SPBM')->view('notification_mails.InboundSPBM', [
            'advanceNotice' => $this->advanceNotice,
            'from_job' => true,
            'job_user_id' => $this->user->id
        ])->attach(\Storage::disk('public')->url($filename));
    }
}
