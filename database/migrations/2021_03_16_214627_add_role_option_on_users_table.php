<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRoleOptionOnUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('admin', 'guru', 'siswa', 'vendor', 'ppdb', 'author', 'editor', 'shop', 'super_admin') NOT NULL DEFAULT 'admin'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN type ENUM('admin', 'guru', 'siswa', 'vendor', 'ppdb') NOT NULL DEFAULT 'admin'");
    }
}
