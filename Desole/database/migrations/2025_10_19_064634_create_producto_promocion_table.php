<?php
//2025_10_19_064634_create_producto_promocion_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('producto_promocion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('promocion_id')
                  ->constrained('promociones')
                  ->onDelete('cascade');
            $table->foreignId('producto_id')
                  ->constrained('productos')
                  ->onDelete('cascade');
            $table->timestamps();

            // Evitar duplicados
            $table->unique(['promocion_id', 'producto_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('producto_promocion');
    }
};