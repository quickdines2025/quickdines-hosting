<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Restaurant;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Start building the query with buses count
        $query = Restaurant::withCount('buses');
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('restaurant_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('city')) {
            $city = $request->city;
            $query->where('city', $city);
        }

        if ($request->filled('category')) {
            $category = $request->category;
            $query->where('cuisines', 'like', "%{$category}%");
        }

        $vendors = $query->orderBy('created_at', 'desc')->paginate(10);
        
        // Calculate Metrics accurately
        $totalRestaurants = Restaurant::count();
        $activeCount = Restaurant::where('status', 1)->count();
        $pendingCount = Restaurant::where('status', 0)->count();
        $suspendedCount = Restaurant::where('status', 2)->count();
        
        // Accurate total payout from wallet balances
        $totalPayout = Restaurant::sum('wallet_balance');

        // Extract Top Cities from the city column
        $cityCounts = Restaurant::whereNotNull('city')
            ->where('city', '!=', '')
            ->select('city', \DB::raw('count(*) as total'))
            ->groupBy('city')
            ->orderByDesc('total')
            ->get();
            
        $totalAddressCount = $cityCounts->sum('total') ?: 1;
        $topCities = [];
        
        foreach ($cityCounts->take(5) as $cityData) {
            $topCities[] = [
                'name' => $cityData->city,
                'count' => $cityData->total,
                'percentage' => round(($cityData->total / $totalAddressCount) * 100, 1)
            ];
        }

        // Extract all unique categories (cuisines)
        $allCuisines = Restaurant::pluck('cuisines')->filter();
        $categoryList = [];
        foreach ($allCuisines as $cuisineStr) {
            $cats = array_map('trim', explode(',', $cuisineStr));
            foreach ($cats as $c) {
                if (!empty($c) && !in_array($c, $categoryList)) {
                    $categoryList[] = $c;
                }
            }
        }
        sort($categoryList);

        // Recent registrations
        $recentRegistrations = Restaurant::orderBy('created_at', 'desc')->take(3)->get();

        return view('admin.vendors.index', compact(
            'vendors', 
            'totalRestaurants', 
            'activeCount', 
            'pendingCount', 
            'suspendedCount', 
            'totalPayout',
            'topCities',
            'categoryList',
            'recentRegistrations'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vendors.create');
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
            'restaurant_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'cuisines' => 'nullable|string',
            'status' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->except(['logo', 'banner']);
        $data['user_id'] = auth()->id() ?? 1; // Fallback if no user is authenticated

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }
        if ($request->hasFile('banner')) {
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
        }

        Restaurant::create($data);

        return redirect()->route('admin.vendors.index')->with('success', 'Restaurant added successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendor = Restaurant::with('buses.busRoute')->findOrFail($id);
        return view('admin.vendors.show', compact('vendor'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $vendor = Restaurant::findOrFail($id);
        return view('admin.vendors.edit', compact('vendor'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $vendor = Restaurant::findOrFail($id);
        
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'cuisines' => 'nullable|string',
            'status' => 'required|integer',
            'rating' => 'nullable|numeric|min:0|max:5',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        $data = $request->except(['logo', 'banner']);

        if ($request->hasFile('logo')) {
            if ($vendor->logo && \Storage::disk('public')->exists($vendor->logo)) {
                \Storage::disk('public')->delete($vendor->logo);
            }
            $data['logo'] = $request->file('logo')->store('restaurants/logos', 'public');
        }
        
        if ($request->hasFile('banner')) {
            if ($vendor->banner && \Storage::disk('public')->exists($vendor->banner)) {
                \Storage::disk('public')->delete($vendor->banner);
            }
            $data['banner'] = $request->file('banner')->store('restaurants/banners', 'public');
        }

        $vendor->update($data);

        return redirect()->route('admin.vendors.index')->with('success', 'Restaurant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vendor = Restaurant::findOrFail($id);
        
        if ($vendor->logo && \Storage::disk('public')->exists($vendor->logo)) {
            \Storage::disk('public')->delete($vendor->logo);
        }
        if ($vendor->banner && \Storage::disk('public')->exists($vendor->banner)) {
            \Storage::disk('public')->delete($vendor->banner);
        }
        
        $vendor->delete();

        return redirect()->route('admin.vendors.index')->with('success', 'Restaurant deleted successfully!');
    }

    public function pending(Request $request)
    {
        // Update user's last_viewed_pending_restaurants timestamp
        auth()->user()->update([
            'last_viewed_pending_restaurants' => now()
        ]);

        $query = Restaurant::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('restaurant_name', 'like', "%{$search}%")
                  ->orWhere('owner_name', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        if ($request->filled('cuisine')) {
            $query->where('cuisines', 'like', "%{$request->cuisine}%");
        }

        $sort = $request->get('sort', 'newest');
        if ($sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $pendingRestaurants = $query->paginate(15);
        $pendingRestaurants->appends($request->all());

        // Get unique cities for filter
        $cities = Restaurant::whereNotNull('city')->where('city', '!=', '')->pluck('city')->unique()->sort();

        // Get unique cuisines for filter
        $allCuisines = Restaurant::pluck('cuisines')->filter();
        $cuisinesList = [];
        foreach ($allCuisines as $cuisineStr) {
            $cats = array_map('trim', explode(',', $cuisineStr));
            foreach ($cats as $cat) {
                if (!empty($cat) && !in_array($cat, $cuisinesList)) {
                    $cuisinesList[] = $cat;
                }
            }
        }
        sort($cuisinesList);

        return view('admin.vendors.pending', compact('pendingRestaurants', 'cities', 'cuisinesList'));
    }

    public function approve($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->status = 1; // Active
        $restaurant->save();

        return redirect()->back()->with('success', 'Restaurant approved successfully.');
    }

    public function reject($id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->status = 2; // Suspended/Rejected
        $restaurant->save();

        return redirect()->back()->with('success', 'Restaurant rejected successfully.');
    }
}
