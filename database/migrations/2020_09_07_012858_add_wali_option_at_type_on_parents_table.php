<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddWaliOptionAtTypeOnParentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE parents MODIFY COLUMN type ENUM('father', 'mother', 'wali') NOT NULL DEFAULT 'father'");
        Schema::table('parents', function (Blueprint $table) {
            $table->string('salary');
            $table->string('education');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE parents MODIFY COLUMN type ENUM('father', 'mother') NOT NULL DEFAULT 'father'");
        Schema::table('parents', function (Blueprint $table) {
            $table->dropColumn('salary');
            $table->dropColumn('education');
        });
    }
}
