<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFormPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_form_periods', function (Blueprint $table) {
            $table->bigInteger('custom_form_id');
            $table->bigInteger('period_id');

            $table->primary(['custom_form_id', 'period_id'], 'pk_custom_form_periods');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_form_periods');
    }
}
