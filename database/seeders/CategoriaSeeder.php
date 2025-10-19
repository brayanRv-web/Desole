<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorias')->insert([
            ['nombre' => 'Café Caliente', 'tipo' => 'Bebidas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Bebidas Frías', 'tipo' => 'Bebidas', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Postres y Panadería', 'tipo' => 'Comida', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Snacks y Comidas Ligeras', 'tipo' => 'Comida', 'created_at' => now(), 'updated_at' => now()],
            ['nombre' => 'Especialidades de Temporada', 'tipo' => 'Especial', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
