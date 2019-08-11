<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDimensionGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimension_groups', function (Blueprint $table) {
            $table->smallIncrements('id');
            $table->string('dimension_group')->unique();
            $table->mediumText('description')->nullable();
            $table->double('value', 8, 2)->default(0);
            $table->string('tags')->nullable();            
            $table->boolean('is_enable')->default(0);
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
        Schema::dropIfExists('dimension_groups');
    }
}
