<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Recolector 1',
            'email' => 'recolector1@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Recolector 2',
            'email' => 'recolector2@example.com',
            'password' => Hash::make('password123'),
        ]);

        User::create([
            'name' => 'Empresa 1',
            'email' => 'empresa1@example.com',
            'password' => Hash::make('password123'),
        ]);
    }
}
