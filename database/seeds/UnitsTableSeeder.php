<?php

use Illuminate\Database\Seeder;

class UnitsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		$units = [
            [
                'unit_name' => 'Unit',
            ],

        ];
 
        foreach ($units as $item)
            DB::table('units')->insert($item);
    }
}
