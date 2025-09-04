<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DropSoftdeletesOnCampusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->unique('name');
        });

        $categories = ['Kampus Santa Maria Sidoarjo', 'Kampus Santa Maria Surabaya', 'Kampus Santa Maria Pacet'];

        foreach ($categories as $category) {
            DB::table('campuses')->insertOrIgnore([
                'name' => $category,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('campuses', function (Blueprint $table) {
            $table->softDeletes();
            $table->dropUnique('campuses_name_unique');
        });
    }
}
