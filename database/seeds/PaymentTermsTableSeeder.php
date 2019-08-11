<?php

use Illuminate\Database\Seeder;

class PaymentTermsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		$terms = [
            [
                'payment_term' => 'N/A',
                'number_of_days' => 0,
                'description' => '',
                'is_default' => 1,
                'created_by' => 1,
            ], 			
            [
                'payment_term' => 'Immediate Payment',
                'number_of_days' => 1,
                'description' => '',
                'created_by' => 1,
            ],
            [
                'payment_term' => '15 Days',
                'number_of_days' => 15,
                'description' => '',
                'created_by' => 1,
            ],
            [
                'payment_term' => '30 Days',
                'number_of_days' => 30,
                'description' => '',
                'created_by' => 1,
            ],            
        ];
 
        foreach ($terms as $item)
            DB::table('payment_terms')->insert($item);
    }
}
