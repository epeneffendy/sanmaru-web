<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpdbResignationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppdb_resignations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('ppdb_user_id');
            $table->string('register_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ppdb_resignations');
    }
}
