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
        if (!Schema::hasColumn('users', 'role')) {
            $table->enum('role', ['admin', 'employee'])->default('employee');
        }

        if (!Schema::hasColumn('users', 'is_active')) {
            $table->boolean('is_active')->default(true);
        }

        if (!Schema::hasColumn('users', 'phone')) {
            $table->string('phone', 20)->nullable();
        }

        if (!Schema::hasColumn('users', 'address')) {
            $table->text('address')->nullable();
        }
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