<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence(10),
        'subtitle' => $faker->sentence(10),
        'description' => $faker->sentence(50),
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
    ];
});
