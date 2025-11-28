<?php
// database/migrations/2025_10_18_055026_add_is_active_to_admins_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ejecuta la migración: agrega la columna 'is_active' a la tabla 'admins'.
     */
    public function up(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Agrega columna 'is_active' tipo boolean con valor por defecto true
            $table->boolean('is_active')->default(true);
        });
    }

    /**
     * Revierte la migración: elimina la columna 'is_active'.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            // Elimina la columna 'is_active'
            $table->dropColumn('is_active');
        });
    }
};
