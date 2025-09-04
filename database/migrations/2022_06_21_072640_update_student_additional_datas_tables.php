<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateStudentAdditionalDatasTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('student_additional_datas','nik')) {
                Schema::table('student_additional_datas', function (Blueprint $table) {
                    $table->dropColumn('nik');
                    $table->string('npwp')->after('country')->nullable();
                });
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('student_additional_datas','npwp')) {
                Schema::table('student_additional_datas', function (Blueprint $table) {
                    $table->dropColumn('npwp');
                    $table->string('nik')->after('country')->nullable();
                });
            }
    }
}
