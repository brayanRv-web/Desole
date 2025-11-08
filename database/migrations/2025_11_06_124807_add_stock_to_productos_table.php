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
            // Agregar columnas solo si no existen (idempotente)
            if (!Schema::hasColumn('productos', 'stock')) {
                $table->integer('stock')->default(0)->after('precio');
            }

            if (!Schema::hasColumn('productos', 'estado_stock')) {
                $table->enum('estado_stock', ['disponible', 'agotado'])->default('disponible')->after('stock');
            }

            // Intentar actualizar el enum 'estado' solo si la columna existe. Algunos drivers requieren doctrine/dbal para change().
            if (Schema::hasColumn('productos', 'estado')) {
                try {
                    $table->enum('estado', ['activo', 'inactivo', 'agotado'])->default('activo')->change();
                } catch (\Throwable $e) {
                    // Ignorar si no es posible cambiar (ej. falta doctrine/dbal) y continuar
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('productos', function (Blueprint $table) {
            if (Schema::hasColumn('productos', 'stock')) {
                $table->dropColumn('stock');
            }
            if (Schema::hasColumn('productos', 'estado_stock')) {
                $table->dropColumn('estado_stock');
            }

            if (Schema::hasColumn('productos', 'estado')) {
                try {
                    $table->enum('estado', ['activo', 'inactivo'])->default('activo')->change();
                } catch (\Throwable $e) {
                    // Ignorar si no se puede revertir el enum
                }
            }
        });
    }
};