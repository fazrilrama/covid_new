<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ActivityLog;

class SendSMSListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $username = env('SMS_GATEWAY_USERNAME', 'birin243jkt');
        $password = env('SMS_GATEWAY_PASSWORD', 'gosms9283');
        $message  = str_replace(" ","%20", $event->message);
        $mobile = $event->mobileNumber;
        $auth = md5($username.$password.$mobile);

        $smsCommand = "http://api.gosmsgateway.com:88/api/sendSMS.php?mobile=$mobile&message=$message&username=$username&auth=$auth";
        $sendCode = file_get_contents($smsCommand);
    }
}
