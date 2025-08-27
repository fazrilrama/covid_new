<?php

use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->command->info('Creating Permission to uoms');
        $uoms = [
            'kilogram',
            'gram',
            'miligram',
            'ton',
            'mililiter',
            'liter',
            'meter kubik',
            'galon',
            'ons',
            'meter persegi',
            'kilometer persegi',
            'hektare'
        ];
        foreach ($uoms as $key => $value) {
            DB::table('uoms')->insert([
                'name' => $value,
            ]);
        }
        
        // $this->command->info('Creating Permission to uom conversion');
        // for ($i=1; $i < 16; $i++) { 
        //     DB::table('uom_conversions')->insert([
        //         'from_uom_id' => $i,
        //         'to_uom_id' => $i+1,
        //         'multiplier' => 100
        //     ]);
        // }

        DB::table('transport_types')->insert([
            'name' => 'udara',
        ]);
        DB::table('transport_types')->insert([
            'name' => 'laut',
        ]);
        DB::table('transport_types')->insert([
            'name' => 'darat',
        ]);

        DB::table('stock_advance_notice_activities')->insert([
            'name' => 'lokal',
        ]);
        DB::table('stock_advance_notice_activities')->insert([
            'name' => 'import',
        ]);

         $this->command->info('Creating Permission to party types');
        DB::table('party_types')->insert([
            'name' => 'shipper',
        ]);
        DB::table('party_types')->insert([
            'name' => 'consignee',
        ]);

         $this->command->info('Creating Permission to control_methods');
        DB::table('control_methods')->insert([
            'name' => 'FIFO',
        ]);
        DB::table('control_methods')->insert([
            'name' => 'LIFO',
        ]);
        DB::table('control_methods')->insert([
            'name' => 'FEFO',
        ]);

         $this->command->info('Creating Permission to company types');

        $company_types = [
            'Jasa',
            'Trading',
            'Pabrik'
        ];
        foreach ($company_types as $id => $value) {
            DB::table('company_types')->insert([
                'name' => $value
            ]);
        }

         $this->command->info('Creating Permission to comodity');
         $commodities = [
            'Makanan',
            'Minuman',
            'Tembakau',
            'Tekstil',
            'Pakaian jadi',
            'Kulit',
            'Kayu',
            'Kertas',
            'Penerbitan',
            'Batu bara',
            'Minyak bumi',
            'Gas bumi',
            'Kimia',
            'Karet',
            'Logam',
            'Mesin',
            'Furnitur'
        ];
        foreach ($commodities as $id => $value) {
            DB::table('commodities')->insert([
                'name' => $value,
                'code' => strtolower($value),
            ]);
        }

    }
}
