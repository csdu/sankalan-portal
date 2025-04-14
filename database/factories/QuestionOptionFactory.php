<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    protected $model = QuestionOption::class;

    public function definition()
    {
        return [
            'key' => $this->faker->bothify('???##'),
            'text' => $this->faker->sentence,
            'question_id' => fn () => Question::factory()->create()->id,
        ];
    }
}
