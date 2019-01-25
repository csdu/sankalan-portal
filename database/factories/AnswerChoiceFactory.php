<?php

use Faker\Generator as Faker;

$factory->define(App\AnswerChoice::class, function (Faker $faker) {
    return [
        'key' => $faker->unique()->randomLetter,
        'text' => $faker->sentence,
        'question_id' => function() {
            return factory(Question::class)->create()->id;
        },
    ];
});
