<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Store;
use Faker\Generator as Faker;

$factory->define(Store::class, function (Faker $faker) {
    return [
        'slug' => $faker->slug,
        'name' => $faker->company,
        'logo_url' => $faker->imageUrl(480, 480),
        'description' => $faker->realText,
    ];
});
