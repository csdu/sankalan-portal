<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\Team;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizResponseFactory extends Factory
{
    protected $model = QuizResponse::class;

    public function definition()
    {
        return [
            'team_id' => fn () => Team::factory()->create()->id,
            'quiz_id' => fn () => Quiz::factory()->create()->id,
            'started_at' => Date::now(),
            'finished_at' => null,
        ];
    }
}
