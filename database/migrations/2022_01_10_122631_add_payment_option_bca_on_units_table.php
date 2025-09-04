<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPaymentOptionBcaOnUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE units MODIFY COLUMN payment_option ENUM('CIMB Niaga', 'Mandiri', 'BCA') NOT NULL DEFAULT 'CIMB Niaga'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE units MODIFY COLUMN payment_option ENUM('CIMB Niaga', 'Mandiri') NOT NULL DEFAULT 'CIMB Niaga'");
    }
}
