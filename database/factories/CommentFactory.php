<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Comment;
use Faker\Generator as Faker;

$factory->define(Comment::class, function (Faker $faker) {
    return [
        'body' => $faker->sentence(50),
        'user_id' => $faker->numberBetween($min = 1, $max = 10),
        'post_id' => $faker->numberBetween($min = 1, $max = 20),
    ];
});
