<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('invoices');
        //
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_number')->unique();
            $table->bigInteger('quotation_id');
            $table->bigInteger('customer_id');
            $table->bigInteger('salesperson_id');
            $table->datetime('issue_date');
            $table->date('due_date');
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
            $table->string('status')->default(config('global.invoice_status.issued'));
            $table->boolean('is_cancel')->default(0);
            $table->boolean('is_delete')->default(0);
            $table->boolean('is_default_currency')->default(1);
            $table->timestamps();
            $table->bigInteger('created_by');
            $table->bigInteger('updated_by')->nullable();
        });

        DB::update("ALTER TABLE invoices AUTO_INCREMENT = 10001;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
