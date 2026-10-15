<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function tracking()
    {
        $restaurant = auth()->user()->restaurant;
        $buses = collect();
        $trackingOrders = collect();

        if ($restaurant) {
            $buses = $restaurant->buses()->with(['busRoute.stops', 'currentStop'])->where('status', 1)->get();
            $trackingOrders = \App\Models\Order::with(['driver.user', 'bus'])
                ->where('restaurant_id', $restaurant->id)
                ->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                })
                ->whereIn('order_status', [
                    'pending',
                    'placed',
                    'accepted',
                    'preparing',
                    'packing',
                    'ready',
                    'on_the_way',
                    'out_for_delivery'
                ])
                ->whereNotNull('driver_id')
                ->latest()
                ->take(20)
                ->get();
        }

        return view('vendor.tracking', compact('buses', 'restaurant', 'trackingOrders'));
    }



            public function orders(\Illuminate\Http\Request $request)
    {
        $restaurant = auth()->user()->restaurant;
        $selectedBusId = $request->input('bus_id');

        if (!$restaurant) {
            $activeOrders = collect();
            $pastOrders = collect();
            $menus = collect();
            $buses = collect();
        } else {

            $buses = $restaurant->buses()
                ->where('status', 1)
                ->orderBy('bus_number')
                ->get();

            $activeQuery = \App\Models\Order::with([
                'items.menuItem',
                'bus.busRoute',
                'user',
                'driverUser'
            ])
                ->where('restaurant_id', $restaurant->id)
                ->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                })
                ->whereIn('order_status', [
                    'pending',
                    'accepted',
                    'preparing',
                    'packing',
                    'ready',
                    'on_the_way',
                    'bus_arrived'
                ]);

            if ($selectedBusId) {
                $activeQuery->where('bus_id', $selectedBusId);
            }

            $activeOrders = $activeQuery
                ->orderBy('created_at', 'desc')
                ->get();

            $pastQuery = \App\Models\Order::with([
                'items.menuItem',
                'bus.busRoute',
                'user',
                'driverUser'
            ])
            ->where('restaurant_id', $restaurant->id)
            ->whereIn('order_status', [
                'completed',
                'cancelled',
                'rejected'
            ]);

            if ($selectedBusId) {
                $pastQuery->where('bus_id', $selectedBusId);
            }

            $pastOrders = $pastQuery
                ->orderBy('created_at', 'desc')
                ->get();

            $menus = \App\Models\Menu::where('restaurant_id', $restaurant->id)
                ->where('availability', 1)
                ->get();
        }

        return view('vendor.orders', compact(
            'activeOrders',
            'pastOrders',
            'menus',
            'buses',
            'selectedBusId'
        ));
    }
    public function payments()
    {
        $restaurant = auth()->user()->restaurant;
        if (!$restaurant) {
            $payments = collect();
        } else {
            $payments = \App\Models\Order::with('items.menuItem', 'user')
                            ->where('restaurant_id', $restaurant->id)
                            ->orderBy('created_at', 'desc')
                            ->get();
        }

        return view('vendor.payments', compact('payments'));
    }

    public function analytics(\Illuminate\Http\Request $request)
    {
        $restaurant = auth()->user()->restaurant;

        $totalRevenue = 0;
        $totalOrders = 0;
        $avgOrderValue = 0;
        $totalViews = 0;
        $topItems = collect();
        $chartData = ['labels' => [], 'data' => []];

        $startDate = $request->input('start_date', \Carbon\Carbon::now()->subDays(6)->format('Y-m-d'));
        $endDate = $request->input('end_date', \Carbon\Carbon::now()->format('Y-m-d'));

        if ($restaurant) {
            $totalViews = $restaurant->views;

            $totalRevenue = \App\Models\Order::where('restaurant_id', $restaurant->id)
                ->whereIn('order_status', ['completed', 'delivered'])
                ->sum('total_amount');

            $totalOrders = \App\Models\Order::where('restaurant_id', $restaurant->id)
                ->whereNotIn('order_status', ['cancelled', 'rejected'])
                ->count();

            $avgOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

            // Getting top items by join on order_items
            $topItems = \App\Models\OrderItem::select('menus.item_name as name', \DB::raw('SUM(order_items.quantity) as total_sold'))
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->join('menus', 'order_items.menu_id', '=', 'menus.id')
                ->where('orders.restaurant_id', $restaurant->id)
                ->whereIn('orders.order_status', ['completed', 'delivered'])
                ->groupBy('menus.id', 'menus.item_name')
                ->orderByDesc('total_sold')
                ->limit(3)
                ->get();

            // Revenue Trend Date Range
            $start = \Carbon\Carbon::parse($startDate);
            $end = \Carbon\Carbon::parse($endDate);

            if ($start->diffInDays($end) > 31) {
                $start = $end->copy()->subDays(30);
                $startDate = $start->format('Y-m-d');
            }

            for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                $chartData['labels'][] = $date->format('M d');

                $dailyRevenue = \App\Models\Order::where('restaurant_id', $restaurant->id)
                    ->whereIn('order_status', ['completed', 'delivered'])
                    ->whereDate('created_at', $date->format('Y-m-d'))
                    ->sum('total_amount');

                $chartData['data'][] = $dailyRevenue;
            }
        }

        return view('vendor.analytics', compact(
            'totalRevenue', 'totalOrders', 'avgOrderValue', 'totalViews', 'topItems', 'chartData', 'startDate', 'endDate'
        ));
    }

    public function transactions()
    {
        $restaurant = auth()->user()->restaurant;
        if (!$restaurant) {
            return redirect()->back();
        }

        $transactions = \App\Models\Transaction::where('user_type', 'vendor')
            ->whereIn('user_id', [$restaurant->id, $restaurant->user_id])
            ->orderByDesc('created_at')
            ->get();

        return view('vendor.transactions', compact('transactions'));
    }



    public function kyc()
    {
        $restaurant = auth()->user()->restaurant;
        return view('vendor.kyc', compact('restaurant'));
    }

    public function profile()
    {
        $restaurant = auth()->user()->restaurant;
        return view('vendor.profile', compact('restaurant'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $restaurant = $user->restaurant;

        if (!$restaurant) {
            $restaurant = new \App\Models\Restaurant();
            $restaurant->user_id = $user->id;
        }

        // Validate
        $request->validate([
            'restaurant_name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'address' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'opening_time' => 'nullable|string',
            'closing_time' => 'nullable|string',
            'cuisines' => 'nullable|string',
            'logo' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,jfif,avif|max:5120',
            'banner' => 'nullable|mimes:jpeg,png,jpg,gif,svg,webp,jfif,avif|max:10240',
            'bank_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'ifsc_code' => 'nullable|string|max:255',
            'branch_name' => 'nullable|string|max:255',
        ]);

        $restaurant->restaurant_name = $request->restaurant_name;
        // The migration requires owner_name, let's just use user's name if null
        if(!$restaurant->owner_name) {
            $restaurant->owner_name = $user->name;
        }
        $restaurant->mobile = $request->mobile;
        $restaurant->address = $request->address;
        $restaurant->latitude = $request->latitude;
        $restaurant->longitude = $request->longitude;
        $restaurant->opening_time = $request->opening_time;
        $restaurant->closing_time = $request->closing_time;
        $restaurant->cuisines = $request->cuisines;
        $restaurant->bank_name = $request->bank_name;
        $restaurant->account_number = $request->account_number;
        $restaurant->ifsc_code = $request->ifsc_code;
        $restaurant->branch_name = $request->branch_name;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('restaurants/logos', 'public');
            $restaurant->logo = $logoPath;
        }

        if ($request->hasFile('banner')) {
            $bannerPath = $request->file('banner')->store('restaurants/banners', 'public');
            $restaurant->banner = $bannerPath;
        }

        $restaurant->save();

        // Also update User name and mobile just in case
        $user->name = $request->restaurant_name;
        $user->mobile = $request->mobile;
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!\Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The provided password does not match your current password.']);
        }

        $user->password = \Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function updateBankDetails(Request $request)
    {
        $request->validate([
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:255',
            'branch_name' => 'required|string|max:255',
        ]);

        $restaurant = auth()->user()->restaurant;
        if ($restaurant) {
            $restaurant->bank_name = $request->bank_name;
            $restaurant->account_number = $request->account_number;
            $restaurant->ifsc_code = $request->ifsc_code;
            $restaurant->branch_name = $request->branch_name;
            $restaurant->save();
        }

        return redirect()->back()->with('success', 'Bank details updated successfully.');
    }

    public function support()
    {
        $tickets = \App\Models\SupportTicket::where('customer_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('vendor.support', compact('tickets'));
    }

    public function tickets()
    {
        $tickets = \App\Models\SupportTicket::where('customer_id', auth()->id())->latest()->get();
        return view('vendor.support-tickets', compact('tickets'));
    }

    public function storeTicketNew(Request $request)
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

    public function storeTicket(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        \App\Models\SupportTicket::create([
            'ticket_number' => 'TKT-' . rand(10000, 99999),
            'customer_id' => auth()->id(),
            'category' => $request->category,
            'issue' => "Subject: " . $request->subject . "\n\n" . $request->description,
            'priority' => 'medium',
            'status' => 'open'
        ]);

        return redirect()->route('vendor.support')->with('success', 'Support ticket submitted successfully. We will get back to you soon.');
    }

    public function reviews()
    {
        $restaurant = auth()->user()->restaurant;
        $reviews = collect();
        $averageRating = 0;
        $totalReviews = 0;

        if ($restaurant) {
            // Assuming we have a Review model or at least a reviews table
            if (class_exists(\App\Models\Review::class)) {
                $reviews = \App\Models\Review::with(['user', 'order'])
                    ->where('restaurant_id', $restaurant->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                $averageRating = \App\Models\Review::where('restaurant_id', $restaurant->id)->avg('rating') ?: 0;
                $totalReviews = \App\Models\Review::where('restaurant_id', $restaurant->id)->count();
            } else {
                // Fallback to DB facade if model doesn't exist
                $reviews = \Illuminate\Support\Facades\DB::table('reviews')
                    ->join('users', 'reviews.user_id', '=', 'users.id')
                    ->select('reviews.*', 'users.name as user_name')
                    ->where('restaurant_id', $restaurant->id)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                $averageRating = \Illuminate\Support\Facades\DB::table('reviews')
                    ->where('restaurant_id', $restaurant->id)
                    ->avg('rating') ?: 0;
                $totalReviews = \Illuminate\Support\Facades\DB::table('reviews')
                    ->where('restaurant_id', $restaurant->id)
                    ->count();
            }
        }

        return view('vendor.reviews', compact('reviews', 'averageRating', 'totalReviews'));
    }

    public function checkNotifications()
    {
        $user = auth()->user();
        $restaurant = $user ? $user->restaurant : null;

        if (!$user) return response()->json(['messages' => 0, 'new_orders' => 0]);

        $newMessagesCount = \App\Models\Message::where('user_id', $user->id)
                                ->where('is_admin', true)
                                ->where('is_read', false)
                                ->count();

        if ($restaurant) {
            $latestNewOrder = \App\Models\Order::where('restaurant_id', $restaurant->id)
                ->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                })
                ->where('order_status', 'pending')
                ->max('created_at');

            $newOrdersCount = \App\Models\Order::where('restaurant_id', $restaurant->id)
                ->where(function ($q) {
                    $q->where('payment_method', 'cod')
                        ->orWhere('payment_status', 'paid');
                })
                ->where('order_status', 'pending')
                ->count();
            $latestOrderUpdate = \App\Models\Order::where('restaurant_id', $restaurant->id)
                                ->max('updated_at');
        } else {
            $latestNewOrder = null;
            $newOrdersCount = 0;
            $latestOrderUpdate = null;
        }

        return response()->json([
            'messages' => $newMessagesCount,
            'new_orders' => $newOrdersCount,
            'latest_order_time' => $latestNewOrder,
            'latest_order_update' => $latestOrderUpdate
        ]);
    }
}

