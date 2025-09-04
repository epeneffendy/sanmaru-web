<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProfileToUnits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->string('address')->nullable()->after('unit_code');
            $table->string('email')->nullable();
            $table->unsignedBigInteger('phone')->nullable();
            $table->string('image_path')->nullable();
            $table->string('banner_path')->nullable();
            $table->text('about')->nullable();
            $table->text('keunggulan')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('units', function (Blueprint $table) {
            $table->dropColumn([
                'address',  
                'email', 
                'phone', 
                'image_path', 
                'banner_path',
                'about',
                'keunggulan'
            ]);
        });
    }
}
