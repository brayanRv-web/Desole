<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('hero_images', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_images', 'imagen')) {
                $table->string('imagen')->nullable();
            }
            if (!Schema::hasColumn('hero_images', 'titulo')) {
                $table->string('titulo')->nullable();
            }
            if (!Schema::hasColumn('hero_images', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }
            if (!Schema::hasColumn('hero_images', 'tipo')) {
                $table->string('tipo')->default('hero'); // hero, banner, etc.
            }
            if (!Schema::hasColumn('hero_images', 'orden')) {
                $table->integer('orden')->default(0);
            }
            if (!Schema::hasColumn('hero_images', 'estado')) {
                $table->boolean('estado')->default(1); // Campo correcto en lugar de 'activo'
            }
            if (!Schema::hasColumn('hero_images', 'enlace')) {
                $table->string('enlace')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $cols = [];
            foreach (['imagen','titulo','descripcion','tipo','orden','estado','enlace'] as $c) {
                if (Schema::hasColumn('hero_images', $c)) $cols[] = $c;
            }
            if (!empty($cols)) {
                $table->dropColumn($cols);
            }
        });
    }
};