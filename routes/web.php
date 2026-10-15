<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\BusController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Auth\CustomerAuthController;

use App\Http\Controllers\Admin\VendorCommissionSettingController;

// Public QR Code Scanner Route
Route::get('/scan/{qr_code}', [\App\Http\Controllers\QrScanController::class, 'scan'])->name('qr.scan');
Route::get('/qr-entry', [\App\Http\Controllers\QrScanController::class, 'entry'])->name('qr.entry');
Route::get('/qr-select-restaurant/{id}', [\App\Http\Controllers\QrScanController::class, 'selectRestaurant'])->name('qr.select-restaurant');
Route::get('/qr-change-restaurant', [\App\Http\Controllers\QrScanController::class, 'changeRestaurant'])->name('qr.change-restaurant');

// Customer OTP Authentication Routes
Route::get('/customer/auth', [CustomerAuthController::class, 'showAuthForm'])->name('customer.auth');
Route::post('/customer/send-otp', [CustomerAuthController::class, 'sendOtp'])->name('customer.send_otp');
Route::post('/customer/verify-otp', [CustomerAuthController::class, 'verifyOtp'])->name('customer.verify_otp');
Route::post('/customer/login-password', [CustomerAuthController::class, 'loginWithPassword'])->name('customer.login_password');



Route::get('/', function () {
    $busId = session('scanned_bus_id');
    $scannedBus = null;
    $restaurant = null;
    $groupedMenus = collect();
    $reviews = collect();
    $busRestaurants = collect();

    if ($busId) {
        $scannedBus = \App\Models\Bus::find($busId);

        if (!$scannedBus) {
            session()->forget(['scanned_bus_id', 'selected_restaurant_id']);
            return redirect('/');
        }

        $busRestaurants = $scannedBus->restaurants()
            ->where('restaurants.status', 1)
            ->orderBy('restaurants.restaurant_name')
            ->get();

        if ($busRestaurants->count() === 1) {
            // Only one restaurant on this bus: show it directly, no selection screen
            $restaurant = $busRestaurants->first();
            session()->forget('selected_restaurant_id');
        } elseif ($busRestaurants->count() > 1) {
            $selectedId = session('selected_restaurant_id');
            $restaurant = $selectedId ? $busRestaurants->firstWhere('id', $selectedId) : null;

            // Nothing picked yet: keep the existing restaurant-selection screen
            if (!$restaurant) {
                return view('qr.restaurants', ['bus' => $scannedBus, 'restaurants' => $busRestaurants]);
            }
        }
        // 0 restaurants: $restaurant stays null, welcome.blade.php shows the "no restaurants" message
    }

    if ($restaurant) {
        $restaurant->increment('views');

        $menus = \App\Models\Menu::where('restaurant_id', $restaurant->id)
            ->where('availability', 1)
            ->get();

        $groupedMenus = $menus->groupBy(function ($menu) {
            return $menu->category ?: 'Recommended';
        });

        $reviews = \App\Models\Review::where('restaurant_id', $restaurant->id)->latest()->get();
    }

    // Normal (non-QR) homepage content stays exactly as before
    if (!$busId) {
        $restaurants = \App\Models\Restaurant::where('status', 1)->latest()->take(12)->get();
        $allMenus = \App\Models\Menu::with('restaurant')->where('availability', 1)->inRandomOrder()->get()->groupBy('restaurant_id');
    } else {
        $restaurants = collect();
        $allMenus = collect();
    }

    // Fetch all menus randomly, then group by restaurant to ensure a fair mix
    $recentMenus = collect();
    while ($recentMenus->count() < 16 && $allMenus->isNotEmpty()) {
        foreach ($allMenus as $key => $group) {
            if ($group->isNotEmpty()) {
                $recentMenus->push($group->shift());
            } else {
                $allMenus->forget($key);
            }
            if ($recentMenus->count() >= 16) break 2;
        }
    }

    return view('welcome', compact(
        'restaurants', 'recentMenus', 'scannedBus',
        'restaurant', 'groupedMenus', 'reviews', 'busRestaurants'
    ));
})->name('home');

