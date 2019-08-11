<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->delete();

 		$settings = [
            // [
            //     'section' => 'company',
            //     'label' => 'company_info',
            //     'display_name' => 'Company Name',
            //     'input_value' => 'SMTech Solution',
            //     'data_type' => 'string',
            //     'notes' => 'logo-placeholder.png',
            //     'description' => 'A short description of your company',
            // ],
            [
                'section' => 'company',
                'label' => 'multiple_locations',
                'display_name' => 'Multiple Storage Locations',
                'input_value' => '1',
                'data_type' => 'bool',
            ],            
            [
                'section' => 'sale',
                'label' => 'multiple_currencies',
                'display_name' => 'Multiple Currencies',
                'input_value' => '1',
                'data_type' => 'bool',
            ],     
            [
                'section' => 'sale',
                'label' => 'delivery_methods',
                'display_name' => 'Delivery Methods',
                'input_value' => '1',
                'data_type' => 'bool',
            ],                    
            [
                'section' => 'product',
                'label' => 'variant_and_dimension',
                'display_name' => 'Variants and Dimensions',
                'input_value' => '1',
                'data_type' => 'bool',
            ],
            [
                'section' => 'product',
                'label' => 'price_list',
                'display_name' => 'Product Price List',
                'input_value' => '1',
                'data_type' => 'bool',
            ],
            [
                'section' => 'product',
                'label' => 'stock',
                'display_name' => 'Stock',
                'input_value' => '1',
                'data_type' => 'bool',
            ], 

        ];
 
        foreach ($settings as $item)
            DB::table('settings')->insert($item);
    }
}
