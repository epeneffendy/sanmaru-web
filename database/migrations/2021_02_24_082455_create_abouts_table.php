<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAboutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('abouts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('short_desc')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('about_category_id');
            $table->longText('content');
            $table->boolean('published')->default(false);
            $table->datetime('publish_date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('featured_image')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('abouts');
    }
}
