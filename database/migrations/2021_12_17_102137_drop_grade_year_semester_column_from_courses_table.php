<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropGradeYearSemesterColumnFromCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('grade');
            $table->dropColumn('year');
            $table->dropColumn('semester');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('grade')->after('code');
            $table->smallInteger('year')->after('grade');
            $table->enum('semester', [1, 2])->after('year');
        });
    }
}
