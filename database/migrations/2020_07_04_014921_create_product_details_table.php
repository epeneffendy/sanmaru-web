<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('products', 'size')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_size_index');
                $table->dropColumn('size');
            });
        }

        if (Schema::hasColumn('products', 'stock')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }

        if (Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }

        Schema::create('product_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->unsignedBigInteger('stock');
            $table->string('size')->nullable();
            $table->decimal('price', 8, 2);
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
        Schema::dropIfExists('product_details');

        Schema::table('products', function (Blueprint $table) {
            $table->string('size')->index('products_size_index')->nullable();
            $table->unsignedBigInteger('stock');
            $table->decimal('price', 16, 2);
        });
    }
}
