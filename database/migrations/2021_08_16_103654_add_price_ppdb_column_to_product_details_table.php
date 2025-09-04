<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPricePpdbColumnToProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->decimal('price_ppdb', 8, 2);
            $table->renameColumn('price', 'price_siswa');
        });

        DB::statement("UPDATE product_details SET price_ppdb = price_siswa");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_details', function (Blueprint $table) {
            $table->dropColumn('price_ppdb');
            $table->renameColumn('price_siswa', 'price');
        });
    }
}
