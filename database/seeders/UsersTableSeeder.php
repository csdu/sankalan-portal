<?php

namespace Database\Seeders;

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
        factory('App\Models\User')->create([
            'first_name' => 'Default',
            'last_name' => 'User',
            'email' => 'sankalan@ducs.in',
        ]);

        factory('App\Models\User')->create([
            'first_name' => 'admin',
            'last_name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        factory('App\Models\User')->create([
            'first_name' => 'nonadmin',
            'last_name' => 'nonadmin',
            'password' => bcrypt('password'),
            'email' => 'nonadmin@admin.com',
        ]);

        factory('App\Models\User', 7)->create();
    }
}
