<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usuario empleado por defecto
        User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'employee',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Llamar a los seeders
        $this->call([
            AdminSeeder::class,
            CategoriaSeeder::class,
            ClienteSeeder::class,
            ProductoSeeder::class,
        ]);
    }
}
