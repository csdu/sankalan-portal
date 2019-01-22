<?php

use Faker\Generator as Faker;

$factory->define(App\Event::class, function (Faker $faker) {
    return [
        'title' => $faker->words(2, true),
        'description' => $faker->paragraph(),
        'rounds' => $faker->numberBetween(1,3),
        'hasQuiz' => true,
    ];
});
$factory->state(App\Event::class, 'withoutQuiz', [
    'hasQuiz' => false,
]);
