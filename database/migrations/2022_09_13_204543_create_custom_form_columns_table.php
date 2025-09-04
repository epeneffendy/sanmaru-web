<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFormColumnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_form_columns', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('custom_form_id');
            $table->string('label');
            $table->tinyInteger('type');
            $table->tinyInteger('order')->default(0);
            $table->timestamps();

            $table->index('custom_form_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_form_columns');
    }
}
