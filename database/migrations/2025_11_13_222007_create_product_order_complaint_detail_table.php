<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOrderComplaintDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_order_complaint_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('product_order_complaint_id');
            $table->bigInteger('product_order_id');
            $table->bigInteger('product_order_detail_id');
            $table->bigInteger('product_id');
            $table->text('complaint');
            $table->text('complaint_response')->nullable();
            $table->string('status');
            $table->bigInteger('created_by')->nullable();
            $table->bigInteger('updated_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_order_complaint_detail');
    }
}
