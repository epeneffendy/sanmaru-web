<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRenameAttributeProductOrderIdToCartIdOnCartDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('cart_details');
        Schema::create('cart_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cart_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_detail_id');
            $table->bigInteger('quantity')->default(1);
            $table->decimal('total_price', 10, 2);
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('cart_id')
                ->references('id')
                ->on('carts')
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
        //cannot be undone
    }
}
