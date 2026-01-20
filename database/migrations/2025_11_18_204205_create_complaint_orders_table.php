<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComplaintOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaint_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('student_type');
            $table->unsignedBigInteger('complaint_category_id');
            $table->unsignedBigInteger('product_order_id');
            $table->unsignedBigInteger('product_order_detail_id');
            $table->unsignedBigInteger('product_detail_id');
            $table->unsignedBigInteger('product_id');
            $table->string('phone');
            $table->string('email');
            $table->text('description');
            $table->text('reason');
            $table->text('attachment');
            $table->text('attachment_addition')->nullable();
            $table->text('attachment_extra')->nullable();
            $table->date('date_pickup')->nullable();
            $table->string('location_pickup')->nullable();
            $table->string('status')->default('waiting');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('complaint_orders');
    }
}
