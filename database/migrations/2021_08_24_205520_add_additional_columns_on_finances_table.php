<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdditionalColumnsOnFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->unsignedInteger('unit_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('period_id')->nullable();
            $table->string('type')->nullable();
            $table->year('year')->nullable();
            $table->text('description')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('finances', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->dropColumn('user_id');
            $table->dropColumn('period_id');
            $table->dropColumn('year');
            $table->dropColumn('description');
        });
    }
}
