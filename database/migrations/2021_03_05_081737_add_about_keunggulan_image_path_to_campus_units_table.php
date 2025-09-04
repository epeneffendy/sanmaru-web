<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAboutKeunggulanImagePathToCampusUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campus_units', function (Blueprint $table) {
            $table->string('image_path')->nullable();
            $table->longText('about')->nullable();
            $table->longText('keunggulan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campus_units', function (Blueprint $table) {
            $table->dropColumn('image_path');
            $table->dropColumn('about');
            $table->dropColumn('keunggulan');
        });
    }
}
