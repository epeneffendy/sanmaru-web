<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CreateFacilityCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facility_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('section');
            $table->timestamps();
            $table->softDeletes();
        });

        $categories = [
            ['name' => 'Facilities Overview', 'section' => 'main'], 
            ['name' => 'Indoor', 'section' => 'sub'],
            ['name' => 'Outdoor', 'section' => 'sub'],
        ];

        foreach ($categories as $category) {
            $category['created_at'] = Carbon::now();
            $category['updated_at'] = Carbon::now();
            DB::table('facility_categories')->insert($category);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility_categories');
    }
}
