<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EmpresasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('empresas')->insert([
            'nit' => '123456789',
            'matricula_mercantil' => '987654321',
            'nombre' => 'Empresa 1',
            'user_id' => 4  // ID del usuario que creamos en UserSeeder
        ]);
    }
}
