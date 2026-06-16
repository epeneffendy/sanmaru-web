<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInvoiceNumberToPaymentVirtualAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_virtual_accounts', function (Blueprint $table) {
            $table->string('invoice_number')->after('ppdb_user_id')->nullable(true);
            $table->string('description')->after('payment_option')->nullable(true);
            $table->integer('payment_dispensation_detail_id')->after('payment_inquiry_id')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_virtual_accounts', function (Blueprint $table) {
            //
        });
    }
}
