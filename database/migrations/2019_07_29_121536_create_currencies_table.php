<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('currencies');
        //
        Schema::create('currencies', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('currency');
            $table->string('symbol');
            $table->string('calculation')->nullable();
            $table->tinyInteger('digit')->default(2);
            $table->boolean('is_enable')->default(1);
            $table->boolean('is_default')->default(0);
            $table->mediumText('description')->nullable();
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
        Schema::dropIfExists('currencies');
    }
}
