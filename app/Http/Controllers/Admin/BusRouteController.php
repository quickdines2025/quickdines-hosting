<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BusRoute;
use App\Models\Bus;

class BusRouteController extends Controller
{
    public function index(Request $request)
    {
        $query = BusRoute::with('buses')->withCount('buses');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('source', 'like', '%' . $request->search . '%')
                  ->orWhere('destination', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('destination')) {
            $query->where('destination', $request->destination);
        }

        $routes = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        // Stat calculations
        $totalRoutes = BusRoute::count();
        $activeRoutes = BusRoute::where('status', 1)->count();
        $inactiveRoutes = BusRoute::where('status', 0)->count();
        $totalBuses = Bus::count();
        
        $sources = BusRoute::distinct('source')->pluck('source');
        $destinations = BusRoute::distinct('destination')->pluck('destination');
        $totalCities = $sources->merge($destinations)->unique()->count();
        
        // For popular route, just pick one with most buses
        $popularRoute = BusRoute::withCount('buses')->orderBy('buses_count', 'desc')->first();

        // Busiest routes for the sidebar
        $busiestRoutes = BusRoute::withCount('buses')->orderBy('buses_count', 'desc')->take(5)->get();

        // Top cities connected
        $topCities = collect([]);
        $allCities = $sources->merge($destinations)->unique();
        foreach($allCities as $city) {
            $count = BusRoute::where('source', $city)->orWhere('destination', $city)->count();
            $topCities->push(['name' => $city, 'count' => $count]);
        }
        $topCities = $topCities->sortByDesc('count')->take(6)->values();

        $allBuses = Bus::with('drivers.user')->get();

        return view('admin.routes.index', compact(
            'routes', 
            'totalRoutes', 
            'activeRoutes', 
            'inactiveRoutes',
            'totalBuses', 
            'totalCities', 
            'popularRoute',
            'sources',
            'destinations',
            'busiestRoutes',
            'topCities',
            'allBuses'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'source' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'bus_id' => 'nullable|exists:buses,id'
        ]);

        $name = $request->source . ' → ' . $request->destination;

        $route = BusRoute::create([
            'name' => $name,
            'source' => $request->source,
            'destination' => $request->destination,
            'distance' => $request->distance,
            'status' => $request->status,
        ]);
        
        if ($request->filled('bus_id')) {
            $bus = Bus::find($request->bus_id);
            if ($bus) {
                $bus->update(['bus_route_id' => $route->id]);
            }
        }

        return redirect()->route('admin.bus-routes.index')->with('success', 'Route created successfully.');
    }

    public function update(Request $request, $id)
    {
        $route = BusRoute::findOrFail($id);
        
        $request->validate([
            'source' => 'required|string|max:255',
            'destination' => 'required|string|max:255',
            'distance' => 'nullable|string|max:255',
            'status' => 'required|boolean',
            'bus_id' => 'nullable|exists:buses,id'
        ]);

        $name = $request->source . ' → ' . $request->destination;

        $route->update([
            'name' => $name,
            'source' => $request->source,
            'destination' => $request->destination,
            'distance' => $request->distance,
            'status' => $request->status,
        ]);
        
        if ($request->filled('bus_id')) {
            $bus = Bus::find($request->bus_id);
            if ($bus) {
                // Optionally unassign old bus if needed, but here we just assign this bus
                $bus->update(['bus_route_id' => $route->id]);
            }
        }

        return redirect()->route('admin.bus-routes.index')->with('success', 'Route updated successfully.');
    }

    public function destroy($id)
    {
        $route = BusRoute::findOrFail($id);
        $route->delete();

        return redirect()->route('admin.bus-routes.index')->with('success', 'Route deleted successfully.');
    }

