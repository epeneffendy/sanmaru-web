<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStatusPaymentToPaymentDispensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_dispensations', function (Blueprint $table) {
            $table->string('status_payment')->after('dispensation_mode')->default('unpaid');
            $table->string('status')->after('status_payment')->default('active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_dispensations', function (Blueprint $table) {
            //
        });
    }
}
