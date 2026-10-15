<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Order;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function getOrders()
    {
        $driver = Driver::where('user_id', auth()->id())->first();
        if (!$driver) {
            return response()->json(['success' => false, 'message' => 'Driver profile not found.'], 404);
        }

        $orders = Order::with(['restaurant', 'bus', 'user'])
            ->where('driver_id', $driver->id)
            ->whereIn('order_status', ['pending', 'placed', 'accepted', 'preparing', 'packing', 'ready', 'on_the_way', 'out_for_delivery'])
            ->latest()
            ->get();

        return response()->json(['success' => true, 'orders' => $orders]);
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $driver = Driver::with('bus')->where('user_id', auth()->id())->first();
        if (!$driver || !$driver->bus) {
            return response()->json(['success' => false, 'message' => 'No bus assigned.'], 404);
        }

        $lat = (float) $request->lat;
        $lng = (float) $request->lng;
        $driver->bus->update([
            'current_lat' => $lat,
            'current_lng' => $lng,
            'journey_updated_at' => now(),
        ]);

        $orders = Order::with('restaurant')
            ->where('driver_id', $driver->id)
            ->whereIn('order_status', ['pending', 'placed', 'accepted', 'preparing', 'packing', 'ready', 'on_the_way', 'out_for_delivery'])
            ->get();

        foreach ($orders as $order) {
            $restaurant = $order->restaurant;
            if ($restaurant && $restaurant->latitude !== null && $restaurant->longitude !== null) {
                $distance = self::distanceKm($lat, $lng, (float) $restaurant->latitude, (float) $restaurant->longitude);
                $minutes = (int) max(1, ceil(($distance / 40) * 60));
                $order->driver_location_updated_at = now();
                $order->driver_distance_km = round($distance, 2);
                $order->driver_eta_minutes = $minutes;
                $order->driver_eta_at = now()->addMinutes($minutes);
                $order->save();
            } else {
                $order->driver_location_updated_at = now();
                $order->save();
            }
        }

        return response()->json([
            'success' => true,
            'updated_orders' => $orders->count(),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    private static function distanceKm(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;

        return $earthRadius * 2 * atan2(sqrt($a), sqrt(max(0.0, 1 - $a)));
    }
}
