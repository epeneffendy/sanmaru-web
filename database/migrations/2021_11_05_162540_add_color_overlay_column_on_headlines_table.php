<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColorOverlayColumnOnHeadlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('headlines', function (Blueprint $table) {
            $table->string('color_overlay')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('headlines', function (Blueprint $table) {
            $table->dropColumn('color_overlay');
        });
    }
}
