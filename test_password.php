<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::find(3); // Paradise Hotel user
$oldPasswordHash = $user->password;

$request = Illuminate\Http\Request::create('/vendor/profile/password', 'POST', [
    'current_password' => 'password', // Assuming this is wrong or right
    'new_password' => 'newpassword123',
    'new_password_confirmation' => 'newpassword123',
]);

$controller = new App\Http\Controllers\Vendor\PageController();
Auth::loginUsingId(3);
try {
    $response = $controller->updatePassword($request);
    var_dump($response->getStatusCode());
    var_dump(session()->all());
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
