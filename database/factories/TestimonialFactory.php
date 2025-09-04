<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use Faker\Generator as Faker;
use App\Models\Testimonial;
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

$factory->define(Testimonial::class, function (Faker $faker) {
    return [
        'subject' => $faker->name,
        'content' => $faker->sentence(rand(5, 10)),
        'photo_path' => '',
        'published' => true
    ];
});
