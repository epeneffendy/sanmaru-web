<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('classes')) {
            return;
        }

        Schema::create('classes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('unit_id')->index('classes_unit_id_index');
            $table->string('name')->index('classes_name_index');
            $table->string('unit_class')->index('classes_unit_index');
            $table->softDeletes();
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
        Schema::dropIfExists('classes');
    }
}
