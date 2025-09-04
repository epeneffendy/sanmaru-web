<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_refunds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('refund_id');
            $table->string('refund_type');
            $table->string('refund_code')->nullable();
            $table->string('status');
            $table->decimal('nominal_price', 10, 2)->default('0.00');
            $table->decimal('nominal_refund', 10, 2)->default('0.00');
            $table->string('refund_image')->nullable();
            $table->text('note')->nullable();
            $table->unsignedBigInteger('updated_by_id');
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
        Schema::dropIfExists('payment_refunds');
    }
}