// QuickDines Frontend Routes
Route::get('/restaurant/{id}', [\App\Http\Controllers\Frontend\RestaurantController::class, 'show'])->name('restaurant.show');
Route::get('/search', [\App\Http\Controllers\Frontend\RestaurantController::class, 'search'])->name('search');




// Route::get('/restaurants', [\App\Http\Controllers\QrScanController::class, 'restaurants'])->name('restaurants.index');




Route::get('/restaurants', [\App\Http\Controllers\QrScanController::class, 'restaurants'])->name('restaurants.index');




Route::get('/restaurants/{slug}', function ($slug) {
    return view('restaurants.show', compact('slug'));
})->name('restaurants.show');

Route::post('/checkout/cart', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'cart_data' => 'required|string',
    ]);

    $cartData = json_decode($request->cart_data, true);
    if (!is_array($cartData) || empty($cartData['items']) || !is_array($cartData['items'])) {
        return response()->json(['message' => 'Cart is empty or invalid.'], 422);
    }

    // Keep a server-side copy while moving from the restaurant page to checkout.
    // The CheckoutController still validates every menu item and recalculates the amount.
    // QR customers may only order from restaurants assigned to their bus
    $busId = session('scanned_bus_id');
    if ($busId) {
        $allowed = \App\Models\Bus::find($busId)?->restaurants()->where('restaurants.status', 1)->pluck('restaurants.id')->all() ?? [];
        foreach ($cartData['items'] as $item) {
            $menu = \App\Models\Menu::find((int) ($item['id'] ?? 0));
            if (!$menu || !in_array((int) $menu->restaurant_id, $allowed, true)) {
                return response()->json(['message' => 'This restaurant is not available for your bus.'], 403);
            }
        }
    }

    session(['checkout_cart' => $cartData]);

    return response()->json(['success' => true, 'redirect_url' => route('checkout')]);
})->name('checkout.cart');

Route::get('/checkout', function () {
    $busId = session('scanned_bus_id') ?: auth()->user()?->bus_id;
    $bus = null;
    if ($busId) {
        $bus = \App\Models\Bus::with('busRoute.stops')->find($busId);
    }
    $checkoutCart = session('checkout_cart');
    return view('checkout.index', compact('bus', 'checkoutCart'));
})->name('checkout');

//old
 Route::post('/checkout/process', [\App\Http\Controllers\Frontend\CheckoutController::class, 'process'])->name('checkout.process');

 //new
// Route::post('/checkout/process', [\App\Http\Controllers\Frontend\CheckoutController::class, 'process'])
//     ->middleware('auth')
//     ->name('checkout.process');


Route::post('/checkout/create-payment-order', [\App\Http\Controllers\Frontend\CheckoutController::class, 'createPaymentOrder'])->middleware('auth')->name('checkout.create-payment-order');
Route::post('/checkout/verify-payment', [\App\Http\Controllers\Frontend\CheckoutController::class, 'verifyPayment'])->middleware('auth')->name('checkout.verify-payment');
Route::post('/checkout/cancel-payment', [\App\Http\Controllers\Frontend\CheckoutController::class, 'cancelPayment'])->middleware('auth')->name('checkout.cancelPayment');

Route::get('/order-confirmed/{id?}', function ($id = null) {
    $order = $id ? App\Models\Order::find($id) : App\Models\Order::latest()->first();
    return view('checkout.success', compact('order'));
})->name('checkout.success');

Route::get('/api/order-status/{id}', function ($id) {
    $order = App\Models\Order::find($id);
    return response()->json(['status' => $order ? strtolower($order->order_status) : 'unknown']);
});

Route::get('/track-bus', function (Illuminate\Http\Request $request) {
    $bus = null;
    $searched = false;

    if ($request->has('bus_number')) {
        $searched = true;
        $bus = \App\Models\Bus::with(['busRoute.stops', 'currentStop'])
            ->where('bus_number', $request->bus_number)
            ->first();
    }

    return view('pages.track-bus', compact('bus', 'searched'));
})->name('track-bus');

Route::get('/how-it-works', function () {
    return view('pages.how-it-works');
})->name('how-it-works');

Route::get('/offers', function () {
    return view('pages.offers');
})->name('offers');

