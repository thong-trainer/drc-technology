<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
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
                'role_name' => 'Super Admin',
                'description' => 'Full permission for technical',
                'is_hide' => 1,
            ],
			[
                'role_name' => 'Admin',
                'description' => 'Full permission for administrator',
                'is_hide' => 0,
            ],  
			[
                'role_name' => 'Manager',
                'description' => 'Manager role',
                'is_hide' => 0,
            ],
			[
                'role_name' => 'Seller',
                'description' => 'Sales person',
                'is_hide' => 0,
            ],                                    
        ];
 
        foreach ($items as $item)
            DB::table('roles')->insert($item);
    }
}
