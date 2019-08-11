<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code')->unique();
            $table->bigInteger('contact_id');            
            $table->string('company_id')->default(0);
            $table->string('type')->nullable(); // Individual or Company
            $table->string('tags')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('content')->nullable();
            $table->boolean('is_enable')->default(0);
            $table->boolean('is_delete')->default(0);                 
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();  
        });

        DB::update("ALTER TABLE suppliers AUTO_INCREMENT = 1001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('suppliers');
    }
}
