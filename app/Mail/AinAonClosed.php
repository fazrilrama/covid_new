<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AinAonClosed extends Mailable
{
    use Queueable, SerializesModels;
    protected $result;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($result)
    {
        $this->result = $result;
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'AIN Closed.';
        if($this->result->type == 'outbound') {
            $subject = 'AON Closed.';
        }

        return $this->view('notification_mails.AinAonClosed', [
            'result' => $this->result
        ])->subject($subject);
    }
}
