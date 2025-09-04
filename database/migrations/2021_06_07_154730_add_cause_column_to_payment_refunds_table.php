<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddCauseColumnToPaymentRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_refunds', function (Blueprint $table) {
            $table->string('cause');
        });

        DB::update("update payment_refunds set refund_type = 'uniform' where refund_type = 'product-order'");
        DB::update("update payment_refunds set refund_type = refund_code where refund_type = 'finance'");
        DB::update("update payment_refunds set cause = 'repayment'");

        Schema::table('payment_refunds', function (Blueprint $table) {
            $table->dropColumn('refund_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_refunds', function (Blueprint $table) {
            $table->dropColumn('cause');
        });

        Schema::table('payment_refunds', function (Blueprint $table) {
            $table->string('refund_code')->nullable();
        });

        DB::update("update payment_refunds set refund_type = 'product-order' where refund_type = 'uniform'");
        DB::update("update payment_refunds set refund_code = refund_type where refund_type <> 'product-order'");
        DB::update("update payment_refunds set refund_type = 'finance' where refund_type <> 'product-order'");
    }
}
