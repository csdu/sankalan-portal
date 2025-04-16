<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'qno' => $this->faker->numberBetween(1, 100),
            'text' => $this->faker->paragraph,
            'quiz_id' => fn () => Quiz::factory()->create()->id,
            'is_multiple' => false,
            'correct_answer_keys' => null,
        ];
    }

    public function multiple()
    {
        $this->state('multiple', fn () => ['is_multiple' => true]);
    }
}
