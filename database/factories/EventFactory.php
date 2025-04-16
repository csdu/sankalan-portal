<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    protected $model = Event::class;

    public function definition()
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title' => $title,
            'slug' => str_slug($title),
            'description' => $this->faker->paragraph(),
            'rounds' => $this->faker->numberBetween(1, 3),
        ];
    }
}
