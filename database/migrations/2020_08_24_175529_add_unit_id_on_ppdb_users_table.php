<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUnitIdOnPpdbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->unsignedInteger('unit_id')->index('ppdb_users_unit_id_index')->nullable();
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
            $table->dropColumn('unit_id');
        });
    }
}
