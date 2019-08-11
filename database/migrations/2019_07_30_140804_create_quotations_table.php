<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::dropIfExists('quotations');
        //
        Schema::create('quotations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('quotation_number')->unique();
            $table->bigInteger('customer_id');
            $table->datetime('quotation_date');
            $table->date('validity_date');
            $table->smallInteger('payment_term_id')->default(0);
            $table->smallInteger('delivery_method_id')->default(0);
            $table->smallInteger('currency_id');
            $table->mediumText('notes')->nullable();
            $table->double('amount', 8, 2)->default(0);
            $table->double('tax', 8, 2)->default(0);
            $table->smallInteger('discount')->default(0);
            $table->double('discount_amount', 8, 2)->default(0);
            $table->double('grand_total', 8, 2)->default(0);
            $table->double('rate', 8, 2);
            $table->string('status')->default(config('global.quotation_status.pending'));
            $table->datetime('confirm_date')->nullable();
            $table->boolean('is_confirm')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->boolean('is_default_currency')->default(1);
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
        });

        DB::update("ALTER TABLE quotations AUTO_INCREMENT = 10001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quotations');
    }
}
