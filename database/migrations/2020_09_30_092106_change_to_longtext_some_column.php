<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToLongtextSomeColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE units MODIFY about longtext NULL");
        DB::statement("ALTER TABLE units MODIFY keunggulan longtext NULL");
        DB::statement("ALTER TABLE units MODIFY `procedure` longtext NULL");
        DB::statement("ALTER TABLE units MODIFY helpdesk longtext NULL");
        DB::statement("ALTER TABLE units MODIFY header_info longtext NULL");

        DB::statement("ALTER TABLE ppdb_users MODIFY additional_info longtext NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE units MODIFY about text NULL");
        DB::statement("ALTER TABLE units MODIFY keunggulan text NULL");
        DB::statement("ALTER TABLE units MODIFY `procedure` text NULL");
        DB::statement("ALTER TABLE units MODIFY helpdesk text NULL");
        DB::statement("ALTER TABLE units MODIFY header_info text NULL");

        DB::statement("ALTER TABLE ppdb_users MODIFY additional_info text NULL");
    }
}
