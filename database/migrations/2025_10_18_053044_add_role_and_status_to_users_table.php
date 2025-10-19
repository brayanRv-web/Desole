<?php
// database/migrations/2024_01_01_000000_add_role_and_status_to_users_table.php

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
        Schema::table('users', function (Blueprint $table) {
            // Agregar campo role (admin, employee)
            $table->enum('role', ['admin', 'employee'])->default('employee');
            
            // Agregar campo is_active (boolean)
            $table->boolean('is_active')->default(true);
            
            // Agregar campo phone (opcional)
            $table->string('phone', 20)->nullable();
            
            // Agregar campo address (opcional)
            $table->text('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar los campos en caso de rollback
            $table->dropColumn(['role', 'is_active', 'phone', 'address']);
        });
    }
};