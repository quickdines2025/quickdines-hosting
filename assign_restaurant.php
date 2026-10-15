<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$driver = App\Models\Driver::first();
$bus = $driver ? $driver->bus : null;
if($bus) {
    // detach all first to reset
    $bus->restaurants()->detach();
    // attach 2 restaurants to simulate new ones
    $rests = App\Models\Restaurant::take(2)->get();
    foreach($rests as $rest) {
        $bus->restaurants()->attach($rest->id);
        echo 'Attached restaurant ' . $rest->id . ' to bus ' . $bus->id . "\n";
    }
}
