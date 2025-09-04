<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->index('billusers_userid_index');
            $table->bigInteger('bill_id')->index('billusers_billid_index');
            $table->string('bill_name');
            $table->date('bill_due_date');
            $table->decimal('bill_amount', 16, 2);
            $table->bigInteger('bill_category_id')->index('billusers_category_id_index');
            $table->enum('status',['unpaid','paid']);
            $table->date('paid_date')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('bill_users');
    }
}