Route::get('/partner', function () {
    return view('pages.partner');
})->name('partner');

Route::get('/support', function () {
    return view('pages.support');
})->name('support');

Route::get('/dashboard', function () {
    $user = auth()->user();

    // Redirect non-customer roles to their respective dashboards
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'vendor') {
        return redirect()->route('vendor.dashboard');
    } elseif ($user->role === 'driver') {
        return redirect()->route('driver.dashboard');
    } elseif ($user->role === 'support') {
        return redirect()->route('support.dashboard');
    }

    $orders = \App\Models\Order::with(['restaurant', 'bus', 'items'])
        ->where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->get();

    $activeOrder = \App\Models\Order::with(['restaurant', 'bus', 'items'])
        ->where('user_id', auth()->id())
        ->whereIn('order_status', ['placed', 'preparing', 'ready', 'out_for_delivery'])
        ->orderBy('created_at', 'desc')
        ->first();

    return view('dashboard', compact('orders', 'activeOrder'));
})->middleware(['auth'])->name('dashboard');

Route::get('/track-order', function() {
    return view('frontend.track-order');
})->middleware(['auth'])->name('track-order');

Route::get('/favourites', function() {
    return view('frontend.favorites');
})->middleware(['auth'])->name('favourites');

Route::get('/profile', function() {
    return view('frontend.profile');
})->middleware(['auth'])->name('profile');
Route::post('/profile/update', [\App\Http\Controllers\Frontend\CustomerController::class, 'updateProfile'])->middleware(['auth'])->name('profile.update');
Route::post('/favorite/toggle', [\App\Http\Controllers\Frontend\CustomerController::class, 'toggleFavorite'])->name('favorite.toggle');
Route::post('/logout', [\App\Http\Controllers\Frontend\CustomerController::class, 'logout'])->name('customer.logout');
Route::post('/orders/{order}/review', [\App\Http\Controllers\Frontend\CustomerController::class, 'storeReview'])->middleware(['auth'])->name('customer.orders.review');
Route::post('/orders/{id}/cancel', [\App\Http\Controllers\Frontend\CustomerController::class, 'cancelOrder'])->middleware(['auth'])->name('customer.order.cancel');

Route::get('/settings', function() {
    return view('frontend.settings');
})->middleware(['auth'])->name('settings');

Route::post('/settings/notifications', [\App\Http\Controllers\Frontend\CustomerController::class, 'updateNotifications'])->middleware(['auth'])->name('settings.notifications');
Route::post('/settings/password', [\App\Http\Controllers\Frontend\CustomerController::class, 'updatePassword'])->middleware(['auth'])->name('settings.password');
Route::post('/settings/2fa', [\App\Http\Controllers\Frontend\CustomerController::class, 'toggle2FA'])->middleware(['auth'])->name('settings.2fa');
Route::post('/settings/logout-devices', [\App\Http\Controllers\Frontend\CustomerController::class, 'logoutDevices'])->middleware(['auth'])->name('settings.logout-devices');
Route::post('/settings/delete-account', [\App\Http\Controllers\Frontend\CustomerController::class, 'deleteAccount'])->middleware(['auth'])->name('settings.delete-account');

Route::get('/customer-support', function() {
    return view('frontend.customer-support');
})->middleware(['auth'])->name('customer.support');

Route::post('/customer-support/ticket', [\App\Http\Controllers\Frontend\CustomerController::class, 'storeTicket'])->middleware(['auth'])->name('customer.support.ticket.store');
Route::get('/customer-tickets', [\App\Http\Controllers\Frontend\CustomerController::class, 'tickets'])->middleware(['auth'])->name('customer.tickets');

Route::get('/chat/messages', [\App\Http\Controllers\ChatController::class, 'fetchMessages'])->middleware(['auth'])->name('chat.fetch');
Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'sendMessage'])->middleware(['auth'])->name('chat.send');
Route::get('/notifications/check', [\App\Http\Controllers\Frontend\CustomerController::class, 'checkNotifications'])->middleware(['auth'])->name('customer.notifications.check');

