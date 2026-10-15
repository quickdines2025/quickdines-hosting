<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Transaction;

class OrderController extends Controller
{
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string|in:preparing,packing,ready,on_the_way,out_for_delivery,delivered,completed,cancelled,bus_arrived'
        ]);

        $restaurant = Restaurant::where('user_id', auth()->id())->first();
        if (!$restaurant) return redirect()->back();

        $order = Order::where('restaurant_id', $restaurant->id)->findOrFail($id);

        $oldStatus = $order->order_status;
        $order->order_status = $request->status;
        $order->save();

        if (in_array($request->status, ['delivered', 'completed']) && !in_array($oldStatus, ['delivered', 'completed'])) {
            // No driver commission is credited. All money goes to the vendor wallet only.
        }

        $previousUrl = strtok(url()->previous(), '#');
        return redirect($previousUrl . '#order-' . $order->id)->with('success', 'Order status updated to ' . $request->status);
    }
    public function storeManual(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantity' => 'required|integer|min:1',
            'seat_number' => 'nullable|string',
            'payment_method' => 'required|in:online,upi,cod'
        ]);

        $restaurant = Restaurant::where('user_id', auth()->id())->first();
        if (!$restaurant) return redirect()->back()->with('error', 'Restaurant profile required');

        $menu = \App\Models\Menu::where('restaurant_id', $restaurant->id)->findOrFail($request->menu_id);
        $totalAmount = $menu->price * $request->quantity;

        // Create Order
        $order = Order::create([
            'user_id' => \App\Models\User::where('role', 'customer')->first()->id ?? auth()->id(),
            'restaurant_id' => $restaurant->id,
            'seat_number' => $request->seat_number ?? '1A',
            'total_amount' => $totalAmount,
            'payment_status' => 'Pending',
            'order_status' => 'pending',
            'payment_method' => $request->payment_method
        ]);

        \App\Models\OrderItem::create([
            'order_id' => $order->id,
            'menu_id' => $menu->id,
            'quantity' => $request->quantity,
            'price' => $menu->price
        ]);

        return redirect()->back()->with('success', 'Manual test order created successfully!');
    }

    public function markPaid($id)
    {
        $restaurant = Restaurant::where('user_id', auth()->id())->first();
        if (!$restaurant) return redirect()->back()->with('error', 'Restaurant profile required');

        $order = Order::where('restaurant_id', $restaurant->id)->findOrFail($id);

        if (strtolower($order->payment_status) !== 'paid') {
            $order->payment_status = 'Paid';

            if ($order->order_status === 'payment_pending') {
                $order->order_status = 'pending';
            }

            $order->save();
            
            // Only credit digital wallet if payment was made online
            if ($order->payment_method != 'cod') {
                $restaurant->wallet_balance += $order->total_amount;
                $restaurant->save();

                Transaction::create([
                    'order_id' => $order->id,
                    'user_type' => 'vendor',
                    'user_id' => $restaurant->user_id,
                    'amount' => $order->total_amount,
                    'type' => 'credit',
                    'status' => 'completed',
                    'payment_method' => 'wallet'
                ]);
            }
        }

        return redirect()->back()->with('success', 'Payment marked as Paid successfully!');
    }
    public function printKot($id)
    {
        $restaurant = Restaurant::where('user_id', auth()->id())->first();

        if (!$restaurant) {
            abort(403, 'Restaurant profile required.');
        }

        $order = Order::with([
            'items.menuItem',
            'bus',
            'bus.busRoute',
            'user'
        ])
            ->where('restaurant_id', $restaurant->id)
            ->findOrFail($id);


        return view('vendor.kot', compact('order', 'restaurant'));
    }
}
