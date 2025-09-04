<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentTypeToProductOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->string('payment_type')->after('payment_confirmed_mail_sent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->dropColumn('payment_type');
        });
    }
}
