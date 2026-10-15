<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Order;

class PageController extends Controller
{
    private function getDriver()
    {
        return Driver::with('bus')->where('user_id', auth()->id())->first();
    }

    public function location()
    {
        $driver = $this->getDriver();
        return view('driver.location.index', compact('driver'));
    }

    public function orders()
    {
        $driver = $this->getDriver();
        $restaurants = collect();
        if ($driver && $driver->bus_id) {
            $restaurants = \Illuminate\Support\Facades\DB::table('bus_restaurant')
                ->where('bus_id', $driver->bus_id)
                ->join('restaurants', 'bus_restaurant.restaurant_id', '=', 'restaurants.id')
                ->select('restaurants.*')
                ->get();
        }
        return view('driver.orders.index', compact('driver', 'restaurants'));
    }

    public function passengers()
    {
        $driver = $this->getDriver();
        $orders = collect();
        if ($driver && $driver->bus_id) {
            $orders = Order::with('restaurant')
                ->where('bus_id', $driver->bus_id)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        return view('driver.passengers.index', compact('driver', 'orders'));
    }

    public function notifications()
    {
        $driver = $this->getDriver();
        return view('driver.notifications.index', compact('driver'));
    }

    public function earnings()
    {
        $driver = $this->getDriver();
        return view('driver.earnings.index', compact('driver'));
    }

    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
        ]);

        $driver = $this->getDriver();
        if ($driver) {
            $driver->update([
                'bank_name' => $request->bank_name,
                'account_number' => $request->account_number,
                'ifsc_code' => strtoupper($request->ifsc_code),
                'branch_name' => $request->branch_name,
            ]);
            return redirect()->back()->with('success', 'Bank details updated successfully.');
        }

        return redirect()->back()->with('error', 'Unable to update bank details.');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $driver = $this->getDriver();
        if (!$driver) {
            return redirect()->back()->with('error', 'Driver not found.');
        }

        if (!$driver->bank_name || !$driver->account_number || !$driver->ifsc_code) {
            return redirect()->back()->with('error', 'Please update your bank details first.');
        }

        if ($driver->wallet_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient wallet balance.');
        }

        // Deduct from wallet
        $driver->wallet_balance -= $request->amount;
        $driver->save();

        // Create payout transaction
        \App\Models\Transaction::create([
            'user_id' => $driver->id,
            'user_type' => 'driver',
            'amount' => $request->amount,
            'type' => 'payout',
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully.');
    }

    public function codrivers()
    {
        $driver = $this->getDriver();
        return view('driver.codrivers.index', compact('driver'));
    }

    public function profile()
    {
        $driver = $this->getDriver();
        return view('driver.profile.index', compact('driver'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        
        if ($request->filled('password')) {
            $user->password = \Illuminate\Support\Facades\Hash::make($request->password);
        }
        
        $user->save();

        $driver = $this->getDriver();
        if ($driver) {
            $driver->name = $request->name;
            $driver->mobile = $request->mobile;
            $driver->save();
        }

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

    public function support()
    {
        $driver = $this->getDriver();
        $tickets = \App\Models\SupportTicket::where('customer_id', auth()->id())->latest()->get();
        return view('driver.support.index', compact('driver', 'tickets'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'issue' => 'required|string'
        ]);

        \App\Models\SupportTicket::create([
            'ticket_number' => 'TKT-' . rand(10000, 99999),
            'customer_id' => auth()->id(),
            'category' => $request->category,
            'issue' => $request->issue,
            'priority' => 'medium',
            'status' => 'open'
        ]);

        return back()->with('success', 'Ticket created successfully.');
    }

    public function chat()
    {
        $driver = $this->getDriver();
        return view('driver.chat.index', compact('driver'));
    }

    public function apiNotifications()
    {
        $driver = $this->getDriver();
        $total = 0;
        if ($driver && $driver->bus_id) {
            $total = \Illuminate\Support\Facades\DB::table('bus_restaurant')
                ->where('bus_id', $driver->bus_id)
                ->count();
        }
        return response()->json(['total' => $total]);
    }
}
