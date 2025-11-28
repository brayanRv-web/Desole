<?php
// database/migrations/2024_01_01_000001_add_cliente_id_to_pedidos_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (!Schema::hasColumn('pedidos', 'cliente_id')) {
                $table->foreignId('cliente_id')
                      ->nullable()
                      ->constrained('clientes')
                      ->onDelete('set null');
            }
        });
    }

    public function down()
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'cliente_id')) {
                try {
                    $table->dropForeign(['cliente_id']);
                } catch (\Throwable $e) {
                    // ignorar si no existe la FK con ese nombre
                }
                $table->dropColumn('cliente_id');
            }
        });
    }
};