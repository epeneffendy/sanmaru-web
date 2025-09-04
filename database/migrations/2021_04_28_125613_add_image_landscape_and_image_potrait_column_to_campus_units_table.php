<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddImageLandscapeAndImagePotraitColumnToCampusUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campus_units', function (Blueprint $table) {
            $table->string('image_potrait_path')->nullable();
            $table->string('image_landscape_path')->nullable();
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
            $table->dropColumn('image_potrait_path');
            $table->dropColumn('image_landscape_path');
        });
    }
}
