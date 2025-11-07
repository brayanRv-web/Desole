<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->string('imagen')->nullable();
            $table->string('titulo')->nullable();
            $table->text('descripcion')->nullable();
            $table->string('tipo')->default('hero'); // hero, banner, etc.
            $table->integer('orden')->default(0);
            $table->boolean('estado')->default(1); // Campo correcto en lugar de 'activo'
            $table->string('enlace')->nullable();
        });
    }

    public function down()
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropColumn([
                'imagen', 
                'titulo', 
                'descripcion', 
                'tipo', 
                'orden', 
                'estado', 
                'enlace'
            ]);
        });
    }
};