<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $mode = User::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'phone' => substr(str_replace(' ', '', $this->faker->unique()->phoneNumber), -10, 10),
            'email' => $this->faker->unique()->safeEmail,
            'college' => $this->faker->words(4, true),
            'course' => $this->faker->randomElement(['Bsc I', 'Msc II', 'MCA I', 'MCA II']),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token' => str_random(10),
        ];
    }
}
