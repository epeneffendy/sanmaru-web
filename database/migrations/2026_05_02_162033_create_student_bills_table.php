<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_bills', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ppdb_user_id');
            $table->integer('finance_id');
            $table->string('type');
            $table->decimal('amount', 12, 2);
            $table->string('payment_method')->default('unpaid'); // unpaid, paid
            $table->date('due_date')->nullable();
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
        Schema::dropIfExists('student_bills');
    }
}
