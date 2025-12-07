<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear el administrador principal en la tabla admins
        Admin::updateOrCreate(
            ['email' => 'admin@desole.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password123'),
                'is_active' => true,
            ]
        );

        // Crear un empleado en la tabla users
        /*User::updateOrCreate(
            ['email' => 'empleado@desole.com'],
            [
                'name' => 'Empleado',
                'password' => Hash::make('password123'),
                'role' => 'employee',
                'is_active' => true,
            ]
        );*/

        $this->command->info('✅ Administrador creado:');
        $this->command->info('📧 Email: admin@desole.com');
        $this->command->info('🔑 Password: password123');
    }
}