<?php

namespace Database\Factories;


use App\Models\Question;
use App\Models\QuestionOption;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    protected $model = QuestionOption::class;

    function definition()
    {
        return [
            'key' => $this->faker->bothify("???##"),
            'text' => $this->faker->sentence,
            'question_id' => fn () => Question::factory()->create()->id,
        ];
    }
}
