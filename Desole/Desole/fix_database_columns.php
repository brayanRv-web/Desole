<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function standardizeStatus($table) {
    echo "Checking table: $table\n";
    if (Schema::hasColumn($table, 'estado') && !Schema::hasColumn($table, 'status')) {
        echo "Renaming 'estado' to 'status' in $table...\n";
        
        // Add status column
        Schema::table($table, function (Blueprint $table) {
             // We don't know the exact type easily, but string is safe for 'activo'/'inactivo'
             // However, if it was enum, string is also fine.
             $table->string('status')->default('activo')->after('estado');
        });
        
        // Copy data
        DB::statement("UPDATE $table SET status = estado");
        
        // Drop estado
        Schema::table($table, function (Blueprint $table) {
            $table->dropColumn('estado');
        });
        
        echo "Done for $table.\n";
    } elseif (Schema::hasColumn($table, 'status')) {
        echo "$table already has 'status'.\n";
    } else {
        echo "$table has neither 'estado' nor 'status'.\n";
    }
}

standardizeStatus('categorias');
standardizeStatus('productos');
standardizeStatus('promociones');
standardizeStatus('hero_images');
standardizeStatus('horarios');
standardizeStatus('pedidos');
