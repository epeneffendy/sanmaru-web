<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniformPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('uniform_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_order_id');
            $table->string('payment_number');
            $table->string('payment_name');
            $table->date('payment_date');
            $table->double('payment_amount', 10, 2)->default("0.00");
            $table->string('payment_method');
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
        Schema::dropIfExists('uniform_payments');
    }
}
