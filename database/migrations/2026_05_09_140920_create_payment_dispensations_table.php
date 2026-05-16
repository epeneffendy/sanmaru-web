<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentDispensationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_dispensations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ppdb_user_id');
            $table->string("dispensation_type");
            $table->decimal('total_final_fee', 15, 2);
            $table->decimal('actual_cost', 15, 2);
            $table->string('dispensation_mode');
            $table->json('value')->nullable(true);
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
        Schema::dropIfExists('payment_dispensations');
    }
}
