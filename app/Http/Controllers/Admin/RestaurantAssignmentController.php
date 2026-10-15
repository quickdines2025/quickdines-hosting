<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Restaurant;
use Illuminate\Support\Facades\DB;

class RestaurantAssignmentController extends Controller
{
    public function index(Request $request)
    {
        // Calculate Top Stats
        $totalBuses = Bus::count();

        $assignedRestaurants = DB::table('bus_restaurant')
            ->select('restaurant_id')
            ->distinct()
            ->count();

        $totalAssignments = DB::table('bus_restaurant')->count();

        // Let's define active assignments as those belonging to active restaurants
        $activeAssignments = DB::table('bus_restaurant')
            ->join('restaurants', 'bus_restaurant.restaurant_id', '=', 'restaurants.id')
            ->where('restaurants.status', 1)
            ->count();

        // Get filter options
        $allOperators = Bus::whereNotNull('operator')->distinct()->orderBy('operator')->pluck('operator');
        $allRoutes = \App\Models\BusRoute::orderBy('name')->get();

        $tab = $request->get('tab', 'bus'); // 'bus' or 'restaurant'

        // Both side panels are always in the DOM, so we need both lists
        $allRestaurants = Restaurant::where('status', 1)->orderBy('restaurant_name', 'asc')->get();

        if ($tab == 'restaurant') {
            // Restaurant-wise tab
            $query = Restaurant::with('buses');

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('restaurant_name', 'like', "%{$search}%");
            }
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $restaurantsList = $query->paginate(15);
            $allBuses = Bus::where('status', 1)->orderBy('bus_number', 'asc')->get();

            return view('admin.restaurants.assign', compact(
                'totalBuses',
                'assignedRestaurants',
                'totalAssignments',
                'activeAssignments',
                'restaurantsList',
                'allBuses',
                'allRestaurants',
                'tab'
            ));
        }

        // Bus-wise tab (default)
        $query = Bus::with(['restaurants', 'busRoute']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bus_number', 'like', "%{$search}%")
                  ->orWhere('bus_name', 'like', "%{$search}%")
                  ->orWhere('operator', 'like', "%{$search}%");
            });
        }

        if ($request->filled('route')) {
            $query->where('bus_route_id', $request->route);
        }

        if ($request->filled('operator')) {
            $query->where('operator', $request->operator);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $buses = $query->paginate(15);

        return view('admin.restaurants.assign', compact(
            'totalBuses',
            'assignedRestaurants',
            'totalAssignments',
            'activeAssignments',
            'buses',
            'allRestaurants',
            'allOperators',
            'allRoutes',
            'tab'
        ));
    }

    public function sync(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'restaurant_ids' => 'nullable|array',
            'restaurant_ids.*' => 'exists:restaurants,id'
        ]);

        $bus = Bus::findOrFail($request->bus_id);

        // Sync the relationships (this will add the new ones and remove the unselected ones)
        $bus->restaurants()->sync($request->restaurant_ids ?? []);

        return redirect()->back()->with('success', 'Assignments updated successfully!');
    }

    public function syncRestaurant(Request $request)
    {
        $request->validate([
            'restaurant_id' => 'required|exists:restaurants,id',
            'bus_ids' => 'nullable|array',
            'bus_ids.*' => 'exists:buses,id'
        ]);

        $restaurant = Restaurant::findOrFail($request->restaurant_id);

        // Sync the relationships
        $restaurant->buses()->sync($request->bus_ids ?? []);

        return redirect()->back()->with('success', 'Assignments updated successfully!');
    }
}
