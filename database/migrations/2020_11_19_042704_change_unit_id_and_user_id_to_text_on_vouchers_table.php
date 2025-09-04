<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUnitIdAndUserIdToTextOnVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->dropIndex('vouchers_unit_id_index');
            $table->dropIndex('vouchers_user_id_index');
        });

        Schema::table('vouchers', function (Blueprint $table) {
            $table->text('unit_id')->nullable()->default(null)->change();
            $table->text('user_id')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vouchers', function (Blueprint $table) {
            $table->string('unit_id')->nullable()->change();
            $table->string('user_id')->nullable()->change();
        });
    }
}
