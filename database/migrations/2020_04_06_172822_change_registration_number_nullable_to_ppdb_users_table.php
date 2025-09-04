<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeRegistrationNumberNullableToPpdbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->dropColumn('register_number');
        });
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->string('register_number')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->dropColumn('register_number');
        });
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->string('register_number')->index('ppdb_users_reg_number_index');
        });
    }
}
