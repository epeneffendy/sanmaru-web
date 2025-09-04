<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastLoginAndLogoutDateOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->datetime('last_login_date')->nullable();
            $table->datetime('last_logout_date')->nullable();
            $table->integer('failed_login_counts')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('last_login_date');
            $table->dropColumn('last_logout_date');
            $table->dropColumn('failed_login_counts');
        });
    }
}
