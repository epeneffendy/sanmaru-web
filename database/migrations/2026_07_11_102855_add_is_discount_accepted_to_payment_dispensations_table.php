<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsDiscountAcceptedToPaymentDispensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_dispensations', function (Blueprint $table) {
            $table->boolean('is_discount_accepted')->default(false)->after('dispensation_mode');
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
            $table->dropColumn('is_discount_accepted');
        });
    }
}
