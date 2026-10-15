<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$u = App\Models\User::where('mobile', '9999999999')->first();
if ($u) {
    App\Models\Driver::create([
        'user_id' => $u->id,
        'name' => $u->name,
        'mobile' => $u->mobile,
        'status' => 1
    ]);
    echo "Driver created.";
} else {
    echo "User not found.";
}
