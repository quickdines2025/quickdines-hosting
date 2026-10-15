<?php

$user = App\Models\User::where('role', 'customer')->first() ?? App\Models\User::first();
$rest = App\Models\Restaurant::first();
$bus = App\Models\Bus::first();

$menu = App\Models\Menu::where('restaurant_id', $rest->id)->first();
if (!$menu) {
    $menu = App\Models\Menu::create([
        'restaurant_id' => $rest->id,
        'name' => 'Test Biryani',
        'description' => 'Test desc',
        'price' => 250,
        'category' => 'Main Course',
        'is_veg' => 0,
        'is_available' => 1
    ]);
}

$order = App\Models\Order::create([
    'user_id' => $user->id,
    'restaurant_id' => $rest->id,
    'bus_id' => $bus ? $bus->id : null,
    'seat_number' => '12A',
    'total_amount' => 250,
    'payment_status' => 'Paid',
    'order_status' => 'Pending'
]);

App\Models\OrderItem::create([
    'order_id' => $order->id,
    'menu_id' => $menu->id,
    'quantity' => 1,
    'price' => 250
]);

echo "Order created with ID: " . $order->id;
