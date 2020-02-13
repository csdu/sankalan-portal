<?php

use App\Models\Question;
use App\Models\QuestionAttachment;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

$factory->define(QuestionAttachment::class, function (Faker $faker) {
    return [
        'question_id' => factory(Question::class)->create()->id,
        'path' => 'illustrations/'.Str::random(),
    ];
});
