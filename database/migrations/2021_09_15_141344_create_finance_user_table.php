<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_user', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('finance_id');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();

            $table->unique(['finance_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('finance_user');
    }
}
