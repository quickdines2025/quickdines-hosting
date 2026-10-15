<?php
require __DIR__ . "/vendor/autoload.php"; 
$app = require_once __DIR__ . "/bootstrap/app.php"; 
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap(); 

$drivers = \App\Models\Driver::whereNotNull('bus_id')->get(); 
foreach($drivers as $driver) { 
    $bus = \App\Models\Bus::find($driver->bus_id); 
    if ($bus && $driver->user) { 
        $bus->driver_name = $driver->user->name; 
        $bus->driver_phone = $driver->user->mobile; 
        $bus->save(); 
    } 
} 
echo "Synced successfully!";
