<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpdbUserStagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppdb_user_stages', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('ppdb_user_id');
            $table->unsignedBigInteger('stage_id');
            $table->foreign('stage_id')
                ->references('id')
                ->on('stages')
                ->onDelete('cascade');
            $table->boolean('passed')
                ->nullable();
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
        Schema::dropIfExists('ppdb_user_stages');
    }
}