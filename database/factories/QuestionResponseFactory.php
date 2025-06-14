<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\QuestionResponse;
use App\Models\QuizResponse;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionResponseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuestionResponse::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'quiz_response_id' => QuizResponse::factory(),
            'question_id' => Question::factory(),
            'response_keys' => $this->faker->word(),
        ];
    }
}
