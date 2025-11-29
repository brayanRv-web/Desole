<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriasTableSeeder extends Seeder
{
    public function run()
    {
        $categorias = [
            [
                'nombre' => 'Comidas Principales',
                'tipo' => 'comida',
                'icono' => 'utensils',
                'color' => '#EF4444',
                'orden' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Bebidas',
                'tipo' => 'bebida',
                'icono' => 'glass-whiskey',
                'color' => '#3B82F6',
                'orden' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Acompañamientos',
                'tipo' => 'acompanamiento',
                'icono' => 'plate-utensils',
                'color' => '#8B5CF6',
                'orden' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nombre' => 'Postres',
                'tipo' => 'postre',
                'icono' => 'ice-cream',
                'color' => '#EC4899',
                'orden' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('categorias')->insert($categorias);
    }
}