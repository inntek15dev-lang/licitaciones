<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$u = App\Models\User::where('email', 'DEMO2@DEMO2.COM')->first();
if($u) { 
    $u->delete(); 
    echo 'User Deleted'; 
} else {
    echo 'User not found';
}
