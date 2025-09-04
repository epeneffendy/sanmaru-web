<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddClassIdStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('class_id')->index('students_class_id_index')->nullable();
            $table->dropIndex('students_unit_id_index');
            $table->dropColumn('unit_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_class_id_index');
            $table->dropColumn('class_id');
            $table->unsignedInteger('unit_id')->index('students_unit_id_index');
        });
    }
}
