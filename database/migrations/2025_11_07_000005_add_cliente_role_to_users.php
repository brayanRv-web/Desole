<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support changing enum types, so we need to recreate the column
        if (config('database.default') === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role_temp')->default('employee');
            });

            DB::statement('UPDATE users SET role_temp = role');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
                $table->enum('role', ['admin', 'employee', 'cliente'])->default('employee');
            });

            DB::statement('UPDATE users SET role = role_temp');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role_temp');
            });
        } else {
            // For MySQL we can alter the enum directly
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'employee', 'cliente') DEFAULT 'employee'");
        }
    }

    public function down(): void
    {
        // SQLite doesn't support changing enum types, so we need to recreate the column
        if (config('database.default') === 'sqlite') {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role_temp')->default('employee');
            });

            DB::statement('UPDATE users SET role_temp = role');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
                $table->enum('role', ['admin', 'employee'])->default('employee');
            });

            DB::statement('UPDATE users SET role = role_temp');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role_temp');
            });
        } else {
            // For MySQL we can alter the enum directly
            DB::statement("ALTER TABLE users MODIFY role ENUM('admin', 'employee') DEFAULT 'employee'");
        }
    }
};