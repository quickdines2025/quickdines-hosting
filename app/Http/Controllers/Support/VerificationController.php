<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Restaurant;

class VerificationController extends Controller
{
    public function restaurantVerification()
    {
        $pendingRestaurants = Restaurant::where('status', 0)->get();
        return view('support.verification.restaurants', compact('pendingRestaurants'));
    }

    public function driverVerification()
    {
        $pendingDrivers = Driver::where('status', 0)->get();
        return view('support.verification.drivers', compact('pendingDrivers'));
    }

    public function verifyRestaurant(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->status = 1;
        $restaurant->save();

        return back()->with('success', "Restaurant {$restaurant->restaurant_name} has been verified.");
    }

    public function rejectRestaurant(Request $request, $id)
    {
        $restaurant = Restaurant::findOrFail($id);
        $restaurant->status = 2; // 2 for rejected/suspended
        $restaurant->save();

        return back()->with('success', "Restaurant {$restaurant->restaurant_name} has been rejected.");
    }

    public function verifyDriver(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = 1;
        $driver->save();

        return back()->with('success', "Driver {$driver->name} has been verified.");
    }
    
    public function rejectDriver(Request $request, $id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = 2; // 2 for rejected
        $driver->save();

        return back()->with('success', "Driver {$driver->name} has been rejected.");
    }
}
