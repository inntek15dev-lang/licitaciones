<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "\n📋 LISTADO DE USUARIOS OIEM ABASTIBLE\n";
echo str_repeat("=", 80) . "\n\n";
echo sprintf("%-4s | %-25s | %-30s | %-20s\n", "ID", "NOMBRE", "EMAIL", "ROL");
echo str_repeat("-", 80) . "\n";

foreach(App\Models\User::with('roles')->get() as $u) {
    $rol = $u->roles->pluck('name')->implode(',') ?: 'sin rol';
    echo sprintf("%-4s | %-25s | %-30s | %-20s\n", 
        $u->id, 
        substr($u->name, 0, 25), 
        $u->email, 
        $rol
    );
}
echo "\n";
