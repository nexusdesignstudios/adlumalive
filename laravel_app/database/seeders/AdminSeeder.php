<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@adluma.com');
        $password = env('ADMIN_PASSWORD', 'password');
        $name = env('ADMIN_NAME', 'Admin');

        User::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($password)]
        );
    }
}
