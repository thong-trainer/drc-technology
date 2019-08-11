<?php

use Illuminate\Database\Seeder;

class CustomerGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		$groups = [
            [
                'group_name' => 'General',
                'description' => 'for walk-in customers',
                'is_default' => 1,
                'is_enable' => 0,
                'created_by' => 1,
            ],
            [
                'group_name' => 'Wholesale',
                'description' => 'for wholesale customers',
                'is_default' => 0,
                'created_by' => 1,
            ]
        ];
 
        foreach ($groups as $item)
            DB::table('customer_groups')->insert($item);
    }
}
