<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFormInputsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_form_inputs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('custom_form_column_id');
            $table->bigInteger('ppdb_user_id');
            $table->text('value');
            $table->timestamps();

            $table->index('ppdb_user_id');
            $table->index('custom_form_column_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_form_inputs');
    }
}
