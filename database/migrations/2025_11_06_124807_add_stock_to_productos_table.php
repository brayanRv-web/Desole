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
        Schema::table('productos', function (Blueprint $table) {
            $table->integer('stock')->default(0)->after('precio');
            $table->enum('estado_stock', ['disponible', 'agotado'])->default('disponible')->after('stock');
            
            // También asegurarnos de que el campo estado tenga el valor 'agotado'
            $table->enum('estado', ['activo', 'inactivo', 'agotado'])->default('activo')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropColumn(['stock', 'estado_stock']);
            // Revertir el campo estado a su estado original si es necesario
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->change();
        });
    }
};