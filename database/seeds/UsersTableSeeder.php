<?php
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
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
                'name' => 'Technical Supporter',
                'gender' => 'male',
                'telephone' => '(000) 000-000',
                'email' => 'technical@mail.com',
                'password' => Hash::make('technical@123'),
                'image_url' => 'users/user-placeholder.jpg',
                'role_id' => 1,
                'is_enable' => 0,
                'location_id' => 1,
            ],                                    
        ];
 
        foreach ($items as $item)
            DB::table('users')->insert($item);
    }
}
