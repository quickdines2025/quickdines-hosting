<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Menu;
use Carbon\Carbon;

class QrScanController extends Controller
{
    public function scan($qr_code)
    {
        $bus = Bus::where('qr_code', $qr_code)->first();

        if (!$bus) {
            return abort(404, 'Invalid QR Code');
        }

        if ($bus->status) {
            $bus->total_scans = $bus->total_scans + 1;
            $bus->last_scanned_at = Carbon::now();
            $bus->save();
        }

        // Save bus in session; a fresh scan always resets any previously chosen restaurant
        session(['scanned_bus_id' => $bus->id]);
        session()->forget('selected_restaurant_id');

        return redirect()->to(
            !auth()->check() ? route('customer.auth') : route('home')
        );
    }

    /**
     * Kept for backward compatibility with old /qr-entry links.
     * The restaurant decision (0 / 1 / 2+) now lives on the Welcome page itself.
     */
    public function entry()
    {
        return redirect()->route('home');
    }

    /**
     * /restaurants
     * QR customer  -> Welcome page decides everything now.
     * Normal user  -> all-menus grid (unchanged).
     */
    public function restaurants()
    {
        if (session('scanned_bus_id')) {
            return redirect()->route('home');
        }

        $menus = Menu::with('restaurant')
            ->where('availability', 1)
            ->latest()
            ->paginate(24);

        return view('restaurants.index', compact('menus'));
    }

    /**
     * Customer picks a restaurant on the multi-restaurant selection screen.
     * Stores the choice in session and sends them back to the Welcome page,
     * which then renders that restaurant + its menu directly.
     */
    public function selectRestaurant($id)
    {
        $busId = session('scanned_bus_id');
        if (!$busId) {
            return redirect()->route('home');
        }

        $bus = Bus::find($busId);
        if (!$bus) {
            session()->forget('scanned_bus_id');
            return redirect()->route('home');
        }

        $valid = $bus->restaurants()
            ->where('restaurants.status', 1)
            ->where('restaurants.id', $id)
            ->exists();

        if (!$valid) {
            abort(403, 'This restaurant is not available for this bus.');
        }

        session(['selected_restaurant_id' => (int) $id]);

        return redirect()->route('home');
    }

    /**
     * Lets a customer go back to the restaurant selection screen
     * when their bus has more than one assigned restaurant.
     */
    public function changeRestaurant()
    {
        session()->forget('selected_restaurant_id');
        return redirect()->route('home');
    }
}
