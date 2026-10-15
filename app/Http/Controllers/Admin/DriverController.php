<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::whereHas('driver', function($q) {
            $q->where('status', 1);
        })->with(['driver.bus.busRoute'])->where('role', 'driver')->orderBy('created_at', 'desc');

        // Stats calculation
        $totalDrivers = User::whereHas('driver', function($q) {
            $q->where('status', 1);
        })->where('role', 'driver')->count();
        $activeDrivers = User::whereHas('driver', function($q) {
            $q->where('status', 1);
        })->where('role', 'driver')->where('status', 'active')->count();
        $inactiveDrivers = User::whereHas('driver', function($q) {
            $q->where('status', 1);
        })->where('role', 'driver')->where('status', 'blocked')->count(); 
        $onTripDrivers = 0; 

        $stats = [
            'total' => $totalDrivers,
            'active' => $activeDrivers,
            'inactive' => $inactiveDrivers,
            'on_trip' => $onTripDrivers
        ];

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        if ($request->has('export')) {
            $drivers = $query->get();
            $csvData = "ID,Name,Email,Mobile,Status,Joined On\n";
            foreach ($drivers as $driver) {
                $csvData .= "DRV100{$driver->id},\"{$driver->name}\",\"{$driver->email}\",\"{$driver->mobile}\",{$driver->status},\"{$driver->created_at->format('M d Y')}\"\n";
            }
            return response($csvData)
                ->header('Content-Type', 'text/csv')
                ->header('Content-Disposition', 'attachment; filename="drivers.csv"');
        }

        $drivers = $query->paginate(15);
        $drivers->appends($request->all());

        return view('admin.drivers.index', compact('drivers', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15|unique:users',
            'email' => 'nullable|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->role = 'driver';
        $user->status = 'active';
        $user->save();

        // Also create a driver profile so they can be assigned buses smoothly
        $driver = new \App\Models\Driver();
        $driver->user_id = $user->id;
        $driver->name = $user->name;
        $driver->mobile = $user->mobile;
        $driver->status = 1; // Admin added drivers are approved by default
        $driver->save();

        return back()->with('success', 'Driver added successfully.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:15|unique:users,mobile,'.$user->id,
            'email' => 'nullable|email|max:255|unique:users,email,'.$user->id,
            'status' => 'required|in:active,blocked',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->email = $request->email;
        $user->status = $request->status;
        if($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return back()->with('success', 'Driver updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'Driver deleted successfully.');
    }
    public function pending(Request $request)
    {
        $query = \App\Models\Driver::with('user')->where('status', 0)->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        $pendingDrivers = $query->paginate(15);
        $pendingDrivers->appends($request->all());

        return view('admin.drivers.pending', compact('pendingDrivers'));
    }

    public function approve($id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        $driver->status = 1;
        $driver->save();

        return redirect()->back()->with('success', 'Driver approved successfully.');
    }

    public function reject($id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        $driver->status = 2; // Assuming 2 is rejected/blocked
        $driver->save();

        return redirect()->back()->with('success', 'Driver rejected successfully.');
    }

    public function destroyApplication($id)
    {
        $driver = \App\Models\Driver::findOrFail($id);
        
        if ($driver->document_path && \Storage::disk('public')->exists($driver->document_path)) {
            \Storage::disk('public')->delete($driver->document_path);
        }
        
        // Delete the associated user as well since it was created at registration
        if ($driver->user_id) {
            User::where('id', $driver->user_id)->delete();
        }

        $driver->delete();

        return redirect()->back()->with('success', 'Driver application deleted successfully.');
    }
}
