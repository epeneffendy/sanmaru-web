<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentBcasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_bcas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('company_code', 20);
            $table->string('channel_type', 4);
            $table->string('request_id', 30);
            $table->string('customer_number', 18);
            $table->string('sub_company', 5);
            $table->string('currency', 3);
            $table->string('status', 2);
            $table->string('reference', 15);
            $table->string('bill_number', 18);
            $table->dateTime('transaction_date');
            $table->decimal('total_amount', 15, 2);
            $table->decimal('paid_amount', 15, 2);
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
        Schema::dropIfExists('payment_bcas');
    }
}
