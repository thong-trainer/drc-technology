<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesTableSeeder::class);
        $this->call(LocationsTableSeeder::class);        
        $this->call(UsersTableSeeder::class);        
        $this->call(ModulesTableSeeder::class);    
        $this->call(UnitsTableSeeder::class);          
        $this->call(SettingsTableSeeder::class);
        $this->call(CompaniesTableSeeder::class);
        $this->call(CurrenciesTableSeeder::class);
        $this->call(CustomerGroupsTableSeeder::class);
        $this->call(PaymentTermsTableSeeder::class);
        $this->call(DeliveryMethodsTableSeeder::class);
        $this->call(StockMovementTypesTableSeeder::class);

        

    }
}
