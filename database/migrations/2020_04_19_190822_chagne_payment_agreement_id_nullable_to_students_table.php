<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChagnePaymentAgreementIdNullableToStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_payment_agreement_id_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('payment_agreement_id')->nullable()->index('students_payment_agreement_id_index')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_payment_agreement_id_index');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->unsignedInteger('payment_agreement_id')->index('students_payment_agreement_id_index')->change();
        });
    }
}
