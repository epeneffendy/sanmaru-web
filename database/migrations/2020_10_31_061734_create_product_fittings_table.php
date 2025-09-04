<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductFittingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_fittings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('unit_id');
            $table->date('date');
            $table->time('hour_start');
            $table->time('hour_end');
            $table->integer('quota');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('product_fittings');
    }
}
