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
            $table->integer('unit_id');
            $table->integer('ppdb_user_id');
            $table->string('reason');
            $table->string('attachment', 255)->nullable(true);
            $table->enum('status', ['draft', 'approved','cancelled'])->default('draft');
            $table->integer('user_id');
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
