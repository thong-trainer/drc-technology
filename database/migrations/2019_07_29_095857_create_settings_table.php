<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('settings');
        Schema::create('settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('section'); // company
            $table->string('label'); // company_name
            $table->string('display_name'); // Company Name
            $table->string('input_value')->nullable(); // PAPAPoS
            $table->string('data_type')->nullable(); // string - integer - boolean
            $table->string('tags')->nullable(); // PAPAPoS
            $table->mediumText('notes')->nullable();
            $table->mediumText('description')->nullable();
            $table->boolean('is_enable')->default(1);
            $table->boolean('is_hide')->default(0);
            $table->timestamps();
            $table->bigInteger('created_by')->nullable();
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
        Schema::dropIfExists('settings');
    }
}
