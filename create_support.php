<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = \App\Models\User::where('email', 'support@quickdines.com')->first();
if (!$user) {
    $user = new \App\Models\User();
}
$user->name = 'Support Manager';
$user->email = 'support@quickdines.com';
$user->mobile = '9876543210';
$user->password = bcrypt('password');
$user->role = 'support';
$user->save();
echo "Support user created/updated successfully.";
