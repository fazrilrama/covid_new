<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class RegisterCustomerAdminAccount extends Mailable
{
    use Queueable, SerializesModels;
    private $userId;
    private $username;
    private $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userId, $username, $password)
    {
        $this->userId = $userId;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $userId = $this->userId;
        $username = $this->username;
        $password = $this->password;
        return $this->view('notification_mails.RegisterCustomerAdminAccount', compact('userId', 'username', 'password'));
    }
}
