<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cliente::create([
            'nombre' => 'Cliente Prueba',
            'email' => 'cliente@test.com',
            'telefono' => '9611234567',
            'password' => bcrypt('cliente123'),
            'direccion' => 'Calle de Prueba #123',
            'tipo' => 'registrado',
            'notas' => 'Cliente creado desde el seeder.',
            'total_pedidos' => 0,
            'ultima_visita' => now(),
        ]);
    }
}