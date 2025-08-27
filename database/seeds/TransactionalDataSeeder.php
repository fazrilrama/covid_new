<?php

use Illuminate\Database\Seeder;

class TransactionalDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_advance_notices')->insert([
                'code' => 'AIN_'.$i,
                'type' => 'inbound',
                'advance_notice_activity_id' => '2',
                'etd' => '2018-10-31',
                'eta' => '2018-11-04',
                'origin_id' => rand(3271,3279),
                'destination_id' => rand(3371,3376),
                'ref_code' => 'os/asd/asd/asd',
                'transport_type_id' => '1',
                'shipper_id' => '1',
                'shipper_address' => 'alamat',
                'consignee_id' => '2',
                'consignee_address' => 'alamat',
                'user_id' => rand(1,8),
                'project_id'=>rand(1,11)
            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_advance_notices')->insert([
                'code' => 'AON_'.$i,
                'type' => 'outbound',
                'advance_notice_activity_id' => '1',
                'etd' => '2018-10-31',
                'eta' => '2018-11-04',
                'origin_id' => rand(3301,3329),
                'destination_id' => rand(1701,1709),
                'ref_code' => 'os/asd/asd/asd',
                'transport_type_id' => '1',
                'shipper_id' => '1',
                'shipper_address' => 'alamat',
                'consignee_id' => '2',
                'consignee_address' => 'alamat',
                'user_id' => rand(1,8),
                'project_id'=>rand(1,11)

            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_transports')->insert([
                'code' => 'GR_'.$i,
                'type' => 'inbound',
                'advance_notice_id' => rand(1,20),
                'etd' => '2018-10-31',
                'eta' => '2018-11-04',
                'origin_id' => rand(3271,3279),
                'destination_id' => rand(3371,3376),
                'ref_code' => 'os/asd/asd/asd',
                'transport_type_id' => '1',
                'vehicle_code_num' => 'code',
                'vehicle_plate_num' => 'plate',
                'shipper_id' => '1',
                'shipper_address' => 'alamat',
                'consignee_id' => '2',
                'consignee_address' => 'alamat',
                'user_id' => rand(1,8),
                'project_id'=>rand(1,11)
            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_transports')->insert([
                'code' => 'DP_'.$i,
                'type' => 'outbound',
                'advance_notice_id' => rand(1,20),
                'etd' => '2018-10-31',
                'eta' => '2018-11-04',
                'origin_id' => rand(3271,3279),
                'destination_id' => rand(3371,3376),
                'ref_code' => 'os/asd/asd/asd',
                'transport_type_id' => '1',
                'vehicle_code_num' => 'code',
                'vehicle_plate_num' => 'plate',
                'shipper_id' => '1',
                'shipper_address' => 'alamat',
                'consignee_id' => '2',
                'consignee_address' => 'alamat',
                'user_id' => rand(1,8),
                'project_id' => rand(1,11)
            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_entries')->insert([
                'stock_transport_id' => '8',
                'type' => 'inbound',
                'code' => 'PA_'.$i,
                'total_pallet' => '100',
                'total_labor' => '35',
                'total_forklift' => '40',
                'forklift_start_time' => '2018-10-09 13:32',
                'forklift_end_time' => '2018-10-09 13:32',
                'ref_code' => 'os/asd/asd/asd',
                'user_id' => rand(1,8),
                'warehouse_id' => rand(1,11),
                'project_id' => rand(1,11)
            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_entries')->insert([
                'stock_transport_id' => '8',
                'type' => 'outbound',
                'code' => 'PP_'.$i,
                'total_pallet' => '100',
                'total_labor' => '35',
                'total_forklift' => '40',
                'forklift_start_time' => '2018-10-09 13:32',
                'forklift_end_time' => '2018-10-09 13:32',
                'ref_code' => 'os/asd/asd/asd',
                'user_id' => rand(1,8),
                'warehouse_id' => rand(1,11),
                'project_id' => rand(1,11)
            ]);
        }
        for ($i=1; $i < 100; $i++) { 
            DB::table('stock_entry_details')->insert([
                'stock_entry_id' => rand(1,40),
                'item_id' => rand(1,20),
                'storage_id' => rand(1,30),
                'uom_id' => rand(1,11),
                'control_date' => '2018-10-30',
                'volume' => rand(100,500),
                'weight' => rand(10,100),
                'qty' => rand(1,40)
            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('stock_deliveries')->insert([
                'code' => 'DN_'.$i,
                'type' => 'outbound',
                'stock_entry_id' => '22',
                'etd' => '2018-10-31',
                'eta' => '2018-11-04',
                'origin_id' => rand(3271,3279),
                'destination_id' => rand(3371,3376),
                'ref_code' => 'os/asd/asd/asd',
                'transport_type_id' => '2',
                'vehicle_code_num' => 'code',
                'vehicle_plate_num' => 'plate',
                'shipper_id' => '1',
                'shipper_address' => 'alamat',
                'consignee_id' => '2',
                'consignee_address' => 'alamat',
                'employee_id' => rand(1,11),
                'user_id' => rand(1,8),
                'total_collie' => 400,
                'total_weight' => 20,
                'total_volume' => 300,
                'project_id' => rand(1,11)
            ]);
        }
        for ($i=1; $i < 21; $i++) { 
            DB::table('projects')->insert([
                'name' => 'Project '.$i,
                'company_id' => rand(1,11)
            ]);
        }
         for ($i=1; $i < 21; $i++) { 
            DB::table('assigned_to')->insert([
                'user_id' => rand(1,11),
                'project_id' => rand(1,11)
            ]);
        }
         for ($i=1; $i < 21; $i++) { 
            DB::table('stock_allocations')->insert([
                'storage_id' => rand(1,11),
                'item_id'=>rand(1,11),
                'project_id'=>rand(1,11),
                'volume'=>rand(1,11),
                'weight' =>rand(1,11),
                'control_date'=>'2018-10-0'.$i.' 13:32'
            ]);
        }

         $this->command->info('Creating Permission to companies');
        for ($i=1; $i < 12; $i++) { 
            DB::table('companies')->insert([
                'code' => 'company_'.$i,
                'name' => 'Perusahaan '.$i,
                'company_type_id' => 1,
                'city_id' => rand(1301,1312),
            ]);
        }

         $this->command->info('Creating Permission to warehouses');
        for ($i=1; $i < 12; $i++) { 
            DB::table('warehouses')->insert([
                'name' => 'Gudang '.$i,
                'code' => 'WH_'.$i,
                'is_active' => 1,
                'city_id' => rand(1301,1312),
                'company_id' => rand(1,11),
                'region_id' => rand(31,36),
                'ownership' => 'milik',
            ]);
        }

         $this->command->info('Creating Permission to storages');
        for ($i=1; $i < 41; $i++) { 
            DB::table('storages')->insert([
                'code' => 'STRG_'.$i,
                'warehouse_id' => rand(1,11),
                'commodity_id' => rand(1,11),
                'is_active' => 1,
            ]);
        }

         $this->command->info('Creating Permission to items');
        for ($i=1; $i < 31; $i++) { 
            DB::table('items')->insert([
                'sku' => 'item_'.$i,
                'name' => 'Barang '.$i,
                'default_uom_id' => rand(1,21),
                'control_method_id' => rand(1,3),
                'commodity_id' => rand(1,11),
                'company_id' => rand(1,11),
            ]);
        }
         $this->command->info('Creating Permission to parties');
        for ($i=1; $i < 12; $i++) { 
            DB::table('parties')->insert([
                'name' => 'Pihak '.$i,
                'address' => 'Alamat '.$i,
                'city_id' => rand(1301,1312),
            ]);
        }
        
         $this->command->info('Creating Parties Party types');
        for ($i=1; $i < 22; $i++) { 
            DB::table('parties_party_types')->insert([
                'party_id' => rand(1,11),
                'party_type_id' => rand(1,2),
            ]);
        }

    }
}
