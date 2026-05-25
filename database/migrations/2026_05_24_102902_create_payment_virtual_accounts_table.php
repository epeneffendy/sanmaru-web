<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentVirtualAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_virtual_accounts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ppdb_user_id');
            $table->string('type');
            $table->string('virtual_account_number');
            $table->decimal('total_payment', 15, 2)->default(0);
            $table->dateTime('payment_date')->nullable(true);
            $table->string('status')->default('unpaid'); //unpaid, paid
            $table->string('payment_option')->nullable(true);
            $table->string('payment_inquiry_id')->nullable(true);
            $table->dateTime('expired_at')->nullable(true);
            $table->json('callback_raw')->nullable(true);
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
        Schema::dropIfExists('payment_virtual_accounts');
    }
}
