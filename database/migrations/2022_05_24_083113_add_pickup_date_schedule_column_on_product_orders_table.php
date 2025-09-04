<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickupDateScheduleColumnOnProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->date('pickup_date_schedule')->after('pickup_date')->nullable();
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
            $table->dropColumn('pickup_date_schedule');
        });
    }
}
