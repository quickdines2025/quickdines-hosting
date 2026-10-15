<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::first();
if(!$user) {
    $user = App\Models\User::create(['name' => 'John Doe', 'email' => 'john@example.com', 'password' => bcrypt('password')]);
}
App\Models\SupportTicket::create(['ticket_number' => 'TKT1001', 'customer_id' => $user->id, 'issue' => 'Order was late', 'category' => 'Order & Delivery', 'priority' => 'High', 'status' => 'open']);
App\Models\SupportTicket::create(['ticket_number' => 'TKT1002', 'customer_id' => $user->id, 'issue' => 'Refund not received', 'category' => 'Payment', 'priority' => 'High', 'status' => 'in progress']);
App\Models\SupportTicket::create(['ticket_number' => 'TKT1003', 'customer_id' => $user->id, 'issue' => 'App keeps crashing', 'category' => 'Account', 'priority' => 'Medium', 'status' => 'open']);
App\Models\SupportTicket::create(['ticket_number' => 'TKT1004', 'customer_id' => $user->id, 'issue' => 'Missing item from order', 'category' => 'Order & Delivery', 'priority' => 'Low', 'status' => 'resolved']);
echo "Tickets created successfully!\n";
