<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\PasswordReset;

use Illuminate\Auth\Events\Failed;
use App\Listeners\LoginFailedListener;

use App\Events\AdvanceInboundCreateEvent;
use App\Events\AdvanceInboundEditEvent;
use App\Events\AdvanceOutboundCreateEvent;
use App\Events\AdvanceOutboundEditEvent;
use App\Events\DeliveryNoteCreateEvent;
use App\Events\DeliveryNoteEditEvent;
use App\Events\DeliveryPlanCreateEvent;
use App\Events\DeliveryPlanEditEvent;
use App\Events\GoodReceiveCreateEvent;
use App\Events\GoodReceiveEditEvent;
use App\Events\PickingCreateEvent;
use App\Events\PickingEditEvent;
use App\Events\PutawayCreateEvent;
use App\Events\PutawayEditEvent;
use App\Events\SendSMSEvent;

use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Listeners\AdvanceInboundCreateListener;
use App\Listeners\AdvanceInboundEditListener;
use App\Listeners\AdvanceOutboundCreateListener;
use App\Listeners\AdvanceOutboundEditListener;
use App\Listeners\DeliveryNoteCreateListener;
use App\Listeners\DeliveryNoteEditListener;
use App\Listeners\DeliveryPlanCreateListener;
use App\Listeners\DeliveryPlanEditListener;
use App\Listeners\GoodReceiveCreateListener;
use App\Listeners\GoodReceiveEditListener;
use App\Listeners\LoginSuccessfullListener;
use App\Listeners\PasswordResetListener;
use App\Listeners\PickingCreateListener;
use App\Listeners\PickingEditListener;
use App\Listeners\PutawayCreateListener;
use App\Listeners\PutawayEditListener;
use App\Listeners\SendSMSListener;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\ActivityLog;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoginSuccessfullListener::class,
        ],
        Failed::class => [
            LoginFailedListener::class,
        ],
        PasswordReset::class => [
            PasswordResetListener::class,
        ],
        SendSMSEvent::class => [
            SendSMSListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        //
    }
}
