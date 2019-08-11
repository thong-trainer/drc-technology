<?php

use Illuminate\Database\Seeder;

class DeliveryMethodsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		$items = [
            [
                'delivery_method' => 'N/A',
                'fixed_price' => 0,   
                'created_by' => 1,
            ],
            [
                'delivery_method' => 'Motobike',
                'fixed_price' => 0,   
                'created_by' => 1,
            ],
            [
                'delivery_method' => 'Car',
                'fixed_price' => 0,   
                'created_by' => 1,
            ]                     
        ];
 
        foreach ($items as $item)
            DB::table('delivery_methods')->insert($item);
    }
}
