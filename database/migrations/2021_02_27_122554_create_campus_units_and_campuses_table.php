<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCampusUnitsAndCampusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('campuses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('campus_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('campus_id');
            $table->unsignedBigInteger('unit_id');
            $table->string('permalink');

            $table->foreign('campus_id')->references('id')->on('campuses')->onDelete('cascade');
            $table->timestamps();
        }) ;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('campuses');
        Schema::dropIfExists('campus_units');
    }
}
