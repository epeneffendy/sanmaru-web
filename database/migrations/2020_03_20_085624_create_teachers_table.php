<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeachersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('teachers')) {
            return;
        }

        Schema::create('teachers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->index('teachers_uid_index');
            $table->integer('nik')->unique();
            $table->string('name')->index('teachers_name_index');
            $table->string('email');
            $table->string('mobile_phone');
            $table->text('address');
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
        Schema::dropIfExists('teachers');
    }
}
