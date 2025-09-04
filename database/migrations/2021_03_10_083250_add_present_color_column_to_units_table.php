<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class AddPresentColorColumnToUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('present_color', 50)->nullable();
        });

        $colors = [
            'SMP' => 'primary', 
            'SD' => 'danger', 
            'KB' => 'warning',
            'TK' => 'warning',
            'SMA' => 'success'
        ];

        foreach ($colors as $key => $color) {
            DB::table('units')
                ->where('name', 'like', '%'.$key.'%')
                ->update(['present_color' => $color]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn('present_color');
        });
    }
}
