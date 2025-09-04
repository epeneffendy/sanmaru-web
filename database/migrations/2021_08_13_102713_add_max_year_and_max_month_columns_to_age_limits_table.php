<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMaxYearAndMaxMonthColumnsToAgeLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('age_limits', function (Blueprint $table) {
            $table->integer('max_year')->nullable();
            $table->integer('max_month')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('age_limits', function (Blueprint $table) {
            $table->dropColumn('max_year');
            $table->dropColumn('max_month');
        });
    }
}
