<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeColumnToProductSchedulesTable extends Migration
{
    public function __construct()
    {
        DB::getDoctrineSchemaManager()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_schedules', function (Blueprint $table) {
            $table->enum('type', ['preorder', 'ready'])->default('ready')->after('product_id');
            $table->renameColumn('available_days', 'available_on');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_schedules', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->renameColumn('available_on', 'available_days');
        });
    }
}
