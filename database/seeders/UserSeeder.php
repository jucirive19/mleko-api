<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('laclave123'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ]);     
        User::create([
            'name' => 'Recolector 1',
            'email' => 'recolector1@example.com',
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'name' => 'Recolector 2',
            'email' => 'recolector2@example.com',
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),   
        ]);

        User::create([
            'name' => 'Empresa 1',
            'email' => 'empresa1@example.com',
            'password' => Hash::make('password123'),
            'remember_token' => Str::random(10),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}