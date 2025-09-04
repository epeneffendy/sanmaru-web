<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('students')) {
            return;
        }

        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('nis')->unique();
            $table->integer('user_id')->index('students_uid_index');
            $table->string('name')->index('students_name_index');
            $table->string('email');
            $table->string('mobile_phone');
            $table->text('address');
            $table->unsignedInteger('unit_id')->index('students_unit_id_index');
            $table->unsignedInteger('payment_agreement_id')->index('students_payment_agreement_id_index');
            $table->integer('school_year');
            $table->integer('register_number')->nullable();
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
        Schema::dropIfExists('students');
    }
}
