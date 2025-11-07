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
        Schema::table('hero_images', function (Blueprint $table) {
            $table->string('imagen')->after('id');
            $table->string('titulo')->nullable()->after('imagen');
            $table->string('subtitulo')->nullable()->after('titulo');
            $table->integer('orden')->default(0)->after('subtitulo');
            $table->boolean('activo')->default(true)->after('orden');
            $table->string('tipo')->default('hero')->after('activo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hero_images', function (Blueprint $table) {
            $table->dropColumn(['imagen', 'titulo', 'subtitulo', 'orden', 'activo', 'tipo']);
        });
    }
};
