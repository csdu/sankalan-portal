<?php

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
        factory('App\User')->create([
            'first_name' => 'Default',
            'last_name' => 'User',
            'email' => 'sankalan@ducs.in',
        ]);

        factory('App\User', 7)->create();
    }
}