    public function show($id)
    {
        $route = BusRoute::with(['stops' => function($q) {
            $q->orderBy('stop_sequence');
        }])->findOrFail($id);

        $activeBus = Bus::with('drivers.user')->where('bus_route_id', $route->id)->first();
        
        // Calculate real performance from buses
        $totalStops = $route->stops->count();
        
        $totalBusesOnRoute = Bus::where('bus_route_id', $route->id)->count();
        $delayInstances = Bus::where('bus_route_id', $route->id)->where('journey_delay_minutes', '>', 0)->count();
        
        $onTimeArrival = $totalBusesOnRoute > 0 
            ? round((1 - ($delayInstances / $totalBusesOnRoute)) * 100) 
            : 100;
            
        $avgDelay = $delayInstances > 0 
            ? round(Bus::where('bus_route_id', $route->id)->where('journey_delay_minutes', '>', 0)->avg('journey_delay_minutes'))
            : 0;

        return view('admin.routes.show', compact('route', 'activeBus', 'totalStops', 'onTimeArrival', 'delayInstances', 'avgDelay'));
    }

    public function storeStop(Request $request, $id)
    {
        $route = BusRoute::findOrFail($id);
        
        $request->validate([
            'stop_name' => 'required|string|max:255',
            'arrival_time' => 'nullable|string|max:20',
            'departure_time' => 'nullable|string|max:20',
            'stay_duration_mins' => 'nullable|integer|min:0',
            'distance_from_start' => 'nullable|numeric|min:0',
        ]);

        $maxSequence = $route->stops()->max('stop_sequence') ?? 0;

        $arrivalTime = null;
        if ($request->arrival_time) {
            try {
                $cleanTime = str_replace([' ', '.'], '', $request->arrival_time);
                $arrivalTime = \Carbon\Carbon::parse($cleanTime)->format('H:i:s');
            } catch (\Exception $e) {
                return back()->withErrors(['arrival_time' => 'Invalid arrival time format. Please use something like 10:30 AM.']);
            }
        }
        
        $departureTime = null;
        if ($request->departure_time) {
            try {
                $cleanTime = str_replace([' ', '.'], '', $request->departure_time);
                $departureTime = \Carbon\Carbon::parse($cleanTime)->format('H:i:s');
            } catch (\Exception $e) {
                return back()->withErrors(['departure_time' => 'Invalid departure time format. Please use something like 10:30 AM.']);
            }
        }

        $route->stops()->create([
            'stop_name' => $request->stop_name,
            'stop_sequence' => $maxSequence + 1,
            'expected_time_from_start' => 0, // Legacy field
            'arrival_time' => $arrivalTime,
            'departure_time' => $departureTime,
            'stay_duration_mins' => $request->stay_duration_mins,
            'distance_from_start' => $request->distance_from_start,
        ]);

        return redirect()->route('admin.bus-routes.show', $id)->with('success', 'Stop added successfully.');
    }

    public function destroyStop($stop_id)
    {
        $stop = \App\Models\BusRouteStop::findOrFail($stop_id);
        $routeId = $stop->bus_route_id;
        $stop->delete();

        return redirect()->route('admin.bus-routes.show', $routeId)->with('success', 'Stop removed successfully.');
    }

    public function submitDelay(Request $request, $id)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'delay_minutes' => 'required|integer|min:0',
            'delay_reason' => 'nullable|string',
            'notes' => 'nullable|string',
            'time_type' => 'nullable|in:delay,early'
        ]);

        $bus = Bus::where('bus_route_id', $id)->findOrFail($request->bus_id);
        
        $minutes = $request->delay_minutes;
        $isEarly = $request->time_type === 'early';
        
        $status = null;
        if ($minutes > 0) {
            if ($isEarly) {
                $status = 'Early: ' . $request->delay_reason . ($request->notes ? ' - ' . $request->notes : '');
                $minutes = -$minutes;
            } else {
                $status = 'Delayed: ' . $request->delay_reason . ($request->notes ? ' - ' . $request->notes : '');
            }
        } else {
            $minutes = 0; // if 0, then reset
            $status = null;
        }
        
        $bus->update([
            'journey_delay_minutes' => $minutes,
            'journey_status' => $status
        ]);

        return redirect()->route('admin.bus-routes.show', $id)->with('success', 'Delay logged successfully.');
    }
}
