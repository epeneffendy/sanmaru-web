<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDispensationDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_dispensation_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('payment_dispensation_id');
            $table->integer('installment_number');
            $table->string('virtual_account')->nullable();
            $table->date('date')->nullable();
            $table->decimal('nominal', 15, 2);
            $table->decimal('amount_paid', 15, 2)->default(0);
            $table->string('status')->default('unpaid'); //unpaid, paid, partial
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
        Schema::dropIfExists('payment_dispensation_details');
    }
}
