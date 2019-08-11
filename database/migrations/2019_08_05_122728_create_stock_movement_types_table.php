<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockMovementTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_movement_types', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('movement_type');
            $table->string('label');
            $table->smallInteger('value')->default(0);
            $table->mediumText('description')->nullable();
            $table->boolean('is_enable')->default(1);
            $table->boolean('is_delete')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_movement_types');
    }
}
