<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdjustPpdbUserTable extends Migration
{
    public $set_schema_table = 'ppdb_users';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE ".$this->set_schema_table." MODIFY COLUMN status ENUM('incomplete', 'complete', 'confirmed', 'submitted', 'rejected', 'accepted') NOT NULL DEFAULT 'incomplete'");
        Schema::table($this->set_schema_table, function (Blueprint $table) {
            $table->string('origin_school')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->set_schema_table, function (Blueprint $table) {
            $table->dropColumn('origin_school');
        });
    }
}
