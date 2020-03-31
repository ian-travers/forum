<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use Carbon\Carbon;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        'remember_token' => Str::random(10),
        'email_verified_at' => Carbon::now(),
    ];
});

$factory->state(User::class, 'unverified', function () {
    return [
        'email_verified_at' => null,
    ];
});

$factory->state(User::class, 'administrator', function () {
    return [
        'name' => 'Ian Travers',
    ];
});
