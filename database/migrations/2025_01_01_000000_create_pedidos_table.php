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
        $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
        $table->string('cliente_nombre')->nullable();
        $table->string('cliente_telefono')->nullable();
        $table->text('direccion')->nullable();
        $table->decimal('total', 10, 2)->default(0);
        $table->json('items')->nullable()->comment('Lista JSON de ítems: [{"nombre":"...","cantidad":1,"precio":...}]');
        $table->string('estado')->default('pendiente'); // pendiente, preparando, listo, entregado, cancelado
        $table->string('tiempo_estimado')->nullable()->comment('Tiempo estimado (ej. 15 min)');
        $table->boolean('stock_descontado')->default(false);
        $table->text('notas')->nullable();
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