use App\Http\Controllers\Vendor\DashboardController as VendorDashboard;
use App\Http\Controllers\Vendor\MenuController as VendorMenu;
use App\Http\Controllers\Vendor\WalletController as VendorWallet;
use App\Http\Controllers\Vendor\OrderController as VendorOrder;

// Vendor Routes
Route::middleware(['auth', 'role:vendor'])->prefix('vendor')->name('vendor.')->group(function () {
    Route::get('/', [VendorDashboard::class, 'index'])->name('dashboard');

    // Menus
    Route::resource('menus', VendorMenu::class)->except(['create', 'show', 'edit']);
    Route::post('menus/{menu}/toggle', [VendorMenu::class, 'toggleAvailability'])->name('menus.toggle');

    // Wallet
    Route::get('wallet', [VendorWallet::class, 'index'])->name('wallet.index');
    Route::post('wallet/withdraw', [VendorWallet::class, 'withdraw'])->name('wallet.withdraw');

    // Orders Actions
    Route::post('orders/{order}/status', [VendorOrder::class, 'updateStatus'])->name('orders.status');
    Route::get('orders/{order}/kot', [VendorOrder::class, 'printKot'])->name('orders.kot');

    // Live driver tracking for restaurant orders
    Route::get('tracking/order/{id}', [\App\Http\Controllers\Vendor\TrackingController::class, 'order'])->name('tracking.order');

    // New Vendor Panel Pages
    Route::get('/tracking', [\App\Http\Controllers\Vendor\PageController::class, 'tracking'])->name('tracking');
    Route::get('/orders', [\App\Http\Controllers\Vendor\PageController::class, 'orders'])->name('orders.index');
    Route::get('/payments', [\App\Http\Controllers\Vendor\PageController::class, 'payments'])->name('payments');
    Route::post('/payments/{id}/mark-paid', [VendorOrder::class, 'markPaid'])->name('payments.mark');
    Route::get('/analytics', [\App\Http\Controllers\Vendor\PageController::class, 'analytics'])->name('analytics');
    Route::get('/transactions', [\App\Http\Controllers\Vendor\PageController::class, 'transactions'])->name('transactions');
    Route::get('/settlements', [\App\Http\Controllers\Vendor\SettlementController::class, 'index'])->name('settlements');
    Route::get('/kyc', [\App\Http\Controllers\Vendor\PageController::class, 'kyc'])->name('kyc');
    Route::post('/kyc/bank', [\App\Http\Controllers\Vendor\PageController::class, 'updateBankDetails'])->name('kyc.bank');
    Route::get('/profile', [\App\Http\Controllers\Vendor\PageController::class, 'profile'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Vendor\PageController::class, 'updateProfile'])->name('profile.update');
    Route::post('/profile/password', [\App\Http\Controllers\Vendor\PageController::class, 'updatePassword'])->name('profile.password');
    Route::get('/reviews', [\App\Http\Controllers\Vendor\PageController::class, 'reviews'])->name('reviews');
    Route::get('/support', [\App\Http\Controllers\Vendor\PageController::class, 'support'])->name('support');
    Route::post('/support', [\App\Http\Controllers\Vendor\PageController::class, 'storeTicket'])->name('support.store');
    Route::get('/tickets', [\App\Http\Controllers\Vendor\PageController::class, 'tickets'])->name('tickets');
    Route::post('/tickets', [\App\Http\Controllers\Vendor\PageController::class, 'storeTicketNew'])->name('tickets.store');
    // Notifications
    Route::get('/notifications/check', [\App\Http\Controllers\Vendor\PageController::class, 'checkNotifications'])->name('notifications.check');
});

use App\Http\Controllers\Driver\DashboardController as DriverDashboard;
use App\Http\Controllers\Driver\WalletController as DriverWallet;
use App\Http\Controllers\Auth\DriverAuthController;

Route::get('/driver/login', [DriverAuthController::class, 'showLoginForm'])->name('driver.login.form');
Route::post('/driver/login', [DriverAuthController::class, 'login'])->name('driver.login');

