<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('contact_name')->index();
            $table->string('gender');
            $table->string('position')->nullable();
            $table->string('email')->nullable();
            $table->string('primary_telephone')->unique();
            $table->string('other_telephone')->nullable();
            $table->mediumText('main_address')->nullable();                      
            $table->mediumText('notes')->nullable();
            $table->mediumText('image_url')->nullable();
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
        Schema::dropIfExists('contacts');
    }
}
