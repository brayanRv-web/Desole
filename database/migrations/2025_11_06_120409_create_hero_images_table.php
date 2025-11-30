<?php

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
        // Hacer idempotente: solo crear si no existe la tabla (evita errores en entornos con tabla manual)
        if (!Schema::hasTable('hero_images')) {
            Schema::create('hero_images', function (Blueprint $table) {
                $table->id();
                $table->string('imagen')->nullable();
                $table->string('titulo')->nullable();
                $table->string('subtitulo')->nullable();
                $table->text('descripcion')->nullable();
                $table->string('tipo')->default('hero');
                $table->integer('orden')->default(0);
                $table->string('enlace')->nullable();
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_images');
    }
};
