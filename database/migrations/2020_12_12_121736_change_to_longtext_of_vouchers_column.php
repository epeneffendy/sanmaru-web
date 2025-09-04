<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToLongtextOfVouchersColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE carts MODIFY voucher longtext NULL");
        DB::statement("ALTER TABLE product_orders MODIFY voucher longtext NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE carts MODIFY voucher text NULL");
        DB::statement("ALTER TABLE product_orders MODIFY voucher text NULL");
    }
}
