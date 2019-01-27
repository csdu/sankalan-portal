<?php

use Faker\Generator as Faker;
use App\Quiz;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'text' => $faker->paragraph,
        'quiz_id' => function() {
            return factory(Quiz::class)->create()->id;
        },
        'is_multiple' => $faker->boolean(),
        'answer_keys' => $faker->randomLetter,
    ];
});
