<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameBaptismCertificateOfPpdbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->renameColumn('baptism_certificate', 'baptismal_certificate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $platform = Schema::getConnection()->getDoctrineSchemaManager()->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->renameColumn('baptismal_certificate', 'baptism_certificate');
        });
    }
}