// Driver Routes
Route::middleware(['auth', 'role:driver'])->prefix('driver')->name('driver.')->group(function () {
    Route::get('/', [DriverDashboard::class, 'index'])->name('dashboard');
    Route::get('/notifications/check', [DriverDashboard::class, 'checkNotifications'])->name('notifications.check');
    Route::post('/location', [DriverDashboard::class, 'updateLocation'])->name('location.update'); // For GPS Sharing
    Route::post('update-journey-status', [\App\Http\Controllers\Driver\DashboardController::class, 'updateJourneyStatus'])->name('journey.status');
    Route::post('route/reach-stop', [\App\Http\Controllers\Driver\DashboardController::class, 'reachStop'])->name('route.reach-stop');
    Route::post('route/report-delay', [\App\Http\Controllers\Driver\DashboardController::class, 'reportDelay'])->name('route.report-delay');
    Route::get('/wallet', [DriverWallet::class, 'index'])->name('wallet.index');

    // New Driver Panel Pages
    Route::get('/live-location', [\App\Http\Controllers\Driver\PageController::class, 'location'])->name('location.index');
    Route::get('/orders', [\App\Http\Controllers\Driver\PageController::class, 'orders'])->name('orders.index');
    Route::get('/passengers', [\App\Http\Controllers\Driver\PageController::class, 'passengers'])->name('passengers.index');
    Route::get('/notifications', [\App\Http\Controllers\Driver\PageController::class, 'notifications'])->name('notifications.index');
    Route::get('/earnings', [\App\Http\Controllers\Driver\PageController::class, 'earnings'])->name('earnings.index');
    Route::post('/wallet/bank-details', [\App\Http\Controllers\Driver\PageController::class, 'updateBankDetails'])->name('wallet.bank_details');
    Route::post('/wallet/withdraw', [\App\Http\Controllers\Driver\PageController::class, 'withdraw'])->name('wallet.withdraw');
    Route::get('/co-drivers', [\App\Http\Controllers\Driver\PageController::class, 'codrivers'])->name('codrivers.index');
    Route::get('/profile', [\App\Http\Controllers\Driver\PageController::class, 'profile'])->name('profile.index');
    Route::post('/profile', [\App\Http\Controllers\Driver\PageController::class, 'updateProfile'])->name('profile.update');
    Route::get('/support', [\App\Http\Controllers\Driver\PageController::class, 'support'])->name('support.index');
    Route::post('/support/ticket', [\App\Http\Controllers\Driver\PageController::class, 'storeTicket'])->name('support.store');
    Route::get('/chat', [\App\Http\Controllers\Driver\PageController::class, 'chat'])->name('chat.index');
    Route::get('/api/notifications', [\App\Http\Controllers\Driver\PageController::class, 'apiNotifications'])->name('api.notifications');
});

use App\Http\Controllers\Support\DashboardController as SupportDashboard;
use App\Http\Controllers\Support\TicketController as SupportTicket;
use App\Http\Controllers\Support\VerificationController as SupportVerification;

