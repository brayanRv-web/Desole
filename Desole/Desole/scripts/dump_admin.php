<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::where('email','admin@desole.com')->first();
if (!$u) {
    echo 'NOT_FOUND';
    exit(0);
}

echo "FOUND\n";
echo 'id: ' . $u->id . "\n";
echo 'email: ' . $u->email . "\n";
echo 'password_hash: ' . $u->password . "\n";
echo 'role: ' . $u->role . "\n";
echo 'is_active: ' . ($u->is_active ? '1' : '0') . "\n";

// verify a check
$ok = Illuminate\Support\Facades\Hash::check('password123', $u->password);
echo 'hash_check_password123: ' . ($ok ? 'TRUE' : 'FALSE') . "\n";
$ok2 = Illuminate\Support\Facades\Hash::check('password', $u->password);
echo 'hash_check_password: ' . ($ok2 ? 'TRUE' : 'FALSE') . "\n";
