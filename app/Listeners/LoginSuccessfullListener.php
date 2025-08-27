<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\WarehouseOfficer;
use App\ActivityLog;
use App\Events\SendSMSEvent;
use App\Project;

class LoginSuccessfullListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        // Set project sessions
        session(['projects'=>$event->user->projects]);
        session(['current_project'=>$event->user->projects->first()]);

        if($event->user->hasRole('WarehouseSupervisor') || $event->user->hasRole('WarehouseOfficer')){
            $warehouse_user = WarehouseOfficer::where('user_id', $event->user->id)->first();

            if(isset($warehouse_user)) {
                if($warehouse_user->warehouse) {
                    session(['warehouse_name'=> $warehouse_user->warehouse->name]);
                    session(['warehouse_id'=> $warehouse_user->warehouse->id]);
                } else {
                    session(['warehouse_name'=> 'Belum Memiliki Gudang']);
                }

            }

        }
        

        // ActivityLog::create([
        //     'description' => 'Login'
        // ]);
        // event(new SendSMSEvent($event->user->mobile_number, 'Login'));
    }
}
