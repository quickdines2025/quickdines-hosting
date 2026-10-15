<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$b = App\Models\Bus::find(1);
$b->bus_route_id = 3;
$b->driver_name = 'Ramesh Kumar';
$b->driver_phone = '+91 98765 43210';
$b->save();
echo "Done\n";
