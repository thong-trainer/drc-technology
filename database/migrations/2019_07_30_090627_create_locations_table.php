<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('locations');
        //
        Schema::create('locations', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('location_name')->unique();
            $table->mediumText('description')->nullable();
            $table->boolean('is_default')->default(0);
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
        Schema::dropIfExists('locations');
    }
}
