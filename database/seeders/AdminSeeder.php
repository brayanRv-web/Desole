<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User; //Se cambio a User
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Administrador',
            'email' => 'admin@desole.com',
            'password' => Hash::make('password123'), // Cambia esta contraseña
            'role' => 'admin', //Cambio a admin a minusculas
            'is_active' => true,  //se agrega este campo para ver si esta activo el usuario
        ]);

        $this->command->info('✅ Administrador creado:');
        $this->command->info('📧 Email: admin@desole.com');
        $this->command->info('🔑 Password: password123');
    }
}