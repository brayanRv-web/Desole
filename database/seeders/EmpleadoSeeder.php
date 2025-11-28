<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class EmpleadoSeeder extends Seeder
{
    public function run()
    {
        Admin::create([
            'name' => 'Empleado',
            'email' => 'empleado@desole.com',
            'password' => Hash::make('empleado123'),
            'role' => 'Empleado',
        ]);

        $this->command->info('Empleado creado: empleado@desole.com / empleado123');
    }
}
