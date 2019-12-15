<?php

use App\Models\Event;
use Faker\Generator as Faker;

$factory->define(App\Models\Quiz::class, function (Faker $faker) {
    $title = $faker->unique()->sentence;

    return [
        'title' => $title,
        'slug' => str_slug($title),
        'instructions' => collect(range(1, 4))->map(function () use ($faker) {
            return $faker->sentence;
        }),
        'time_limit' => $faker->randomElement([25 * 60, 30 * 60, 45 * 60]),
        'questions_limit' => $faker->numberBetween(20, 40),
        'event_id' => function () {
            return factory(Event::class)->create()->id;
        },
    ];
});