// Support Team Routes
Route::middleware(['auth', 'role:support'])->prefix('support-panel')->name('support.')->group(function () {
    Route::get('/', [SupportDashboard::class, 'index'])->name('dashboard');
    Route::get('/notifications/check', [SupportDashboard::class, 'checkNotifications'])->name('notifications.check');
    Route::post('/delay-notify/{bus}', [SupportDashboard::class, 'notifyDelay'])->name('notify-delay');

    // Live Chat (Shared with Admin Panel codebase)
    Route::get('/chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/users', [\App\Http\Controllers\Admin\ChatController::class, 'fetchUsers'])->name('chat.users');
    Route::get('/chat/{user_id}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('/chat/{user_id}/send', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::delete('/chat/{user_id}/delete', [\App\Http\Controllers\Admin\ChatController::class, 'deleteChat'])->name('chat.delete');

    // Support Tickets (Shared with Admin Panel codebase)
    Route::get('/tickets', [\App\Http\Controllers\Admin\PageController::class, 'supportTickets'])->name('tickets.index');
    Route::get('/tickets/{id}', [\App\Http\Controllers\Admin\PageController::class, 'showTicket'])->name('tickets.show');
    Route::post('/tickets/{id}/status', [\App\Http\Controllers\Admin\PageController::class, 'updateTicketStatus'])->name('tickets.status');
    Route::delete('/tickets/{id}', [\App\Http\Controllers\Admin\PageController::class, 'destroyTicket'])->name('tickets.destroy');

    Route::get('/verification/restaurants', [SupportVerification::class, 'restaurantVerification'])->name('verification.restaurants');
    Route::get('/verification/drivers', [SupportVerification::class, 'driverVerification'])->name('verification.drivers');
    Route::post('/verification/restaurant/{id}/approve', [SupportVerification::class, 'verifyRestaurant'])->name('verification.restaurant.approve');
    Route::post('/verification/restaurant/{id}/reject', [SupportVerification::class, 'rejectRestaurant'])->name('verification.restaurant.reject');
    Route::post('/verification/driver/{id}/approve', [SupportVerification::class, 'verifyDriver'])->name('verification.driver.approve');
    Route::post('/verification/driver/{id}/reject', [SupportVerification::class, 'rejectDriver'])->name('verification.driver.reject');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Support\SettingsController::class, 'index'])->name('settings');
    Route::post('/settings/profile', [\App\Http\Controllers\Support\SettingsController::class, 'updateProfile'])->name('settings.profile');
    Route::post('/settings/password', [\App\Http\Controllers\Support\SettingsController::class, 'updatePassword'])->name('settings.password');

    // Order Verification
    Route::get('/orders/verification', [SupportDashboard::class, 'orderVerification'])->name('orders.verification');
    Route::post('/orders/{id}/verify', [SupportDashboard::class, 'verifyOrder'])->name('orders.verify');
    Route::post('/buses/{id}/notify-delay', [SupportDashboard::class, 'notifyDelay'])->name('notify-delay');

    // New Feature Placeholder Routes
    Route::get('/orders', function() { return view('support.placeholder', ['title' => 'Order Monitoring']); })->name('orders.monitoring');
    Route::get('/cancellations', [SupportDashboard::class, 'cancellations'])->name('cancellations');
    Route::delete('/cancellations/{id}', [SupportDashboard::class, 'deleteCancellation'])->name('cancellations.delete');
    Route::get('/refunds', [SupportDashboard::class, 'refunds'])->name('refunds');
    Route::post('/refunds/{id}/process', [SupportDashboard::class, 'processRefund'])->name('refunds.process');
    Route::post('/refunds/{id}/reject', [SupportDashboard::class, 'rejectRefund'])->name('refunds.reject');
    Route::get('/complaints', function() { return view('support.placeholder', ['title' => 'Complaint Resolution']); })->name('complaints');
    Route::get('/buses', function() { return view('support.placeholder', ['title' => 'Bus Registration Management']); })->name('buses');
});

