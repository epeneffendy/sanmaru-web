<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHeadlinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('headlines', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('is_unit')->default(false);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->enum('type', ['image', 'video']);
            $table->boolean('published')->default(false);
            $table->string('content_url');
            $table->timestamps();

            $table->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('headlines');
    }
}
