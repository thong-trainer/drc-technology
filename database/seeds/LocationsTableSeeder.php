<?php

use Illuminate\Database\Seeder;

class LocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$locations = [
            [
                'location_name' => 'WH - Default',
                'is_default' => 1,
                'created_by' => 1,
            ]
        ];
 
        foreach ($locations as $item)
            DB::table('locations')->insert($item);
    }
}
