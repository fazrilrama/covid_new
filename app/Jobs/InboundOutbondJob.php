<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\InboundSPBM as InboundSPBMMail;
use App\Mail\OutboundSPBK as InboundSPBKMail;
use Illuminate\Support\Facades\Mail;

class InboundOutbondJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $advanceNotice, $u, $logged_user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($advanceNotice, $u, $logged_user = null)
    {
        $this->advanceNotice = $advanceNotice;
        $this->u = $u;
        $this->logged_user = $logged_user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->advanceNotice->type == 'inbound') {
            Mail::to($this->u)->send(new InboundSPBMMail($this->advanceNotice, $this->logged_user));
        } else {
            Mail::to($this->u)->send(new InboundSPBKMail($this->advanceNotice, $this->logged_user));
        }
    }
}
