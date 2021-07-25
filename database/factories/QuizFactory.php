<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    function definition()
    {
        $title = $this->faker->unique()->sentence;

        return [
            'title' => $title,
            'slug' => str_slug($title),
            'instructions' => collect(range(1, 4))->map(fn () => $this->faker->sentence),
            'time_limit' => $this->faker->randomElement([25 * 60, 30 * 60, 45 * 60]),
            'questions_limit' => $this->faker->numberBetween(20, 40),
            'event_id' => fn () => Event::factory()->create()->id,
        ];
    }
}
