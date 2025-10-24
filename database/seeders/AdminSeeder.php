<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Administrador',
            'email' => 'prueba@desole.com',
            'password' => Hash::make('admin123'), // Cambia esta contraseña
            'role' => 'Administrador',
        ]);

        $this->command->info('✅ Administrador creado:');
        $this->command->info('📧 Email: prueba@desole.com');
        $this->command->info('🔑 Password: admin123');
    }
}