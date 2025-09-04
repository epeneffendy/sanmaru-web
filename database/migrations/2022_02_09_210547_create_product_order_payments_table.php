<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrderPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order_payments', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->bigInteger('product_order_id')->unsigned();
            $table->string('bank', 50);
            $table->decimal('total_payment', 15, 2)->default(0);
            $table->dateTime('payment_date');
            $table->string('status', 10)->default('pending');
            $table->bigInteger('payment_bca_id')->nullable();
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
        Schema::dropIfExists('product_order_payments');
    }
}
