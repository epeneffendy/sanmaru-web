<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddQuotaAndStartNumberOnPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->integer('quota')->nullable();
            $table->integer('start_register_number')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periods', function (Blueprint $table) {
            $table->dropColumn('quota');
            $table->dropColumn('start_register_number');
        });
    }
}
