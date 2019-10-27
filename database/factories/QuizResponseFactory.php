<?php

use App\Models\Quiz;
use App\Models\Team;
use Faker\Generator as Faker;

$factory->define(App\Models\QuizResponse::class, function (Faker $faker) {
    return [
        'team_id' => function () {
            return factory(Team::class)->create()->id;
        },
        'quiz_id' => function () {
            return factory(Quiz::class)->create()->id;
        },
    ];
});
