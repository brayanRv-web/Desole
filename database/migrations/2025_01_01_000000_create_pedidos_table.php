<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->string('cliente_nombre')->nullable();
            $table->string('cliente_telefono')->nullable();
            $table->text('direccion')->nullable();
            $table->decimal('total', 10, 2)->default(0);
            $table->string('status')->default('pendiente'); // pendiente, preparando, listo, entregado, cancelado
            $table->json('items')->nullable()->comment('Lista JSON de ítems: [{"nombre":"...","cantidad":1,"precio":...}]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
