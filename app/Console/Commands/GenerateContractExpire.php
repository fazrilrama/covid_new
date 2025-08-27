<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ContractExpire;
use App\User;

class GenerateContractExpire extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:email_for_contract_expires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Email For Contract Expires';

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
        $contract_expires = ContractExpire::with('contract')->where('is_generated', 0)->get();

        $raw = [];
        if($contract_expires->count()) {
            foreach($contract_expires as $ce) {
                if($ce->party->user->count()) {
                    foreach($ce->party->user as $u) {
                        if($u->hasRole(['WarehouseManager', 'CargoOwner'])) {
                            $raw[] = [
                                'email' => $u->email,
                                'name' => $u->full_name,
                                'payload' => $ce->contract,
                                'contract_expire_id' => $ce->id
                            ];
                        }
                    }
                }

                $admin_bgrs = User::whereHas('roles', function($q) {
                    $q->where('name', 'Admin-BGR');
                })->get();

                foreach($admin_bgrs as $admin) {
                    $raw[] = [
                        'email' => $admin->email,
                        'name' => $admin->full_name,
                        'payload' => $ce->contract,
                        'contract_expire_id' => $ce->id
                    ];
                }
                
                $ce->is_generated = 1;
                $ce->save();
            }
        }

        \DB::table('contract_expire_notifications')->insert($raw);
    }
}
