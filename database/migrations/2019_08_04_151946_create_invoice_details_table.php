<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('invoice_details');
        //
        Schema::create('invoice_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('invoice_id');
            $table->bigInteger('product_id');
            $table->string('product_name');
            $table->string('variant_ids')->nullable();
            $table->double('unit_price', 8, 2)->default(0);            
            $table->smallInteger('qty')->default(1);
            $table->smallInteger('tax')->default(0);
            $table->double('pay_tax', 8, 2)->default(0);
            $table->smallInteger('discount')->default(0);
            $table->double('discount_amount', 8, 2)->default(0);
            $table->double('subtotal', 8, 2)->default(0);
            $table->mediumText('notes')->nullable();
            $table->boolean('is_delete')->default(0); 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_details');
    }
}
