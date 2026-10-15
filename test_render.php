<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);
try {
    $user = App\Models\User::with(['orders' => function($q) {
        $q->with('restaurant', 'bus.busRoute')->orderBy('created_at', 'desc');
    }])->find(12);
    if (!$user) {
        echo "User not found\n";
        exit;
    }
    echo view('admin.users.show', compact('user'))->render();
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
