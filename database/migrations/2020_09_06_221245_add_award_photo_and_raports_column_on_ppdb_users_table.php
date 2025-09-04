<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAwardPhotoAndRaportsColumnOnPpdbUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ppdb_users', function (Blueprint $table) {
            $table->string('award_photo')->nullable();
            $table->text('report_cards')->nullable();
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
            $table->dropColumn('award_photo');
            $table->dropColumn('report_cards');
        });
    }
}
