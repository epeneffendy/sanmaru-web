<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourseUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('course_id')->index('cusers_cid_index');
            $table->bigInteger('user_id')->index('cusers_uid_index');
            $table->bigInteger('uts_score')->nullable();
            $table->bigInteger('uas_score')->nullable();
            $table->smallInteger('year_taken');
            $table->enum('semester_taken',[1,2]);
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
        Schema::dropIfExists('course_users');
    }
}
