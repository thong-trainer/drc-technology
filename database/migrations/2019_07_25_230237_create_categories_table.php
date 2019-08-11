<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('category_name');
            $table->double('value', 8, 2)->default(0);
            $table->string('tags')->nullable();
            $table->mediumText('description')->nullable();
            $table->bigInteger('parent_id')->default(0);
            $table->mediumText('image_url')->nullable();
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
        Schema::dropIfExists('categories');
    }
}
