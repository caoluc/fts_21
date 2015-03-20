<?php

use Illuminate\Database\Seeder;
use App\User;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('users')->truncate();

        $users = [
            [
                'name' => 'caoluc',
                'email' => 'caoluc@mail.com',
                'password' => Hash::make('caolucok'),
            ],
            [
                'name' => 'User',
                'email' => 'user@mail.com',
                'password' => Hash::make('user'),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@mail.com',
                'password' => Hash::make('admin'),
                'role' => 1,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
