<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentAgreementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('payment_agreements')) {
            return;
        }

        Schema::create('payment_agreements', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->index("payment_agreements_name_index");
            $table->text('desc');
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
        Schema::dropIfExists('payment_agreements');
    }
}
