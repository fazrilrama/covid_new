<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContractExpires extends Mailable
{
    use Queueable, SerializesModels;
    protected $name, $contract;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $contract)
    {
        $this->name = $name;
        $this->contract = $contract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('notification_mails.ContractExpires', [
            'name' => $this->name,
            'contract' => $this->contract
        ]);
    }
}
