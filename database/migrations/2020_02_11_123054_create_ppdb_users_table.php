<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePpdbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ppdb_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index('ppdbusers_name_index');
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('place_of_birth')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('religion')->nullable();
            $table->string('payment_form')->nullable();
            $table->string('birth_certificate')->nullable();
            $table->string('photo')->nullable();
            $table->string('family_card')->nullable();
            $table->string('baptism_certificate')->nullable();
            $table->enum('status',['incomplete', 'complete', 'confirmed'])->index('ppdbusers_status');
            $table->unsignedSmallInteger('school_year')->nullable();
            $table->unsignedSmallInteger('periode')->nullable();
            $table->bigInteger('user_id');
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
        Schema::dropIfExists('ppdb_users');
    }
}
