<?php

use Faker\Generator as Faker;
use App\Team;
use App\Quiz;

$factory->define(App\QuizParticipation::class, function (Faker $faker) {
    return [
        'team_id' => function () {
            return factory(Team::class)->create()->id;
        },
        'quiz_id' => function () {
            return factory(Quiz::class)->create()->id;
        },
    ];
});
