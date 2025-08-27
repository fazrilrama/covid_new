<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Contract;
use App\ContractExpire;

class ContractExpires extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:contract_expires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification Expired Contract';

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
        $contract_expires = "
            SELECT
            parties.id as party_id, 
            parties.name as party_name,
            contracts.id as contract_id,
            contracts.start_date,
            contracts.end_date
            FROM contracts
            LEFT JOIN contract_warehouse ON contracts.id = contract_warehouse.contract_id
            LEFT JOIN warehouses ON warehouses.id = contract_warehouse.warehouse_id
            LEFT JOIN parties ON parties.id = warehouses.branch_id
            WHERE
            parties.id IS NOT NULL
            AND
            contracts.end_date BETWEEN NOW() AND DATE_SUB(NOW(), INTERVAL -30 DAY)
            GROUP BY party_id
        ";

        $res = \DB::select($contract_expires);

        foreach($res as $row) {
            $check = ContractExpire::where('party_id', $row->party_id)->where('contract_id', $row->contract_id)->count();
            if(!$check) {
                $ce = new ContractExpire;
                $ce->party_id = $row->party_id;
                $ce->contract_id = $row->contract_id;
                $ce->is_generated = 0;
                $ce->created_at = now();
                $ce->updated_at = now();
                $ce->save();
            }
        }
    }
}
