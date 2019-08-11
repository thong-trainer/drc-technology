<?php

use Illuminate\Database\Seeder;

class CompaniesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
 		$companies = [
            [
                'company_name' => 'Your Company',
                'type' => '',
                'telephone' => '(000) 000-000',
                'email' => 'info@your-company.com',
                'website' => 'www.your-company.com',
                'image_url' => 'logo-placeholder.png',
                'is_enable' => 0,
                'created_by' => 1,
            ],

        ];
 
        foreach ($companies as $item)
            DB::table('companies')->insert($item);
    }
}
