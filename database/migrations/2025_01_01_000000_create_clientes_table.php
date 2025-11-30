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
            $table->string('nombre');
            $table->string('telefono')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('direccion')->nullable();
            $table->enum('tipo', ['registrado', 'anonimo'])->default('registrado');
            $table->text('notas')->nullable();
            $table->integer('total_pedidos')->default(0);
            $table->date('ultima_visita')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clientes');
    }
};