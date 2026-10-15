<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\Bus;

class BusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Bus::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bus_number', 'like', "%{$search}%")
                  ->orWhere('bus_name', 'like', "%{$search}%")
                  ->orWhere('operator', 'like', "%{$search}%");
            });
        }

        if ($request->filled('route')) {
            $query->where(function($q) use ($request) {
                $q->where('route_from', $request->route)
                  ->orWhere('route_to', $request->route);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('operator')) {
            $query->where('operator', $request->operator);
        }

        $buses = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // Get unique filters for dropdowns
        $operators = Bus::whereNotNull('operator')->where('operator', '!=', '')->distinct()->pluck('operator');
        $routesFrom = Bus::whereNotNull('route_from')->where('route_from', '!=', '')->distinct()->pluck('route_from');
        $routesTo = Bus::whereNotNull('route_to')->where('route_to', '!=', '')->distinct()->pluck('route_to');
        
        // Combine all distinct locations into one list for the route filter
        $routes = $routesFrom->merge($routesTo)->unique()->sort()->values();

        $totalBuses = Bus::count();
        $activeBuses = Bus::where('status', 1)->count();
        $inactiveBuses = Bus::where('status', 0)->count();
        $totalRoutes = $routes->count();
        $qrScans = 0;
        $allDrivers = \App\Models\Driver::with('user')->where('status', 1)->whereHas('user', function($q) {
            $q->where('status', 'active');
        })->get();
        
        return view('admin.buses.index', compact(
            'buses', 'totalBuses', 'activeBuses', 'inactiveBuses', 'totalRoutes', 'qrScans', 'operators', 'routes', 'allDrivers'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'bus_number' => 'required|string|max:255|unique:buses',
            'bus_name' => 'nullable|string|max:255',
            'operator' => 'nullable|string|max:255',
            'route_from' => 'nullable|string|max:255',
            'route_to' => 'nullable|string|max:255',
            'distance' => 'nullable|string|max:255',
            'departure_time' => 'nullable|date_format:H:i',
            'arrival_time' => 'nullable|date_format:H:i',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('buses', 'public');
        }

        Bus::create([
            'bus_number' => $request->bus_number,
            'bus_name' => $request->bus_name,
            'operator' => $request->operator,
            'route_from' => $request->route_from,
            'route_to' => $request->route_to,
            'distance' => $request->distance,
            'departure_time' => $request->departure_time,
            'arrival_time' => $request->arrival_time,
            'status' => $request->status,
            'image' => $imagePath,
            'qr_code' => 'QR-' . strtoupper(uniqid()), // Auto generate a dummy QR for now
        ]);

        return redirect()->route('admin.buses.index')->with('success', 'Bus added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'bus_number' => 'required|string|max:255|unique:buses,bus_number,' . $bus->id,
            'bus_name' => 'nullable|string|max:255',
            'operator' => 'nullable|string|max:255',
            'route_from' => 'nullable|string|max:255',
            'route_to' => 'nullable|string|max:255',
            'distance' => 'nullable|string|max:255',
            'departure_time' => 'nullable|date_format:H:i:s,H:i',
            'arrival_time' => 'nullable|date_format:H:i:s,H:i',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:20',
            'status' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image
            if ($bus->image) {
                Storage::disk('public')->delete($bus->image);
            }
            $data['image'] = $request->file('image')->store('buses', 'public');
        }

        $bus->update($data);

        return redirect()->route('admin.buses.index')->with('success', 'Bus updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Bus  $bus
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus deleted successfully!');
    }

    /**
     * Manage the specific route for this bus.
     */
    public function manageRoute($id)
    {
        $bus = Bus::findOrFail($id);

        // If the bus already has a route assigned, go straight to its details page.
        if ($bus->bus_route_id) {
            return redirect()->route('admin.bus-routes.show', $bus->bus_route_id);
        }

        // If it doesn't have a route, create a new specific route for this bus.
        $routeName = ($bus->route_from && $bus->route_to) 
            ? $bus->route_from . ' → ' . $bus->route_to 
            : $bus->bus_number . ' Route';

        $route = \App\Models\BusRoute::create([
            'name' => $routeName,
            'source' => $bus->route_from ?? 'Unknown',
            'destination' => $bus->route_to ?? 'Unknown',
            'distance' => 0
        ]);

        $bus->bus_route_id = $route->id;
        $bus->journey_delay_minutes = 0;
        $bus->journey_status = 'Pending';
        $bus->save();

        return redirect()->route('admin.bus-routes.show', $route->id)->with('success', 'A new route profile has been created for this bus. You can now define its stops and ETA.');
    }

    /**
     * Download the QR code for a bus.
     */
    public function downloadQr($id)
    {
        $bus = Bus::findOrFail($id);
        $qrData = $bus->qr_code ?? 'BUS-'.$bus->id;
        
        // Fetch the generated QR code from qrserver API
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);
        $imageContent = file_get_contents($url);
        
        $filename = 'bus-' . str_replace(' ', '-', strtolower($bus->bus_number)) . '-qr.png';
        
        return response($imageContent)
            ->header('Content-type', 'image/png')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    public function assignDrivers(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'driver_ids' => 'nullable|array',
            'driver_ids.*' => 'exists:drivers,id'
        ]);

        $busId = $request->bus_id;
        $driverIds = $request->driver_ids ?? [];

        // Clear bus_id for drivers that are currently assigned to this bus but are not in the new list
        \App\Models\Driver::where('bus_id', $busId)
            ->whereNotIn('id', $driverIds)
            ->update(['bus_id' => null]);

        // Assign new drivers to this bus
        if (!empty($driverIds)) {
            \App\Models\Driver::whereIn('id', $driverIds)
                ->update(['bus_id' => $busId]);
        }

        return redirect()->route('admin.buses.index')->with('success', 'Drivers successfully assigned to the bus!');
    }
}
