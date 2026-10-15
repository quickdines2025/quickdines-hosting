<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;

class TrackingController extends Controller
{
    public function order($id)
    {
        $restaurant = auth()->user()->restaurant;
        if (!$restaurant) {
            return response()->json(['success' => false, 'message' => 'Restaurant profile not found.'], 404);
        }

        $order = Order::with(['driver.user', 'bus', 'restaurant'])
            ->where('restaurant_id', $restaurant->id)
            ->findOrFail($id);

        $driver = $order->driver;
        $bus = $order->bus;

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'status' => $order->order_status,
            'driver_assigned' => (bool) $driver,
            'driver' => $driver ? [
                'name' => optional($driver->user)->name ?? $driver->name ?? 'Driver',
                'mobile' => optional($driver->user)->mobile ?? $driver->mobile ?? null,
            ] : null,
            'location' => ($bus && $bus->current_lat !== null && $bus->current_lng !== null) ? [
                'lat' => (float) $bus->current_lat,
                'lng' => (float) $bus->current_lng,
                'updated_at' => optional($order->driver_location_updated_at)->toIso8601String() ?? optional($bus->journey_updated_at)->toIso8601String(),
            ] : null,
            'distance_km' => $order->driver_distance_km !== null ? (float) $order->driver_distance_km : null,
            'eta_minutes' => $order->driver_eta_minutes,
            'eta_at' => optional($order->driver_eta_at)->toIso8601String(),
        ]);
    }
}
