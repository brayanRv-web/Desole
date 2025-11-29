<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClienteFactory extends Factory
{
    protected $model = Cliente::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create(['role' => 'cliente'])->id,
            'nombre' => $this->faker->name,
            'telefono' => $this->faker->phoneNumber,
        ];
    }
}