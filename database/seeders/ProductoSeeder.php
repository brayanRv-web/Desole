<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        // Comidas Principales (Categoría 1)
        $this->createProducto([
            'nombre' => 'Hamburguesa Clásica',
            'descripcion' => 'Deliciosa hamburguesa con carne de res, lechuga, tomate y queso',
            'precio' => 12.99,
            'categoria_id' => 1,
            'stock' => 50,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Pizza Margherita',
            'descripcion' => 'Pizza tradicional con salsa de tomate, mozzarella y albahaca',
            'precio' => 15.99,
            'categoria_id' => 1,
            'stock' => 30,
            'status' => 'activo',
        ]);

        // Bebidas (Categoría 2)
        $this->createProducto([
            'nombre' => 'Limonada Fresca',
            'descripcion' => 'Limonada natural con hierbabuena',
            'precio' => 3.99,
            'categoria_id' => 2,
            'stock' => 100,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Smoothie de Frutas',
            'descripcion' => 'Batido natural de frutas mixtas',
            'precio' => 4.99,
            'categoria_id' => 2,
            'stock' => 80,
            'status' => 'activo',
        ]);

        // Acompañamientos (Categoría 3)
        $this->createProducto([
            'nombre' => 'Papas Fritas',
            'descripcion' => 'Papas fritas crujientes con sal marina',
            'precio' => 5.99,
            'categoria_id' => 3,
            'stock' => 150,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Ensalada César',
            'descripcion' => 'Ensalada fresca con pollo, crutones y aderezo césar',
            'precio' => 8.99,
            'categoria_id' => 3,
            'stock' => 40,
            'status' => 'activo',
        ]);

        // Postres (Categoría 4)
        $this->createProducto([
            'nombre' => 'Tiramisú',
            'descripcion' => 'Postre italiano clásico con café y mascarpone',
            'precio' => 6.99,
            'categoria_id' => 4,
            'stock' => 25,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Cheesecake',
            'descripcion' => 'Tarta de queso con salsa de frutos rojos',
            'precio' => 7.99,
            'categoria_id' => 4,
            'stock' => 20,
            'status' => 'activo',
        ]);

        $this->command->info('✅ Productos de prueba creados correctamente');
    }

    private function createProducto($data)
    {
        Producto::create($data);
    }
}