<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->text('description')->nullable();
        });

        Schema::create('product_units', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('product_id')->index();
            $table->unsignedInteger('unit_id')->index();
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
        Schema::dropIfExists('product_units');
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedInteger('unit_id');
            $table->dropColumn('description');
        });
    }
}
