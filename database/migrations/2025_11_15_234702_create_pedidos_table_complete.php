<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('pedidos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('cliente_id')->constrained()->onDelete('cascade');
        $table->decimal('total', 10, 2);
        $table->string('estado')->default('pendiente'); // Esta es la columna importante
        $table->string('tiempo_estimado')->nullable()->comment('Tiempo estimado (ej. 15 min)');
        $table->boolean('stock_descontado')->default(false);
        $table->text('notas')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('pedidos');
}
};
