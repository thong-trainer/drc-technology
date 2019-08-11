<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('stock_movements');
        //
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_code');
            $table->string('source_document')->nullable(); // PO00012 or SO10002
            $table->smallInteger('movement_type_id');
            $table->datetime('movement_date');
            $table->bigInteger('contact_id');
            $table->string('remark')->nullable();
            $table->smallInteger('location_id');
            $table->string('status');            
            $table->mediumText('notes')->nullable();
            $table->mediumText('description')->nullable();
            $table->boolean('is_delete')->default(0);                        
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();             
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movements');
    }
}
