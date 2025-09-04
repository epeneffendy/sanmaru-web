<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\User;
use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Blog::class, function (Faker $faker) {
    $blogCategoryId = BlogCategory::where('active', true)->first();
    $title = $faker->sentence(rand(5, 10));

    return [
        'title' => $title,
        'short_desc' => $faker->text(180),
        'content' => $faker->text(700),
        'slug' => Str::slug($title),
        'published' => true,
        'blog_category_id' => $blogCategoryId,
        'publish_date' => Carbon::now()->subMinutes(rand(10, 100))->toDateTimeString(),
        'user_id' => User::where('type', 'admin')->first()
    ];
});
