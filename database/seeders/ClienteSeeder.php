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
            'password' => bcrypt('cliente123'),
            'telefono' => '9611234567',
            'direccion' => 'Calle de Prueba #123',
            'colonia' => 'Centro',
            'tipo' => 'registrado',
            'email_verified_at' => now(),
            'recibir_promociones' => true,
            'recibir_cumpleanos' => true,
        ]);
    }
}