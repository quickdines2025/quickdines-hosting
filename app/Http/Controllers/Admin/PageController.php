<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function placeholder(Request $request)
    {
        // Use the route name to determine the page title
        $routeName = $request->route()->getName(); // e.g. admin.routes.index
        
        $title = 'Feature In Development';
        
        if (str_contains($routeName, 'routes')) $title = 'Routes Management';
        if (str_contains($routeName, 'qr')) $title = 'QR Code Management';
        if (str_contains($routeName, 'restaurants.assign')) $title = 'Restaurant Assignment';
        if (str_contains($routeName, 'categories')) $title = 'Menu Categories';
        if (str_contains($routeName, 'customers')) $title = 'Customer Management';
        if (str_contains($routeName, 'reviews')) $title = 'Reviews & Ratings';
        if (str_contains($routeName, 'drivers.assign')) $title = 'Driver Assignment';
        if (str_contains($routeName, 'marketing')) $title = 'Marketing & Banners';
        if (str_contains($routeName, 'coupons')) $title = 'Coupons & Offers';
        if (str_contains($routeName, 'support')) $title = 'Support Tickets';
        if (str_contains($routeName, 'roles')) $title = 'Role Management';

        return view('admin.placeholder', compact('title'));
    }

    public function supportTickets(Request $request)
    {
        // Mark all unread tickets as read when the admin views the tickets page
        \App\Models\SupportTicket::where('admin_read', false)->update(['admin_read' => true]);

        $query = \App\Models\SupportTicket::with('customer')->orderBy('created_at', 'desc');

        // Stats
        $stats = [
            'total' => \App\Models\SupportTicket::count(),
            'open' => \App\Models\SupportTicket::where('status', 'open')->count(),
            'in_progress' => \App\Models\SupportTicket::where('status', 'in progress')->count(),
            'resolved' => \App\Models\SupportTicket::where('status', 'resolved')->count(),
            'closed' => \App\Models\SupportTicket::where('status', 'closed')->count(),
        ];

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('issue', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($q2) use ($search) {
                      $q2->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        if ($request->filled('status') && $request->status !== 'All Status') {
            $query->where('status', strtolower($request->status));
        }
        if ($request->filled('priority') && $request->priority !== 'All Priority') {
            $query->where('priority', strtolower($request->priority));
        }
        if ($request->filled('category') && $request->category !== 'All Categories') {
            $query->where('category', $request->category);
        }

        $tickets = $query->paginate(25);

        return view('admin.support.index', compact('tickets', 'stats'));
    }

    public function showTicket($id)
    {
        $ticket = \App\Models\SupportTicket::with(['customer', 'replies.user'])->findOrFail($id);
        return view('admin.support.show', compact('ticket'));
    }

    public function updateTicketStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,in progress,resolved,closed'
        ]);
        
        $ticket = \App\Models\SupportTicket::findOrFail($id);
        $ticket->update(['status' => strtolower($request->status)]);

        return redirect()->back()->with('success', 'Ticket status updated successfully');
    }

    public function destroyTicket($id)
    {
        $ticket = \App\Models\SupportTicket::findOrFail($id);
        $ticket->delete();
        return redirect()->back()->with('success', 'Ticket deleted successfully.');
    }

    public function reports()
    {
        // 1. Top Level Stats
        $totalOrders = \App\Models\Order::count();
        $totalRevenue = \App\Models\Order::where('order_status', '!=', 'cancelled')->sum('total_amount');
        $totalCustomers = \App\Models\User::where('role', 'customer')->count();
        if ($totalCustomers == 0) $totalCustomers = \App\Models\User::count(); // Fallback if no roles
        $totalRestaurants = \App\Models\Restaurant::count();
        $totalDrivers = \App\Models\Driver::count();

        // 2. Chart Data (Last 7 Days)
        $dates = [];
        $ordersData = [];
        $revenueData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $dates[] = now()->subDays($i)->format('M d');
            
            $ordersData[] = \App\Models\Order::whereDate('created_at', $date)->count();
            $revenueData[] = \App\Models\Order::whereDate('created_at', $date)
                                ->where('order_status', '!=', 'cancelled')
                                ->sum('total_amount');
        }

        // 3. Orders by Status Donut
        $orderStatuses = \App\Models\Order::selectRaw('order_status, count(*) as count')
                                ->groupBy('order_status')
                                ->pluck('count', 'order_status')->toArray();
        if (empty($orderStatuses)) {
            $orderStatuses = ['Completed' => 0, 'Pending' => 0, 'Cancelled' => 0];
        }

        // 4. Revenue by Payment Method Donut
        // If transactions exist, use them, otherwise mock it for now based on orders
        $paymentMethods = \App\Models\Transaction::selectRaw('payment_method, sum(amount) as total')
                                ->where('status', 'completed')
                                ->groupBy('payment_method')
                                ->pluck('total', 'payment_method')->toArray();
        if (empty($paymentMethods)) {
            $paymentMethods = ['UPI' => 0, 'Card' => 0, 'Cash' => 0];
        }

        // 5. Top Restaurants & Drivers
        $topRestaurants = \App\Models\Restaurant::withCount('orders')
                            ->orderBy('orders_count', 'desc')
                            ->take(5)->get();
                            
        $topDrivers = \App\Models\Driver::with('user')
                        ->withCount('orders')
                        ->orderBy('orders_count', 'desc')
                        ->take(5)->get();

        return view('admin.reports.index', compact(
            'totalOrders', 'totalRevenue', 'totalCustomers', 'totalRestaurants', 'totalDrivers',
            'dates', 'ordersData', 'revenueData', 'orderStatuses', 'paymentMethods',
            'topRestaurants', 'topDrivers'
        ));
    }

    public function roles()
    {
        return view('admin.roles.index');
    }

    public function settings()
    {
        return view('admin.settings.index');
    }

    public function checkNotifications()
    {
        $currentUser = auth()->user();
        $lastNotifView = $currentUser ? $currentUser->last_viewed_notifications_at : null;
        
        $pendingRestaurants = \App\Models\Restaurant::where('status', 0)->latest()->get();
        $pendingDrivers = \App\Models\Driver::where('status', 0)->latest()->get();
        $latestCustomer = \App\Models\User::where('role', 'customer')->latest()->first();
        
        $newNotificationsCount = 0;
        foreach($pendingRestaurants as $r) {
            if(!$lastNotifView || $r->created_at > $lastNotifView) $newNotificationsCount++;
        }
        foreach($pendingDrivers as $d) {
            if(!$lastNotifView || $d->created_at > $lastNotifView) $newNotificationsCount++;
        }
        if ($latestCustomer && $latestCustomer->created_at->diffInDays(now()) < 1) {
            if(!$lastNotifView || $latestCustomer->created_at > $lastNotifView) $newNotificationsCount++;
        }
        
        $newMessagesCount = \App\Models\Message::where('is_admin', false)->where('is_read', false)->count();
        
        return response()->json([
            'notifications' => $newNotificationsCount,
            'messages' => $newMessagesCount
        ]);
    }

    public function markNotificationsRead()
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['last_viewed_notifications_at' => now()]);
        }
        return response()->json(['success' => true]);
    }

    public function markMessagesRead()
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['last_viewed_messages_at' => now()]);
        }
        return response()->json(['success' => true]);
    }

}
