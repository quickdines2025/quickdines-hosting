<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\Bus;

class DashboardController extends Controller
{
    public function index()
    {
        // Get the current user's restaurant
        $restaurant = Restaurant::where('user_id', auth()->id())->first();
        
        if (!$restaurant) {
            return redirect()->route('dashboard')->with('error', 'Restaurant profile not found.');
        }

        // Get active orders for this restaurant
        $orders = Order::with(['user', 'bus'])
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('order_status', ['placed', 'preparing', 'ready', 'out_for_delivery'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get completed orders for revenue calculation
        $completedOrders = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('order_status', ['completed', 'delivered'])
            ->get();

        // Statistics
        $today = now()->startOfDay();
        
        $todaysOrders = Order::where('restaurant_id', $restaurant->id)
            ->where('created_at', '>=', $today)
            ->count();

        $todaysRevenue = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('order_status', ['completed', 'delivered'])
            ->where('created_at', '>=', $today)
            ->sum('total_amount');

        $yesterdaysRevenue = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('order_status', ['completed', 'delivered'])
            ->whereBetween('created_at', [now()->subDay()->startOfDay(), now()->subDay()->endOfDay()])
            ->sum('total_amount');

        // Calculate trend percentage
        $revenueTrend = 0;
        if ($yesterdaysRevenue > 0) {
            $revenueTrend = round((($todaysRevenue - $yesterdaysRevenue) / $yesterdaysRevenue) * 100);
        } elseif ($todaysRevenue > 0) {
            $revenueTrend = 100;
        }

        $preparingOrdersCount = $orders->where('order_status', 'preparing')->count();

        // Wallet Summary
        $availableBalance = $restaurant->wallet_balance ?? 0;
        
        $thisMonthEarnings = Order::where('restaurant_id', $restaurant->id)
            ->whereIn('order_status', ['completed', 'delivered'])
            ->where('created_at', '>=', now()->startOfMonth())
            ->sum('total_amount');

        // Recent Transactions
        $recentTransactions = \App\Models\Transaction::where('user_type', 'vendor')
            ->where('user_id', auth()->id())
            ->latest()
            ->take(5)
            ->get();

        // Get assigned buses based on the restaurant's relationship
        $busesCount = $restaurant->buses()->where('status', 1)->count();

        // Get menus for menu management widget
        $menus = \App\Models\Menu::where('restaurant_id', $restaurant->id)->latest()->take(5)->get();

        // Chart Data (Last 7 Days Sales)
        $salesData = [];
        $salesLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $salesLabels[] = $date->format('M d');
            $salesData[] = Order::where('restaurant_id', $restaurant->id)
                ->where('order_status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_amount');
        }

        return view('vendor.dashboard', compact(
            'restaurant', 'orders', 'busesCount', 'menus', 
            'todaysOrders', 'todaysRevenue', 'revenueTrend', 'preparingOrdersCount',
            'availableBalance', 'thisMonthEarnings', 'recentTransactions',
            'salesLabels', 'salesData'
        ));
    }
}
