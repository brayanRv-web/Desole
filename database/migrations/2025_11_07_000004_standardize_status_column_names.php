<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Productos table
        if (Schema::hasColumn('productos', 'estado')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('status')->default('activo')->after('estado');
            });
            DB::statement('UPDATE productos SET status = estado');
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }

        // Hero Images table
        if (Schema::hasColumn('hero_images', 'estado')) {
            Schema::table('hero_images', function (Blueprint $table) {
                $table->boolean('status')->default(true)->after('estado');
            });
            DB::statement('UPDATE hero_images SET status = estado');
            Schema::table('hero_images', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }

        // Categorias table
        if (Schema::hasColumn('categorias', 'estado')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->string('status')->default('activo')->after('estado');
            });
            DB::statement('UPDATE categorias SET status = estado');
            Schema::table('categorias', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }

        // Promociones table
        if (Schema::hasColumn('promociones', 'estado')) {
            Schema::table('promociones', function (Blueprint $table) {
                $table->string('status')->default('activo')->after('estado');
            });
            DB::statement('UPDATE promociones SET status = estado');
            Schema::table('promociones', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }

        // Horarios table
        if (Schema::hasColumn('horarios', 'estado')) {
            Schema::table('horarios', function (Blueprint $table) {
                $table->string('status')->default('activo')->after('estado');
            });
            DB::statement('UPDATE horarios SET status = estado');
            Schema::table('horarios', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }

        // Double check pedidos table just in case
        if (Schema::hasColumn('pedidos', 'estado') && !Schema::hasColumn('pedidos', 'status')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->string('status')->default('pendiente')->after('estado');
            });
            DB::statement('UPDATE pedidos SET status = estado');
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropColumn('estado');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Productos table
        if (!Schema::hasColumn('productos', 'estado')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('estado')->default('activo')->after('descripcion');
            });
            DB::statement('UPDATE productos SET estado = status');
            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        // Hero Images table
        if (!Schema::hasColumn('hero_images', 'estado')) {
            Schema::table('hero_images', function (Blueprint $table) {
                $table->boolean('estado')->default(true)->after('orden');
            });
            DB::statement('UPDATE hero_images SET estado = status');
            Schema::table('hero_images', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        // Categorias table
        if (!Schema::hasColumn('categorias', 'estado')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->string('estado')->default('activo');
            });
            DB::statement('UPDATE categorias SET estado = status');
            Schema::table('categorias', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        // Promociones table
        if (!Schema::hasColumn('promociones', 'estado')) {
            Schema::table('promociones', function (Blueprint $table) {
                $table->string('estado')->default('activo');
            });
            DB::statement('UPDATE promociones SET estado = status');
            Schema::table('promociones', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        // Horarios table
        if (!Schema::hasColumn('horarios', 'estado')) {
            Schema::table('horarios', function (Blueprint $table) {
                $table->string('estado')->default('activo');
            });
            DB::statement('UPDATE horarios SET estado = status');
            Schema::table('horarios', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }

        // Pedidos table
        if (Schema::hasColumn('pedidos', 'status') && !Schema::hasColumn('pedidos', 'estado')) {
            Schema::table('pedidos', function (Blueprint $table) {
                $table->string('estado')->default('pendiente');
            });
            DB::statement('UPDATE pedidos SET estado = status');
            Schema::table('pedidos', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};