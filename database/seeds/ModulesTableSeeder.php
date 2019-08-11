<?php

use Illuminate\Database\Seeder;

class ModulesTableSeeder extends Seeder
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
                'module_name' => 'user',
                'description' => 'User Management',
            ],
            [
                'module_name' => 'role',
                'description' => 'Role Management',
            ],    
            [
                'module_name' => 'customer',
                'description' => 'Customer Management',
            ],
            [
                'module_name' => 'customer_group',
                'description' => 'Customer Group Management',
            ],  
            [
                'module_name' => 'category',
                'description' => 'Category Management',
            ],
            [
                'module_name' => 'supplier',
                'description' => 'Supplier Management',
            ],    
            [
                'module_name' => 'company',
                'description' => 'Company Management',
            ],
            [
                'module_name' => 'dimension',
                'description' => 'Dmension Management',
            ],            
            [
                'module_name' => 'product',
                'description' => 'Product Management',
            ],    
            [
                'module_name' => 'product_price',
                'description' => 'Product Price Management',
            ],
            [
                'module_name' => 'stock',
                'description' => 'Stock Management',
            ],  
            [
                'module_name' => 'sale',
                'description' => 'Sale Management',
            ],
            [
                'module_name' => 'quotation',
                'description' => 'Quotation Management',
            ],    
            [
                'module_name' => 'invoice',
                'description' => 'Invoice Management',
            ],
            [
                'module_name' => 'setting',
                'description' => 'Setting Management',
            ],                                        
        ];
 
        foreach ($items as $item) {

            // $id = DB::table('modules')->insert($item);
            $id = DB::table('modules')->insertGetId($item);
	 		$permissions = [
	            'role_id' => 1,
	            'module_id' => $id,
	            'is_view' => 1,
	            'is_create' => 1,
	            'is_edit' => 1,
	            'is_delete' => 1,
	            'is_export' => 1,
	            'is_import' => 1,	                	                          
	        ];  
            DB::table('permissions')->insert($permissions);
        }
    }
}
