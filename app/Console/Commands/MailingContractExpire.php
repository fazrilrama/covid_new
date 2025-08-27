<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ContractExpireNotification;
use App\Mail\ContractExpires as ContractExpiresMail;
use Mail;

class MailingContractExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:contract_expire_notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notifications = ContractExpireNotification::where('is_sent', 0)->get();
        if($notifications->count()) {
            foreach($notifications as $notification) {
                $data = json_decode($notification->payload);
                Mail::to($notification->email)->queue(new ContractExpiresMail($notification->name, $data));
                $notification->is_sent = 1;
                $notification->save();
                $this->comment('Send Notification To ' . $notification->email);
            }
        }
    }
}
