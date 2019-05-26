<?php

use Faker\Generator as Faker;
use App\Question;

$factory->define(App\AnswerChoice::class, function (Faker $faker, $attributes) {
    if (array_key_exists('question_id', $attributes)) {
        $question_id = $attributes['question_id'];
    } else {
        $question_id = factory(Question::class)->create()->id;
    }
    return [
        'key' => str_before($faker->unique()->bothify("???##-$question_id"), '-'),
        'text' => $faker->sentence,
        'question_id' => $question_id,
    ];
});