use App\Http\Controllers\Admin\FinancialController as AdminFinancial;

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('system-users', [\App\Http\Controllers\Admin\SystemUserController::class, 'index'])->name('system-users.index');
    Route::post('system-users/{id}/reset-password', [\App\Http\Controllers\Admin\SystemUserController::class, 'resetPassword'])->name('system-users.reset-password');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::get('restaurants/pending', [\App\Http\Controllers\Admin\VendorController::class, 'pending'])->name('restaurants.pending');
    Route::post('restaurants/{id}/approve', [\App\Http\Controllers\Admin\VendorController::class, 'approve'])->name('restaurants.approve');
    Route::post('restaurants/{id}/reject', [\App\Http\Controllers\Admin\VendorController::class, 'reject'])->name('restaurants.reject');
    Route::resource('vendors', \App\Http\Controllers\Admin\VendorController::class);
    Route::resource('buses', \App\Http\Controllers\Admin\BusController::class);
    Route::get('buses/{id}/qr', [\App\Http\Controllers\Admin\BusController::class, 'downloadQr'])->name('buses.qr');
    Route::get('buses/{id}/manage-route', [\App\Http\Controllers\Admin\BusController::class, 'manageRoute'])->name('buses.manage-route');
    Route::post('buses/assign-drivers', [\App\Http\Controllers\Admin\BusController::class, 'assignDrivers'])->name('buses.assign-drivers');
    Route::get('drivers/pending', [\App\Http\Controllers\Admin\DriverController::class, 'pending'])->name('drivers.pending');
    Route::post('drivers/{id}/approve', [\App\Http\Controllers\Admin\DriverController::class, 'approve'])->name('drivers.approve');
    Route::post('drivers/{id}/reject', [\App\Http\Controllers\Admin\DriverController::class, 'reject'])->name('drivers.reject');
    Route::delete('drivers/application/{id}', [\App\Http\Controllers\Admin\DriverController::class, 'destroyApplication'])->name('drivers.application.destroy');
    Route::resource('drivers', \App\Http\Controllers\Admin\DriverController::class);
    Route::resource('orders', \App\Http\Controllers\Admin\OrderController::class);
    Route::get('reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports.index');

    // Route Management
    Route::resource('bus-routes', \App\Http\Controllers\Admin\BusRouteController::class);
    Route::post('bus-routes/{id}/stops', [\App\Http\Controllers\Admin\BusRouteController::class, 'storeStop'])->name('bus-routes.stops.store');
    Route::delete('bus-routes/stops/{stop_id}', [\App\Http\Controllers\Admin\BusRouteController::class, 'destroyStop'])->name('bus-routes.stops.destroy');
    Route::post('bus-routes/{id}/delay', [\App\Http\Controllers\Admin\BusRouteController::class, 'submitDelay'])->name('bus-routes.delay');
    Route::get('qr/export', [\App\Http\Controllers\Admin\QrCodeController::class, 'export'])->name('qr.export');
    Route::post('qr/{id}/regenerate', [\App\Http\Controllers\Admin\QrCodeController::class, 'regenerate'])->name('qr.regenerate');
    Route::post('qr/{id}/toggle-status', [\App\Http\Controllers\Admin\QrCodeController::class, 'toggleStatus'])->name('qr.toggle-status');
    Route::get('qr', [\App\Http\Controllers\Admin\QrCodeController::class, 'index'])->name('qr.index');
    Route::get('restaurants/assign', [\App\Http\Controllers\Admin\RestaurantAssignmentController::class, 'index'])->name('restaurants.assign');
    Route::post('restaurants/assign/sync', [\App\Http\Controllers\Admin\RestaurantAssignmentController::class, 'sync'])->name('restaurants.assign.sync');
    Route::post('restaurants/assign/syncRestaurant', [\App\Http\Controllers\Admin\RestaurantAssignmentController::class, 'syncRestaurant'])->name('restaurants.assign.syncRestaurant');
    Route::get('categories', [\App\Http\Controllers\Admin\PageController::class, 'placeholder'])->name('categories.index');
    Route::get('customers', [\App\Http\Controllers\Admin\PageController::class, 'placeholder'])->name('customers.index');
    Route::resource('reviews', \App\Http\Controllers\Admin\ReviewController::class);
    Route::get('marketing', [\App\Http\Controllers\Admin\PageController::class, 'placeholder'])->name('marketing.index');
    Route::get('coupons', [\App\Http\Controllers\Admin\PageController::class, 'placeholder'])->name('coupons.index');
    Route::get('support/tickets', [\App\Http\Controllers\Admin\PageController::class, 'supportTickets'])->name('support.tickets.index');
    Route::get('support/tickets/{id}', [\App\Http\Controllers\Admin\PageController::class, 'showTicket'])->name('support.tickets.show');
    Route::post('support/tickets/{id}/status', [\App\Http\Controllers\Admin\PageController::class, 'updateTicketStatus'])->name('support.tickets.status');
    Route::delete('support/tickets/{id}', [\App\Http\Controllers\Admin\PageController::class, 'destroyTicket'])->name('support.tickets.destroy');
    Route::get('reports', [\App\Http\Controllers\Admin\PageController::class, 'reports'])->name('reports.index');
    Route::get('finance/settlements', [\App\Http\Controllers\Admin\SettlementController::class, 'index'])->name('finance.settlements.index');
    Route::post('finance/settlements/{id}/settle', [\App\Http\Controllers\Admin\SettlementController::class, 'settle'])->name('finance.settlements.settle');
    Route::get('finance/transactions', [\App\Http\Controllers\Admin\FinancePageController::class, 'transactions'])->name('finance.transactions.index');
    Route::get('finance/transactions/export', [\App\Http\Controllers\Admin\FinancePageController::class, 'exportTransactions'])->name('finance.transactions.export');
    Route::get('finance/vendor-payouts', [\App\Http\Controllers\Admin\VendorPayoutController::class, 'index'])->name('finance.vendor-payouts.index');
    Route::get('finance/payouts', [\App\Http\Controllers\Admin\FinancePageController::class, 'payouts'])->name('finance.payouts.index');
    Route::get('finance/payouts/export', [\App\Http\Controllers\Admin\FinancePageController::class, 'exportPayouts'])->name('finance.payouts.export');
    Route::get('finance/commissions', [\App\Http\Controllers\Admin\FinancePageController::class, 'commissions'])->name('finance.commissions.index');
    Route::post('finance/commissions/save', [\App\Http\Controllers\Admin\FinancePageController::class, 'saveCommissions'])->name('finance.commissions.save');

    // Partner Approvals
    Route::get('approvals', [\App\Http\Controllers\Admin\ApprovalController::class, 'index'])->name('approvals.index');
    Route::post('approvals/{type}/{id}/approve', [\App\Http\Controllers\Admin\ApprovalController::class, 'approve'])->name('approvals.approve');
    Route::post('approvals/{type}/{id}/reject', [\App\Http\Controllers\Admin\ApprovalController::class, 'reject'])->name('approvals.reject');

    // Financial
    Route::get('finance', [AdminFinancial::class, 'index'])->name('finance.index');
    Route::post('finance/withdraw/{id}/approve', [AdminFinancial::class, 'approveWithdrawal'])->name('finance.approve');
    Route::post('finance/withdraw/{id}/reject', [AdminFinancial::class, 'rejectWithdrawal'])->name('finance.reject');

    // Notifications and Messages
    Route::get('notifications/check', [\App\Http\Controllers\Admin\PageController::class, 'checkNotifications'])->name('notifications.check');
    Route::post('notifications/mark-read', [\App\Http\Controllers\Admin\PageController::class, 'markNotificationsRead'])->name('notifications.mark-read');
    Route::post('messages/mark-read', [\App\Http\Controllers\Admin\PageController::class, 'markMessagesRead'])->name('messages.mark-read');

    // Admin Live Chat
    Route::get('chat', [\App\Http\Controllers\Admin\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/users', [\App\Http\Controllers\Admin\ChatController::class, 'fetchUsers'])->name('chat.users');
    Route::get('chat/{user_id}/messages', [\App\Http\Controllers\Admin\ChatController::class, 'fetchMessages'])->name('chat.messages');
    Route::post('chat/{user_id}/send', [\App\Http\Controllers\Admin\ChatController::class, 'sendMessage'])->name('chat.send');
    Route::delete('chat/{user_id}/delete', [\App\Http\Controllers\Admin\ChatController::class, 'deleteChat'])->name('chat.delete');
});

