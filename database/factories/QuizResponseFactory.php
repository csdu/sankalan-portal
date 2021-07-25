<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizResponse;
use App\Models\Team;
use Carbon\Carbon;
use Faker\Generator as Faker;

use Illuminate\Database\Eloquent\Factories\Factory;

class QuizResponseFactory extends Factory
{
    protected $model = QuizResponse::class;

    function definition()
    {
        return [
            'team_id' => fn () => Team::factory()->create()->id,
            'quiz_id' => fn () => Quiz::factory()->create()->id,
            'started_at' => Carbon::now(),
            'finished_at' => null,
        ];
    }
}
