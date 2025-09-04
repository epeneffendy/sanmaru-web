<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateSchoolLifeCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_life_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });

        $categories = ['Habitus', 'Retret', 'Live in', 'Servian Camp', 'Servian Project', 'RSO', 'Atraksi Siswa', 'Kegiatan Osis', 'Pembelajaran Daring', 'Bimbingan Konseling'];

        foreach ($categories as $category) {
            DB::table('school_life_categories')->insertOrIgnore([
                'name' => $category,
                'slug' => Str::slug($category),
                'active' => 1,
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
        Schema::dropIfExists('school_life_categories');
    }
}
