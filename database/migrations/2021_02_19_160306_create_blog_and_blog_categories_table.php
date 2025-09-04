<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogAndBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('slug')->nullable();
            $table->boolean('active');
            $table->unsignedInteger('parent_id')->nullable();
            $table->datetime('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::create('blogs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->string('short_desc')->nullable();
            $table->string('slug')->nullable();
            $table->unsignedInteger('blog_category_id');
            $table->longText('content');
            $table->boolean('published')->default(false);
            $table->datetime('publish_date');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('featured_image')->nullable();
            $table->datetime('deleted_at')->nullable();
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
        Schema::dropIfExists('blog_categories');
        Schema::dropIfExists('blogs');
    }
}
