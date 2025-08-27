<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon;
use App\LastLogger;

class DeleteOldUserLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:old_user_login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        LastLogger::where('created_at', '<', Carbon::now()->subWeek())->each(function ($item) {
            $item->delete();
        });
    }
}
