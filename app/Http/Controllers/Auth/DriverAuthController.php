<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Bus;
use Illuminate\Support\Facades\Auth;

class DriverAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.driver-login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'bus_number' => 'required|string',
            'mobile' => 'required|string',
        ]);

        $bus = Bus::where('bus_number', $request->bus_number)->first();
        if (!$bus) {
            return back()->withErrors(['bus_number' => 'Bus not found.']);
        }

        $driver = Driver::where('bus_id', $bus->id)->where('mobile', $request->mobile)->first();
        if (!$driver) {
            return back()->withErrors(['mobile' => 'Driver not found for this bus.']);
        }

        // Assuming driver is linked to a user account
        if ($driver->user_id) {
            Auth::loginUsingId($driver->user_id);
            return redirect()->route('driver.dashboard');
        }

        return back()->withErrors(['mobile' => 'Driver profile is not properly linked to a user account.']);
    }
}
