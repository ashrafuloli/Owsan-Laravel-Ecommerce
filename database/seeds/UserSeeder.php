<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::insert([
            [
                'email' => 'admin@admin.com',
                'password' => bcrypt('password'),
            ],
            [
                'email' => 'user@admin.com',
                'password' => bcrypt('password'),
            ],
        ]);
    }
}
