<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPickupColumnsIntoProductOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_orders', function (Blueprint $table) {
            $table->time('pickup_start_time')->nullable()->after('pickup_date');
            $table->time('pickup_end_time')->nullable()->after('pickup_start_time');
            $table->string('pickup_location')->nullable()->after('pickup_end_time');
            $table->text('pickup_notes')->nullable()->after('pickup_location');
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
            $table->dropColumn('pickup_start_time');
            $table->dropColumn('pickup_end_time');
            $table->dropColumn('pickup_location');
            $table->dropColumn('pickup_notes');
        });
    }
}
