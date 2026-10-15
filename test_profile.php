<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/vendor/profile', 'POST', [
    'restaurant_name' => 'Paradise Hotel Updated',
    'mobile' => '7777777777',
    'address' => 'Test Address',
    'opening_time' => '10:00',
    'closing_time' => '22:00',
    'cuisines' => 'Test Cuisine',
]);

$controller = new App\Http\Controllers\Vendor\PageController();
Auth::loginUsingId(3); // assuming user id 3 is Paradise Hotel
try {
    $response = $controller->updateProfile($request);
    var_dump($response->getStatusCode());
    var_dump(session()->all());
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
