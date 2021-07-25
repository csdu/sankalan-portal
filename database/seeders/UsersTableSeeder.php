<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'first_name' => 'Default',
            'last_name' => 'User',
            'email' => 'sankalan@ducs.in',
        ]);

        User::factory()->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        User::factory()->create([
            'first_name' => 'nonadmin',
            'last_name' => 'nonadmin',
            'password' => bcrypt('password'),
            'email' => 'nonadmin@admin.com',
        ]);

        User::factory()->count(7)->create();
    }
}
