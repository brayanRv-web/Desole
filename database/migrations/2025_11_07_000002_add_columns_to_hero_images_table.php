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
        if (!Schema::hasTable('hero_images')) {
            // Si por alguna razón la tabla no existe, crearla con las columnas mínimas
            Schema::create('hero_images', function (Blueprint $table) {
                $table->id();
                $table->string('imagen')->nullable();
                $table->string('titulo')->nullable();
                $table->string('subtitulo')->nullable();
                $table->integer('orden')->default(0);
                $table->boolean('activo')->default(true);
                $table->string('tipo')->default('hero');
                $table->timestamps();
            });
            return;
        }

        Schema::table('hero_images', function (Blueprint $table) {
            if (!Schema::hasColumn('hero_images', 'imagen')) {
                $table->string('imagen')->nullable()->after('id');
            }
            if (!Schema::hasColumn('hero_images', 'titulo')) {
                $table->string('titulo')->nullable()->after('imagen');
            }
            if (!Schema::hasColumn('hero_images', 'subtitulo')) {
                $table->string('subtitulo')->nullable()->after('titulo');
            }
            if (!Schema::hasColumn('hero_images', 'orden')) {
                $table->integer('orden')->default(0)->after('subtitulo');
            }
            if (!Schema::hasColumn('hero_images', 'activo')) {
                $table->boolean('activo')->default(true)->after('orden');
            }
            if (!Schema::hasColumn('hero_images', 'tipo')) {
                $table->string('tipo')->default('hero')->after('activo');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('hero_images')) return;

        Schema::table('hero_images', function (Blueprint $table) {
            if (Schema::hasColumn('hero_images', 'tipo')) {
                $table->dropColumn('tipo');
            }
            if (Schema::hasColumn('hero_images', 'activo')) {
                $table->dropColumn('activo');
            }
            if (Schema::hasColumn('hero_images', 'orden')) {
                $table->dropColumn('orden');
            }
            if (Schema::hasColumn('hero_images', 'subtitulo')) {
                $table->dropColumn('subtitulo');
            }
            if (Schema::hasColumn('hero_images', 'titulo')) {
                $table->dropColumn('titulo');
            }
            if (Schema::hasColumn('hero_images', 'imagen')) {
                $table->dropColumn('imagen');
            }
        });
    }
};
