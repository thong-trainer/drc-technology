<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->smallInteger('role_id');
            $table->smallInteger('module_id');
            $table->boolean('is_view')->default(0);
            $table->boolean('is_create')->default(0);
            $table->boolean('is_edit')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->boolean('is_export')->default(0);
            $table->boolean('is_import')->default(0);
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
        Schema::dropIfExists('permissions');
    }
}
