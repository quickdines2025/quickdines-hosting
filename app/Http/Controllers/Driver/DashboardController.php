<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Driver;

class DashboardController extends Controller
{
    public function checkNotifications()
    {
        $user = auth()->user();
        if (!$user) return response()->json(['messages' => 0, 'new_orders' => 0]);

        $newMessagesCount = \App\Models\Message::where('user_id', $user->id)
                                ->where('is_admin', true)
                                ->where('is_read', false)
                                ->count();
                                
        $newOrders = \App\Models\Order::where('driver_id', null)
                                ->where('order_status', 'ready')
                                ->count(); // Just an example of something a driver might get notified for

        return response()->json([
            'messages' => $newMessagesCount,
            'new_orders' => $newOrders,
            'total' => $newMessagesCount + $newOrders
        ]);
    }

    public function index()
    {
        $driver = Driver::with(['bus.busRoute.stops' => function($q) {
            $q->orderBy('stop_sequence');
        }])->where('user_id', auth()->id())->first();
        if (!$driver) return redirect()->route('dashboard')->with('error', 'Driver profile not found.');

        // Get orders assigned to the driver's bus
        $orders = Order::with(['restaurant', 'user'])
            ->where('bus_id', $driver->bus_id)
            ->whereIn('order_status', ['placed', 'preparing', 'ready', 'out_for_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        $completedOrdersCount = Order::where('bus_id', $driver->bus_id)->where('order_status', 'delivered')->count();
        $todayDeliveries = Order::where('bus_id', $driver->bus_id)->whereDate('created_at', now()->toDateString())->count();
        $todayEarnings = Order::where('bus_id', $driver->bus_id)->where('order_status', 'delivered')->whereDate('created_at', now()->toDateString())->sum('total_amount') * 0.1; // Assuming 10% commission

        // Get assigned hotels (restaurants)
        $assignedHotels = [];
        if ($driver->bus) {
            $assignedHotels = $driver->bus->restaurants()->withPivot('id')->get();
            $delayMins = $driver->bus->journey_delay_minutes ?? 0;
            $stops = $driver->bus->busRoute ? $driver->bus->busRoute->stops : collect([]);
            
            foreach ($assignedHotels as $hotel) {
                // Calculate active orders
                $hotel->active_orders_count = Order::where('bus_id', $driver->bus_id)
                    ->where('restaurant_id', $hotel->id)
                    ->whereIn('order_status', ['placed', 'preparing', 'ready', 'out_for_delivery'])
                    ->count();
                
                // Calculate reaching time (ETA)
                $reachingTime = 'N/A';
                if ($stops->count() > 0) {
                    $matchedStop = null;
                    foreach ($stops as $stop) {
                        if (stripos($hotel->address, $stop->stop_name) !== false || stripos($hotel->restaurant_name, $stop->stop_name) !== false) {
                            $matchedStop = $stop;
                            break;
                        }
                    }
                    if ($matchedStop && $matchedStop->arrival_time) {
                        $reachingTime = \Carbon\Carbon::parse($matchedStop->arrival_time)->addMinutes($delayMins)->format('h:i A');
                    } else if ($stops->last()->arrival_time) {
                        $reachingTime = \Carbon\Carbon::parse($stops->last()->arrival_time)->addMinutes($delayMins)->format('h:i A');
                    }
                }
                $hotel->reaching_time = $reachingTime;
            }
        }

        $stats = [
            'route' => $driver->bus ? ($driver->bus->route_from . ' → ' . $driver->bus->route_to) : 'Not Assigned',
            'today_earnings' => $todayEarnings > 0 ? $todayEarnings : 0,
            'today_deliveries' => $todayDeliveries,
            'completed_deliveries' => $completedOrdersCount,
        ];

        return view('driver.dashboard', compact('driver', 'orders', 'stats', 'assignedHotels'));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180'
        ]);

        $driver = Driver::with('bus')->where('user_id', auth()->id())->first();
        if (!$driver || !$driver->bus) {
            return response()->json(['success' => false, 'message' => 'No bus assigned.'], 404);
        }

        $lat = (float) $request->lat;
        $lng = (float) $request->lng;
        $bus = $driver->bus;
        $bus->current_lat = $lat;
        $bus->current_lng = $lng;
        $bus->journey_updated_at = now();
        $bus->save();

        // Update ETA for every active order assigned to this driver.
        $orders = Order::with('restaurant')
            ->where('driver_id', $driver->id)
            ->whereIn('order_status', ['pending', 'placed', 'accepted', 'preparing', 'packing', 'ready', 'on_the_way', 'out_for_delivery'])
            ->get();

        foreach ($orders as $order) {
            $eta = self::calculateEta($lat, $lng, optional($order->restaurant)->latitude, optional($order->restaurant)->longitude);
            $order->driver_location_updated_at = now();
            if ($eta) {
                $order->driver_distance_km = $eta['distance_km'];
                $order->driver_eta_minutes = $eta['minutes'];
                $order->driver_eta_at = now()->addMinutes($eta['minutes']);
            }
            $order->save();
        }

        return response()->json([
            'success' => true,
            'updated_orders' => $orders->count(),
            'updated_at' => now()->toIso8601String(),
        ]);
    }

