<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('product_type')->nullable(); // Service or Storable            
            $table->string('barcode')->unique();
            $table->string('ref_number')->nullable();
            $table->string('product_name')->unique()->index();   
            $table->double('customer_tax', 8, 2)->default(0);
            // $table->double('sale_price', 8, 2)->default(0);
            $table->double('cost', 8, 2)->default(0);
            $table->smallInteger('default_qty')->default(1);
            $table->string('tags')->nullable();
            $table->mediumText('notes')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->mediumText('image_url')->nullable();
            $table->smallInteger('dimension_group_id');
            $table->smallInteger('category_id');
            $table->smallInteger('sale_unit_id');
            $table->smallInteger('purchase_unit_id');
            $table->boolean('is_pos')->default(0);
            $table->boolean('is_release')->default(0);
            $table->boolean('is_delete')->default(0);                         
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();            
        });

        DB::update("ALTER TABLE products AUTO_INCREMENT = 1001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

