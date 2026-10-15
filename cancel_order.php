<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$order = App\Models\Order::find(3);
if ($order) {
    $order->order_status = 'cancelled';
    $order->save();
    echo "Order 3 cancelled.\n";
} else {
    echo "Order 3 not found.\n";
}
