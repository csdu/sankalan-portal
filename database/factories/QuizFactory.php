<?php

use Faker\Generator as Faker;
use App\Event;

$factory->define(App\Quiz::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'event_id' => function() {
            return factory(Event::class)->create()->id;
        },
    ];
});
