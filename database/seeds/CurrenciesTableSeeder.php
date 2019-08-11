<?php

use Illuminate\Database\Seeder;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

    	DB::table('currencies')->delete();
    	DB::table('currency_exchange_rates')->delete();

 		$currencies = [
            [
                'currency' => 'USD',
                'symbol' => '$',
                'digit' => 2,
                'is_default' => 1,
                'created_by' => 1,
            ],
            [
                'currency' => 'KHR',
                'symbol' => '៛',
                'calculation' => 'multiplication',
                'digit' => 0,
                'is_default' => 0,
                'created_by' => 1,
            ],            
        ];    
 
        foreach ($currencies as $item)
        {
        	$id = DB::table('currencies')->insertGetId($item);

            if($item['is_default'] == 1) {
                $rate = [
                    'currency_id' => $id,
                    'rate' => 1,                 
                    'applied_date' => Carbon\Carbon::now(),
                    'created_by' => 1,
                ];
            } else {
                $rate = [
                    'currency_id' => $id,
                    'rate' => 4100,                
                    'applied_date' => Carbon\Carbon::now(),
                    'created_by' => 1,
                ];                
            }
           
            DB::table('currency_exchange_rates')->insert($rate);
        }
    }
}
