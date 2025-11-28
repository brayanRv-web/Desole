<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categorias', function (Blueprint $table) {
            // Agregar campo estado con valores específicos
            $table->enum('estado', ['activo', 'inactivo'])->default('activo')->after('nombre');
        });

        // Actualizar todas las categorías existentes a estado 'activo'
        DB::table('categorias')->update(['estado' => 'activo']);
    }

    public function down()
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('estado');
        });
    }
};