    /**
     * Lightweight ETA estimate. Road-aware routing can be plugged in later
     * without changing the driver/restaurant tracking flow.
     */
    private static function calculateEta($fromLat, $fromLng, $toLat, $toLng): ?array
    {
        if ($toLat === null || $toLng === null) {
            return null;
        }

        $earthRadius = 6371;
        $lat1 = deg2rad((float) $fromLat);
        $lat2 = deg2rad((float) $toLat);
        $dLat = deg2rad((float) $toLat - (float) $fromLat);
        $dLng = deg2rad((float) $toLng - (float) $fromLng);
        $a = sin($dLat / 2) ** 2 + cos($lat1) * cos($lat2) * sin($dLng / 2) ** 2;
        $distance = $earthRadius * 2 * atan2(sqrt($a), sqrt(max(0.0, 1 - $a)));

        // Conservative city/bus average speed. Final road ETA can later use a routing API.
        $averageSpeedKmh = 40;
        $minutes = (int) max(1, ceil(($distance / $averageSpeedKmh) * 60));

        return ['distance_km' => round($distance, 2), 'minutes' => $minutes];
    }

    public function updateJourneyStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|string|in:Started,Reaching Soon,10 Mins Away,Reached,Problem Report,Completed'
        ]);

        $driver = Driver::with('bus')->where('user_id', auth()->id())->first();
        if ($driver && $driver->bus) {
            $driver->bus->journey_status = $request->status;
            $driver->bus->journey_updated_at = now();

            if ($request->status === 'Started') {
                $driver->bus->journey_start_time = now();
                $driver->bus->journey_delay_minutes = 0;
                $driver->bus->current_stop_id = null;
            }

            $driver->bus->save();
            return response()->json(['success' => true, 'status' => $request->status, 'updated_at' => now()->format('h:i A')]);
        }
        
        return response()->json(['success' => false, 'message' => 'No bus assigned.'], 404);
    }

    public function reachStop(Request $request)
    {
        $request->validate([
            'stop_id' => 'required|exists:bus_route_stops,id'
        ]);

        $driver = Driver::with('bus')->where('user_id', auth()->id())->first();

        if ($driver && $driver->bus) {
            $bus = $driver->bus;

            $stop = \App\Models\BusRouteStop::findOrFail($request->stop_id);
            $lastStop = \App\Models\BusRouteStop::where('bus_route_id', $bus->bus_route_id)
                ->orderByDesc('stop_sequence')
                ->first();

            if (!$lastStop || $stop->bus_route_id != $bus->bus_route_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid stop for this bus route.'
                ], 422);
            }

            if ($stop->id == $lastStop->id) {
                $bus->current_stop_id = null;
                $bus->journey_status = 'Completed';
                $bus->journey_updated_at = now();
            } else {
                $bus->current_stop_id = $stop->id;
                $bus->journey_status = 'Started';
                $bus->journey_updated_at = now();
            }

            $bus->save();

            return response()->json([
                'success' => true,
                'completed' => $stop->id == $lastStop->id
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No bus assigned.'
        ], 404);
    }

    public function reportDelay(Request $request)
    {
        $request->validate([
            'minutes' => 'required|integer|min:1',
            'reason' => 'nullable|string',
            'type' => 'nullable|in:delay,early'
        ]);

        $driver = Driver::with('bus')->where('user_id', auth()->id())->first();
        if ($driver && $driver->bus) {
            $mins = $request->minutes;
            if ($request->type === 'early') {
                $driver->bus->journey_delay_minutes = -$mins;
                $reason = $request->reason ? $request->reason : 'Driver Reported Early Arrival';
                $driver->bus->journey_status = 'Early: ' . $reason;
            } else {
                $driver->bus->journey_delay_minutes = $mins;
                $reason = $request->reason ? $request->reason : 'Driver Reported Delay';
                $driver->bus->journey_status = 'Delayed: ' . $reason;
            }
            $driver->bus->journey_updated_at = now();
            $driver->bus->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'No bus assigned.'], 404);
    }
}

