<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::query()->insert([
            [
                "name" => "Admin",
                "email" => "admin@mail.com",
                "password" => bcrypt("semuasama"),
                "role_id" => 1
            ],
            [
                "name" => "Content Writer",
                "email" => "writer@mail.com",
                "password" => bcrypt("semuasama"),
                "role_id" => 2
            ],
            [
                "name" => "Content Writer 2",
                "email" => "writer2@mail.com",
                "password" => bcrypt("semuasama"),
                "role_id" => 2
            ],
            [
                "name" => "Content Writer 3",
                "email" => "writer3@mail.com",
                "password" => bcrypt("semuasama"),
                "role_id" => 2
            ],
            [
                "name" => "Adnan Erlansyah",
                "email" => "adnanerlansyah505@mail.com",
                "password" => bcrypt("semuasama"),
                "role_id" => 3,
            ],
        ]);
    }
}
