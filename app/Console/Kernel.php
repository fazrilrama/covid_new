<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('generate:contract_expires')->everyMinute();
        $schedule->command('generate:email_for_contract_expires')->everyMinute();
        $schedule->command('emails:contract_expire_notifications')->everyMinute();
        $schedule->command('delete:old_user_login')->everyMinute();
        $schedule->command('delete:old_activity_log')->everyMinute();
        $schedule->command('delete:old_data_log')->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
