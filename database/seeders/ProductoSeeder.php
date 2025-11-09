<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run()
    {
        // Limpiar la tabla de productos
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('productos')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Comidas Principales (Categoría 1)
        $this->createProducto([
            'nombre' => 'Sandwich Club Nocturno',
            'descripcion' => 'Triple sandwich con pollo, tocino, jamón, queso, lechuga y tomate',
            'precio' => 13.99,
            'categoria_id' => 1,
            'stock' => 50,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Bagel de Salmón',
            'descripcion' => 'Bagel tostado con salmón ahumado, queso crema, alcaparras y cebolla morada',
            'precio' => 15.99,
            'categoria_id' => 1,
            'stock' => 30,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Croissant Relleno',
            'descripcion' => 'Croissant recién horneado con jamón y queso gratinado',
            'precio' => 8.99,
            'categoria_id' => 1,
            'stock' => 40,
            'status' => 'activo',
        ]);

        // Bebidas (Categoría 2)
        $this->createProducto([
            'nombre' => 'Café Americano',
            'descripcion' => 'Café negro recién preparado con granos premium',
            'precio' => 3.99,
            'categoria_id' => 2,
            'stock' => 100,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Latte Caramelo',
            'descripcion' => 'Espresso con leche vaporizada y jarabe de caramelo',
            'precio' => 4.99,
            'categoria_id' => 2,
            'stock' => 100,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Chocolate Caliente',
            'descripcion' => 'Chocolate belga con leche y crema batida',
            'precio' => 4.50,
            'categoria_id' => 2,
            'stock' => 80,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Té Chai Latte',
            'descripcion' => 'Té negro con especias y leche vaporizada',
            'precio' => 4.50,
            'categoria_id' => 2,
            'stock' => 80,
            'status' => 'activo',
        ]);

        // Acompañamientos (Categoría 3)
        $this->createProducto([
            'nombre' => 'Galletas de la Casa',
            'descripcion' => 'Pack de galletas recién horneadas (4 unidades)',
            'precio' => 5.99,
            'categoria_id' => 3,
            'stock' => 60,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Mini Empanadas',
            'descripcion' => 'Variedad de empanadas de carne y pollo (3 unidades)',
            'precio' => 7.99,
            'categoria_id' => 3,
            'stock' => 40,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Nachos con Queso',
            'descripcion' => 'Nachos crujientes con queso fundido y guacamole',
            'precio' => 8.99,
            'categoria_id' => 3,
            'stock' => 45,
            'status' => 'activo',
        ]);

        // Postres (Categoría 4)
        $this->createProducto([
            'nombre' => 'Tarta de Manzana',
            'descripcion' => 'Tarta casera de manzana con canela y helado de vainilla',
            'precio' => 6.99,
            'categoria_id' => 4,
            'stock' => 25,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Brownie Caliente',
            'descripcion' => 'Brownie de chocolate con helado y salsa de chocolate',
            'precio' => 7.99,
            'categoria_id' => 4,
            'stock' => 30,
            'status' => 'activo',
        ]);

        $this->createProducto([
            'nombre' => 'Cheesecake New York',
            'descripcion' => 'Cheesecake estilo New York con salsa de frutos rojos',
            'precio' => 7.99,
            'categoria_id' => 4,
            'stock' => 20,
            'status' => 'activo',
        ]);

        $this->command->info('✅ Productos creados correctamente');
    }

    private function createProducto($data)
    {
        Producto::create($data);
    }
}