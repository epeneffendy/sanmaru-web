<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRemainingBalanceColumnTypeInPaymentDispensations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_dispensations', function (Blueprint $table) {
            $table->decimal('remaining_balance', 15, 2)->change();
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
