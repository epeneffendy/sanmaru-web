<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductAcceptanceDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_acceptance_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_acceptance_id');
            $table->bigInteger('product_detail_id');
            $table->string('size');
            $table->bigInteger('stock');
            $table->decimal('price_siswa');
            $table->decimal('price_ppdb');
            $table->decimal('price_vendor_reguler');
            $table->decimal('price_vendor_ppdb');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_acceptence_detail');
    }
}
