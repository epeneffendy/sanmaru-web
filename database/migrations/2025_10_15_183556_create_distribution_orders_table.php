<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distribution_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('unit_id');
            $table->date('date');
            $table->string('date_range');
            $table->string('type_student');
            $table->string('description')->nullable(true);
            $table->string('status')->default('active');
            $table->integer('created_by');
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
        Schema::dropIfExists('distribution_orders');
    }
}
