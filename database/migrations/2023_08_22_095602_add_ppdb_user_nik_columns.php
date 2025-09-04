<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPpdbUserNikColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->string('nik_siswa')->after('name');
            $table->string('nik_ortu')->after('nik_siswa');
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
            $table->dropColumn('nik_siswa');
            $table->dropColumn('nik_ortu');
        });
    }
}
