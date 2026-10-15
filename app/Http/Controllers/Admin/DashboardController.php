<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Restaurant;
use App\Models\Bus;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();

        // Date Filtering Logic
        $thisWeekStart = Carbon::now()->startOfWeek();
        $thisWeekEnd = Carbon::now()->endOfWeek();

        if ($request->has('start_date') && $request->has('end_date')) {
            $thisWeekStart = Carbon::parse($request->start_date)->startOfDay();
            $thisWeekEnd = Carbon::parse($request->end_date)->endOfDay();
        }

        // Calculate previous period for trends (same duration)
        $duration = $thisWeekStart->diffInDays($thisWeekEnd) + 1;
        $lastWeekStart = (clone $thisWeekStart)->subDays($duration);
        $lastWeekEnd = (clone $thisWeekEnd)->subDays($duration);

        // Helpers for trends
        $calcTrend = function($current, $previous) {
            if ($previous == 0) return $current > 0 ? 100 : 0;
            return round((($current - $previous) / $previous) * 100, 1);
        };

        // Total Buses & Active Buses
        $totalBuses = Bus::count();
        $activeBuses = Bus::where('status', 1)->count();
        $busesLastWeek = Bus::where('created_at', '<', $thisWeekStart)->count();
        $busesTrend = $calcTrend($totalBuses, $busesLastWeek);

        $activeBusesLastWeek = Bus::where('status', 1)->where('created_at', '<', $thisWeekStart)->count();
        $activeBusesTrend = $calcTrend($activeBuses, $activeBusesLastWeek);

        // Restaurants
        $totalRestaurants = Restaurant::count();
        $restaurantsLastWeek = Restaurant::where('created_at', '<', $thisWeekStart)->count();
        $restaurantsTrend = $calcTrend($totalRestaurants, $restaurantsLastWeek);

        // Customers
        $totalCustomers = User::where('role', 'customer')->count();
        $customersLastWeek = User::where('role', 'customer')->where('created_at', '<', $thisWeekStart)->count();
        $customersTrend = $calcTrend($totalCustomers, $customersLastWeek);

        // Orders
        // $todaysOrders = Order::whereDate('created_at', $today)->count();
        // $lastWeekSameDayOrders = Order::whereDate('created_at', $today->copy()->subWeek())->count();


        // new
        $todaysOrders = Order::whereDate('created_at', $today)
            ->where(function ($q) {
                $q->where('payment_method', 'cod')
                    ->orWhere('payment_status', 'paid');
            })
            ->count();

        $lastWeekSameDayOrders = Order::whereDate(
            'created_at',
            $today->copy()->subWeek()
        )
            ->where(function ($q) {
                $q->where('payment_method', 'cod')
                    ->orWhere('payment_status', 'paid');
            })
            ->count();

        // end


        $ordersTrend = $calcTrend($todaysOrders, $lastWeekSameDayOrders);

        // Revenue
        $revenueThisWeek = Order::whereBetween('created_at', [$thisWeekStart, $thisWeekEnd])->where('payment_status', 'paid')->sum('total_amount') ?? 0;
        $revenueLastWeek = Order::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->where('payment_status', 'paid')->sum('total_amount') ?? 0;
        $revenueTrend = $calcTrend($revenueThisWeek, $revenueLastWeek);

        $todaysRevenue = Order::whereDate('created_at', $today)->where('payment_status', 'paid')->sum('total_amount') ?? 0;
        $yesterdaysRevenue = Order::whereDate('created_at', $today->copy()->subDay())->where('payment_status', 'paid')->sum('total_amount') ?? 0;
        $todaysRevenueTrend = $calcTrend($todaysRevenue, $yesterdaysRevenue);

        // Live Orders
        // $liveOrders = Order::with('restaurant')
        //     ->whereIn('order_status', ['placed', 'preparing', 'accepted', 'ready'])
        //     ->orderBy('created_at', 'desc')
        //     ->take(5)
        //     ->get();
        // $totalLiveOrders = Order::whereIn('order_status', ['placed', 'preparing', 'accepted', 'ready'])->count();


        // new
        $liveOrders = Order::with('restaurant')
            ->whereIn('order_status', ['placed', 'preparing', 'accepted', 'ready'])
            ->where(function ($q) {
                $q->where('payment_method', 'cod')
                    ->orWhere('payment_status', 'paid');
            })
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $totalLiveOrders = Order::whereIn(
            'order_status',
            ['placed', 'preparing', 'accepted', 'ready']
        )
            ->where(function ($q) {
                $q->where('payment_method', 'cod')
                    ->orWhere('payment_status', 'paid');
            })
            ->count();
        // end



        // Top Buses
        // $topBuses = Bus::withCount('orders')
        //     ->orderBy('orders_count', 'desc')
        //     ->take(5)
        //     ->get();

        $topBuses = Bus::withCount([
            'orders' => function ($query) {
                $query->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                });
            }
        ])
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();



        // Top Restaurants
        // $topRestaurants = Restaurant::withCount('orders')
        //     ->orderBy('orders_count', 'desc')
        //     ->take(5)
        //     ->get();


        $topRestaurants = Restaurant::withCount([
            'orders' => function ($query) {
                $query->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                });
            }
        ])
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();



        // Recent Bus Registrations
        $recentBuses = Bus::orderBy('created_at', 'desc')->take(5)->get();

        // Chart Data (Orders and Revenue this week)
        $dates = [];
        $completedOrdersChart = [];
        $cancelledOrdersChart = [];
        $revenueChart = [];

        $durationDays = $thisWeekStart->diffInDays($thisWeekEnd);
        $maxDays = min($durationDays, 31); // Cap at 31 days to prevent huge loops

        for($i=0; $i <= $maxDays; $i++) {
            $dateObj = $thisWeekStart->copy()->addDays($i);
            $dates[] = $dateObj->format('M d');

            // $completedOrdersChart[] = Order::whereDate('created_at', $dateObj)->whereNotIn('order_status', ['cancelled', 'failed'])->count();

            $completedOrdersChart[] = Order::whereDate('created_at', $dateObj)
                ->whereNotIn('order_status', ['cancelled', 'failed'])
                ->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                })
                ->count();


            $cancelledOrdersChart[] = Order::whereDate('created_at', $dateObj)->where('order_status', 'cancelled')->count();
            $revenueChart[] = Order::whereDate('created_at', $dateObj)->where('payment_status', 'paid')->sum('total_amount') ?? 0;
        }

        // Real QR Scans from Database
        $allBusesWithScans = Bus::with('busRoute')->where('total_scans', '>', 0)->get();
        $totalScans = $allBusesWithScans->sum('total_scans');

        $scansByRoute = [];
        foreach($allBusesWithScans as $bus) {
            $routeName = $bus->busRoute ? $bus->busRoute->source . ' → ' . $bus->busRoute->destination : 'Unassigned Route';
            if (!isset($scansByRoute[$routeName])) {
                $scansByRoute[$routeName] = 0;
            }
            $scansByRoute[$routeName] += $bus->total_scans;
        }

        arsort($scansByRoute); // Sort highest scans first

        $colors = ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#14b8a6'];
        $breakdown = [];
        $colorIndex = 0;

        foreach($scansByRoute as $label => $value) {
            $percent = $totalScans > 0 ? round(($value / $totalScans) * 100) : 0;
            $breakdown[] = [
                'label' => $label,
                'value' => $value,
                'percent' => $percent,
                'color' => $colors[$colorIndex % count($colors)]
            ];
            $colorIndex++;
            if ($colorIndex >= 4 && count($scansByRoute) > 5) {
                // If there are many routes, maybe group the rest into "Others".
                // But for simplicity, we just list them or limit to top 4 and sum the rest.
                // Let's limit to top 4 and sum others.
                if ($colorIndex == 4) {
                    $otherValue = 0;
                    $remainingRoutes = array_slice($scansByRoute, 4);
                    foreach($remainingRoutes as $rv) {
                        $otherValue += $rv;
                    }
                    $percent = $totalScans > 0 ? round(($otherValue / $totalScans) * 100) : 0;
                    $breakdown[] = [
                        'label' => 'Others',
                        'value' => $otherValue,
                        'percent' => $percent,
                        'color' => '#94a3b8' // Slate for others
                    ];
                    break;
                }
            }
        }

        if ($totalScans == 0) {
            $breakdown = [
                ['label' => 'No Scans Yet', 'value' => 0, 'percent' => 100, 'color' => '#cbd5e1']
            ];
        }

        $qrScans = [
            'total' => $totalScans,
            'breakdown' => $breakdown
        ];

        $trackingBuses = Bus::where('status', 1)
                            ->with(['busRoute.stops', 'currentStop'])
                            ->orderBy('journey_delay_minutes', 'desc')
                            ->get();

        return view('admin.dashboard', compact(
            'totalBuses', 'activeBuses', 'busesTrend', 'activeBusesTrend',
            'totalRestaurants', 'restaurantsTrend',
            'totalCustomers', 'customersTrend',
            'todaysOrders', 'ordersTrend',
            'revenueThisWeek', 'revenueTrend', 'todaysRevenue', 'todaysRevenueTrend',
            'liveOrders', 'totalLiveOrders',
            'topBuses', 'topRestaurants', 'recentBuses',
            'dates', 'completedOrdersChart', 'cancelledOrdersChart', 'revenueChart',
            'qrScans',
            'thisWeekStart', 'thisWeekEnd', 'trackingBuses'
        ));
    }
}
