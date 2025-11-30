<?php
//2025_01_01_000000_create_productos_table.php
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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->decimal('precio', 8, 2);
            $table->text('descripcion')->nullable();
            $table->string('imagen')->nullable();
            $table->string('status')->default('activo');
            $table->integer('stock')->default(0);
            $table->enum('estado_stock', ['disponible', 'agotado'])->default('disponible');
            $table->timestamp('ultima_alerta_stock')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('productos');
    }

};
