<?php

use Faker\Generator as Faker;
use App\Quiz;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'text' => $faker->paragraph,
        'quiz_id' => function() {
            return factory(Quiz::class)->create()->id;
        },
        'is_multiple' => false,
        'answer_keys' => null,
    ];
});

$factory->state(App\Question::class, 'multiple', function (Faker $faker) {
    return [
        'is_multiple' => true,
        'answer_keys' => $faker->words($faker->numberBetween(0,4)),
    ];
});
