<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceSystemConfigurationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_system_configurations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('min_down_payment',5,2)->default(0);
            $table->decimal('down_payment_multiple', 5,2)->default(0);
            $table->decimal('recommended_down_payment',5,2)->default(0);
            $table->integer('max_absolute_installment')->default(0);
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
        Schema::dropIfExists('finance_system_configurations');
    }
}
