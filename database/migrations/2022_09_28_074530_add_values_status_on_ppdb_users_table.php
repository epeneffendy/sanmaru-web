<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddValuesStatusOnPpdbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE ppdb_users MODIFY COLUMN status ENUM('incomplete','complete','confirmed','submitted','rejected','accepted','approved') NOT NULL DEFAULT 'incomplete'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE ppdb_users MODIFY COLUMN status ENUM('incomplete','complete','confirmed','submitted','rejected','accepted') NOT NULL DEFAULT 'incomplete'");
    }
}
