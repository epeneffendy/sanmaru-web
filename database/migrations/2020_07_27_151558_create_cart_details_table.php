<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cart_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_detail_id');
            $table->bigInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('product_order_id')
                ->references('id')
                ->on('product_orders')
                ->onDelete('cascade');

            $table->foreign('product_detail_id')
                ->references('id')
                ->on('product_details');

            $table->foreign('product_id')
                ->references('id')
                ->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cart_details');
    }
}
