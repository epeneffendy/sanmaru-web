<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDispensationRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_dispensation_request', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ppdb_user_id');
            $table->integer('unit_id');
            $table->integer('school_year');
            $table->string('dispensation_type');
            $table->string('description');
            $table->string('reason');
            $table->string('status');
            $table->datetime('verified_date');
            $table->integer('verified_user_id');
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
        Schema::dropIfExists('payment_dispensation_request');
    }
}
