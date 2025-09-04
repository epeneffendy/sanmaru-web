<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVoucherOnCartAndProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('carts', function(Blueprint $table) {
            $table->text('voucher')->nullable();
        });
        Schema::table('product_orders', function(Blueprint $table) {
            $table->text('voucher')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('carts', function(Blueprint $table) {
            $table->dropColumn('voucher');
        });
        Schema::table('product_orders', function(Blueprint $table) {
            $table->dropColumn('voucher');
        });
    }
}