Route::get('/admin/login', [\App\Http\Controllers\Admin\AdminLoginController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [\App\Http\Controllers\Admin\AdminLoginController::class, 'login'])
    ->name('admin.login.submit');

require __DIR__.'/auth.php';

// Fallback route for images (fixes asset paths on live servers without proper DocumentRoot or .htaccess)
Route::get('images/{path}', function ($path) {
    $filePath = public_path('images/' . $path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    abort(404);
})->where('path', '.*');

// Fallback route for storage files (fixes broken storage symlinks on shared hosting)
Route::get('storage/{path}', function ($path) {
    $filePath = storage_path('app/public/' . $path);
    if (file_exists($filePath)) {
        return response()->file($filePath);
    }
    abort(404);
})->where('path', '.*');





Route::get('/fix-storage', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    return 'Storage linked successfully!';
});






Route::get(
    '/admin/vendor-commission-setting',
    [VendorCommissionSettingController::class, 'index']
)->name('admin.vendor-commission.index');

Route::post(
    '/admin/vendor-commission-setting',
    [VendorCommissionSettingController::class, 'update']
)->name('admin.vendor-commission.update');



Route::get('/vendor/analytics/download', [\App\Http\Controllers\Vendor\AnalyticsController::class, 'download'])
    ->name('vendor.analytics.download');
