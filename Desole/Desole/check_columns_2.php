<?php

use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Columns in categorias table:\n";
print_r(Schema::getColumnListing('categorias'));

echo "\nColumns in productos table:\n";
print_r(Schema::getColumnListing('productos'));
