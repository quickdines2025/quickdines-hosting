<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')
                    ->with(['orders' => function($q) {
                        $q->latest()->with('bus.busRoute')->limit(1);
                    }])
                    ->orderBy('created_at', 'desc');
        
        // Default to customers for this view if no role specified, as the UI says "Customers"
        $role = $request->role ?? 'customer';
        if (in_array($role, ['admin', 'customer', 'vendor', 'driver'])) {
            $query->where('role', $role);
        }

        // Stats
        $baseStatsQuery = User::where('role', $role);
        $stats = [
            'total' => (clone $baseStatsQuery)->count(),
            'active' => (clone $baseStatsQuery)->where('status', 'active')->count(),
            'new_this_week' => (clone $baseStatsQuery)->where('created_at', '>=', now()->subDays(7))->count(),
            'blocked' => (clone $baseStatsQuery)->where('status', 'blocked')->count(),
        ];

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('bus_id')) {
            // Find users who have placed orders on this bus
            $query->whereHas('orders', function($q) use ($request) {
                $q->where('bus_id', $request->bus_id);
            });
        }

        if ($request->export == 'csv') {
            $exportUsers = $query->get();
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=customers.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('Customer Name', 'Phone', 'Email', 'Bus Details', 'Total Orders', 'Joined On', 'Status');

            $callback = function() use($exportUsers, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($exportUsers as $user) {
                    $busDetails = 'No orders yet';
                    if($user->orders->count() > 0 && $user->orders->first()->bus) {
                        $latestBus = $user->orders->first()->bus;
                        $route = $latestBus->busRoute ? ($latestBus->busRoute->source . ' to ' . $latestBus->busRoute->destination) : ($latestBus->route_from . ' to ' . $latestBus->route_to);
                        $busDetails = $latestBus->bus_number . ' (' . $route . ')';
                    }

                    $row['Customer Name']  = $user->name;
                    $row['Phone']  = $user->mobile ?? 'N/A';
                    $row['Email']  = $user->email ?? 'N/A';
                    $row['Bus Details']  = $busDetails;
                    $row['Total Orders']  = $user->orders_count;
                    $row['Joined On']  = $user->created_at->format('M d, Y');
                    $row['Status']  = ucfirst($user->status);

                    fputcsv($file, array($row['Customer Name'], $row['Phone'], $row['Email'], $row['Bus Details'], $row['Total Orders'], $row['Joined On'], $row['Status']));
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $users = $query->paginate(15);
        $users->appends($request->all());
        $currentRole = $role;
        
        $buses = \App\Models\Bus::select('id', 'bus_number')->get();

        return view('admin.users.index', compact('users', 'currentRole', 'stats', 'buses'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|unique:users',
            'role' => 'required|in:admin,customer,vendor,driver',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'role' => $request->role,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created successfully.');
    }

    public function show($id)
    {
        $user = User::with(['orders' => function($q) {
            $q->with('restaurant', 'bus.busRoute')->orderBy('created_at', 'desc');
        }])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        if ($request->has('status') && count($request->all()) == 2) { // _method and status
            $user->status = $request->status;
            $user->save();
            return back()->with('success', 'Customer status updated successfully!');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|unique:users,mobile,'.$user->id,
            'role' => 'required|in:admin,customer,vendor,driver',
            'password' => 'nullable|string|min:8',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return back()->with('success', 'User deleted successfully!');
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed'
        ]);

        $user = User::findOrFail($id);
        $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        $user->save();

        return redirect()->back()->with('success', 'Password reset successfully for ' . $user->name);
    }
}
