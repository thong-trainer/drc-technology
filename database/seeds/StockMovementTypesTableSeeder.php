<?php

use Illuminate\Database\Seeder;

class StockMovementTypesTableSeeder extends Seeder
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
                'movement_type' => 'Purchase Order',
                'label' => config('global.stock_status.stock_in'),
            ],
            [
                'movement_type' => 'Customer Return',
                'label' => config('global.stock_status.stock_in'),
            ],            
            [
                'movement_type' => 'Return to Supplier',
                'label' => config('global.stock_status.stock_out'),
            ],            
            [
                'movement_type' => 'Sale Order',
                'label' => config('global.stock_status.stock_out'),
                'is_enable' => 0,
            ],
        ];
 
        foreach ($items as $item)
            DB::table('stock_movement_types')->insert($item);
    } 

}
