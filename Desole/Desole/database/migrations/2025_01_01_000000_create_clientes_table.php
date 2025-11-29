<?php
// database/migrations/2025_01_01_000000_create_clientes_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            //Datos basicos (comunes a ambos)
            $table->string('nombre');
            $table->string('telefono')->nullable()->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password')->nullable();

            //Datos de contacto
            $table->text('direccion')->nullable();
            $table->string('colonia')->nullable();
            $table->string('referencias')->nullable();

            //Datos personales
            $table->date('fecha_nacimiento')->nullable();
            $table->text('alergias')->nullable();
            $table->text('preferencias')->nullable();

            //Sistema de autenticacion
            $table->enum('tipo', ['registrado', 'anonimo'])->default('anonimo');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();

            //Programas de fidelidad
            $table->integer('puntos_fidelidad')->default(0);
            $table->integer('nivel_fidelidad')->default(1);

            //Estadisticas
            $table->integer('total_pedidos')->default(0);
            $table->decimal('total_gastado', 10, 2)->default(0);
            $table->date('ultima_visita')->nullable();
            $table->date('primera_visita')->nullable();

            //Preferencias marketing
            $table->boolean('recibir_promociones')->default(true);
            $table->boolean('recibir_cumpleanos')->default(true);
            $table->text('notas')->nullable();
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};