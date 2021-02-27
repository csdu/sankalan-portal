<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    function definition()
    {
        return [
            'qno' => $this->faker->numberBetween(1, 100),
            'text' => $this->faker->paragraph,
            'quiz_id' => function () {
                return Quiz::factory()->create()->id;
            },
            'is_multiple' => false,
            'correct_answer_keys' => null,
        ];
    }

    function multiple()
    {
        $this->state('multiple', function () {
            return [
                'is_multiple' => true,
            ];
        });
    }
}
