<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUniformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('uniforms')) {
            return;
        }

        Schema::create('uniforms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index('uniforms_name_index');
            $table->string('level');
            $table->string('size')->index('uniforms_size_index');
            $table->enum('gender', ['male', 'female']);
            $table->unsignedBigInteger('prize_basic');
            $table->unsignedBigInteger('prize_male');
            $table->unsignedBigInteger('prize_female');
            $table->string('brand');
            $table->integer('unit_id')->index('unifors_unit_id_index');
            $table->enum('status', ['published', 'unpublished']);
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
        Schema::dropIfExists('uniforms');
    }
}
