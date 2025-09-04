<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleriablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('galleriables', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('gallery_id');
            $table->unsignedBigInteger('galleriable_id');
            $table->string('galleriable_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('galleriables');
    }
}
