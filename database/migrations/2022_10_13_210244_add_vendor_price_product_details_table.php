<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVendorPriceProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->decimal('price_vendor_regular', 8, 2)->default(0);
            $table->decimal('price_vendor_ppdb', 8, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn(['price_vendor_regular', 'price_vendor_ppdb']);
        });
    }
}
