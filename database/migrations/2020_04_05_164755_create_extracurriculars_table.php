<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtracurricularsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('extracurriculars')) {
            return;
        }

        Schema::create('extracurriculars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index('extracurriculars_name_index');
            $table->string('class_id')->index('subjects_class_id_index');
            $table->string('code');
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
        Schema::dropIfExists('extracurriculars');
    }
}
