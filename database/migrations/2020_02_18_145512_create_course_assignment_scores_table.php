<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseAssignmentScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assignment_scores', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_assignment_id')->index('cassscore_cass_id_index');
            $table->bigInteger('user_id')->index('cassscore_user_id_index');
            $table->tinyInteger('score');
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
        Schema::dropIfExists('course_assignment_scores');
    }
}
