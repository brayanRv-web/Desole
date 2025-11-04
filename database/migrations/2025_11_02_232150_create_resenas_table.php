<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('resenas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');           
            $table->string('email')->nullable(); 
            $table->integer('calificacion');    
            $table->text('comentario');         
            
            // ✅ AÑADIR DIRECTAMENTE AQUÍ LOS NUEVOS CAMPOS
            $table->foreignId('cliente_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('tipo_cliente')->default('anonimo');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('resenas');
    }
};