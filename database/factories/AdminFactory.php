<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Admin;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Admin::class, function (Faker $faker) {
    return [
        'first_name' => $faker->firstName(),
        'last_name' => $faker->lastName,
        'email' => $faker->safeEmail,
        'phone' =>  $faker->phoneNumber,
        'dob' => $faker->date('Y-m-d', 'now'),
        'password' => "password",
    ];
});
