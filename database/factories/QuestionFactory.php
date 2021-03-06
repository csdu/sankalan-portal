<?php

use App\Models\Quiz;
use Faker\Generator as Faker;

$factory->define(App\Models\Question::class, function (Faker $faker) {
    return [
        'qno' => $faker->numberBetween(1, 100),
        'text' => $faker->paragraph,
        'quiz_id' => function () {
            return factory(Quiz::class)->create()->id;
        },
        'is_multiple' => false,
        'correct_answer_keys' => null,
    ];
});

$factory->state(App\Models\Question::class, 'multiple', function (Faker $faker) {
    return [
        'is_multiple' => true,
    ];
});
