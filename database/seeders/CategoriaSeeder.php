<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Categoria;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run()
    {
        // Limpiar las tablas de manera segura
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('categorias')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $categorias = [
            [
                'nombre' => 'Comidas Principales',
                'status' => 'activo',
                'tipo' => 'comida',
                'icono' => 'utensils',
                'color' => '#EF4444',
                'orden' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Bebidas',
                'status' => 'activo',
                'tipo' => 'bebida',
                'icono' => 'glass-whiskey',
                'color' => '#3B82F6',
                'orden' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Acompañamientos',
                'status' => 'activo',
                'tipo' => 'acompanamiento',
                'icono' => 'plate-utensils',
                'color' => '#8B5CF6',
                'orden' => 3,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'nombre' => 'Postres',
                'status' => 'activo',
                'tipo' => 'postre',
                'icono' => 'ice-cream',
                'color' => '#EC4899',
                'orden' => 4,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        foreach ($categorias as $categoria) {
            Categoria::create($categoria);
        }

        $this->command->info('✅ Categorías creadas correctamente');
    }
}