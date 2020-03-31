<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Channel;
use App\Thread;
use App\User;
use Faker\Generator as Faker;

$factory->define(Thread::class, function (Faker $faker) {
    $title = $faker->sentence(4);

    return [
        'user_id' => function() {
            return factory(User::class)->create()->id;
        },
        'channel_id' => function() {
            return factory(Channel::class)->create()->id;
        },
        'title' => $title,
        'slug' => Str::slug($title),
        'body' => $faker->paragraph(),
        'visits' => 0,
        'locked' => false,
    ];
});
