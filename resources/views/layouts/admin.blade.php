<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QuickDines') }} - Super Admin Panel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --sidebar-bg: #04112e;
            --sidebar-text: #8c90a4;
            --sidebar-active-bg: linear-gradient(90deg, #2563eb 0%, #3b82f6 100%);
            --sidebar-active-text: #ffffff;
            --bg-body: #f8fafc;
            --topbar-bg: #ffffff;
            --primary-color: #2563eb;
            --text-main: #1e293b;
            --text-muted: #64748b;
        }

        body.dark-theme {
            --bg-body: #02091c;
            --topbar-bg: #04112e;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
        }

        body.dark-theme .glass-card {
            background: #1e293b;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        body.dark-theme .search-box {
            background: #0f172a;
        }

        body.dark-theme .search-box input {
            color: #f8fafc;
        }

        body.dark-theme .search-shortcut {
            background: #1e293b;
            color: #94a3b8;
        }

        body.dark-theme .topbar {
            border-bottom: 1px solid #334155;
        }

        body.dark-theme .topbar-icon:hover {
            background: #334155;
        }

        body.dark-theme .dropdown-menu {
            background: #1e293b;
            border: 1px solid #334155 !important;
        }

        body.dark-theme .dropdown-item {
            color: #f8fafc;
        }

        body.dark-theme .dropdown-item:hover {
            background: #334155;
            color: #ffffff;
        }

        body.dark-theme .dropdown-divider {
            border-color: #334155;
        }

        body.dark-theme .user-profile {
            border-left: 1px solid #334155;
        }

        /* Apply theme transition smoothly */
        body,
        .glass-card,
        .topbar,
        .search-box,
        .dropdown-menu {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-body);
            color: var(--text-main);
            margin: 0;
            overflow-x: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            background-color: var(--sidebar-bg);
            width: 260px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1000;
            overflow-y: auto;
            padding-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: #2a2a3c;
            border-radius: 4px;
        }

        .sidebar .brand {
            padding: 1.5rem;
            color: white;
            font-size: 1.4rem;
            font-weight: 800;
            display: flex;
            flex-direction: column;
            text-decoration: none;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 1rem;
        }

        .sidebar .brand .brand-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f26a21;
        }

        .sidebar .brand .brand-logo span {
            color: white;
            letter-spacing: -0.5px;
        }

        .sidebar .brand .brand-subtitle {
            font-size: 0.75rem;
            color: #8c90a4;
            font-weight: 500;
            margin-top: 2px;
            margin-left: 2px;
        }

        .sidebar-section {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #5d6179;
            font-weight: 700;
            padding: 1rem 1.5rem 0.5rem;
            margin-top: 0.5rem;
        }

        .sidebar .nav-link {
            color: #ffffff;
            font-weight: 500;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            font-size: 0.85rem;
            text-decoration: none;
            transition: all 0.2s ease;
            margin: 0.1rem 1rem;
            border-radius: 8px;
            opacity: 0.8;
        }

        .sidebar .nav-link:hover {
            color: #ffffff;
            opacity: 1;
            background-color: rgba(255, 255, 255, 0.05);
        }

        .sidebar .nav-link.active {
            color: #ffffff;
            opacity: 1;
            background: var(--sidebar-active-bg);
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .sidebar .nav-link i {
            width: 24px;
            text-align: left;
            margin-right: 8px;
            font-size: 1.1rem;
        }

        /* Main Content wrapper */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Topbar Styles */
        .topbar {
            background-color: var(--topbar-bg);
            height: 70px;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
            border-bottom: 1px solid #e2e8f0;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            flex: 1;
        }

        .menu-toggle {
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.2rem;
        }

        .search-box {
            position: relative;
            width: 350px;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
        }

        .search-box i {
            color: #94a3b8;
        }

        .search-box input {
            border: none;
            background: transparent;
            width: 100%;
            padding-left: 10px;
            outline: none;
            font-size: 0.9rem;
            color: var(--text-main);
        }

        .search-shortcut {
            font-size: 0.7rem;
            background: #e2e8f0;
            padding: 2px 6px;
            border-radius: 4px;
            color: #64748b;
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }

        .topbar-icon {
            color: var(--text-muted);
            font-size: 1.2rem;
            position: relative;
            cursor: pointer;
            transition: color 0.2s;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .topbar-icon:hover {
            color: var(--primary-color);
            background: #f1f5f9;
        }

        .icon-badge {
            position: absolute;
            top: 0px;
            right: 0px;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: 2px solid white;
            line-height: 1;
        }

        .badge-red {
            background-color: #ef4444;
            color: white;
        }

        .badge-green {
            background-color: #10b981;
            color: white;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            padding-left: 0.5rem;
            border-left: 1px solid #e2e8f0;
            margin-left: 0.5rem;
        }

        .user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 50%;
            object-fit: cover;
        }

        .user-info {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            line-height: 1.2;
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        /* Main Content Area */
        .content-area {
            padding: 2rem;
            flex-grow: 1;
        }

        .footer {
            padding: 1.5rem 2rem;
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        /* Utility */
        .glass-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0, 0, 0, 0.02);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .glass-card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
            transform: translateY(-2px);
        }

        /* Mobile Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 995;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }

        /* Responsive Styles */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .search-box {
                width: 200px;
            }

            .content-area {
                padding: 1rem;
            }
        }

        @media (max-width: 576px) {
            .search-box {
                display: none;
                /* hide search on very small screens to save space */
            }

            .topbar {
                padding: 0 1rem;
            }
        }
    </style>
</head>

<body>

    <!-- Mobile Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="d-flex justify-content-between align-items-center pe-3">
            <a href="{{ route('admin.dashboard') }}" class="brand flex-grow-1">
                <div class="brand-logo">
                    <i class="fa-solid fa-bolt" style="color: #f26a21;"></i> <span>QuickDines</span>
                </div>
                <div class="brand-subtitle">Super Admin Panel</div>
            </a>
            <i class="fa-solid fa-times d-lg-none" id="closeSidebar" style="color: #8c90a4; cursor: pointer; font-size: 1.3rem;"></i>
        </div>

        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Dashboard
                </a>
            </li>

            <div class="sidebar-section" style="color: #f472b6;">BUS MANAGEMENT</div>
            <li class="nav-item"><a href="{{ route('admin.buses.index') }}" class="nav-link {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}"><i class="fa-solid fa-bus"></i> Buses</a></li>
            <li class="nav-item"><a href="{{ route('admin.bus-routes.index') }}" class="nav-link {{ request()->routeIs('admin.bus-routes.*') ? 'active' : '' }}"><i class="fa-solid fa-route"></i> Bus Routes</a></li>
            <li class="nav-item"><a href="{{ route('admin.qr.index') }}" class="nav-link {{ request()->routeIs('admin.qr.*') ? 'active' : '' }}"><i class="fa-solid fa-qrcode"></i> QR Code Management</a></li>

            <div class="sidebar-section" style="color: #fbbf24;">RESTAURANTS</div>
            <li class="nav-item"><a href="{{ route('admin.vendors.index') }}" class="nav-link {{ request()->routeIs('admin.vendors.*') ? 'active' : '' }}"><i class="fa-solid fa-store"></i> Restaurants</a></li>
            <li class="nav-item"><a href="{{ route('admin.restaurants.assign') }}" class="nav-link {{ request()->routeIs('admin.restaurants.assign') ? 'active' : '' }}"><i class="fa-solid fa-utensils"></i> Restaurant Assignment</a></li>
            @php
            $pendingCount = \App\Models\Restaurant::where('status', 0)->where('created_at', '>', auth()->user()->last_viewed_pending_restaurants ?? '2000-01-01')->count();
            @endphp
            <li class="nav-item">
                <a href="{{ route('admin.restaurants.pending') }}" class="nav-link {{ request()->routeIs('admin.restaurants.pending') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                    <div><i class="fa-solid fa-clock-rotate-left"></i> Pending Restaurants</div>
                    @if($pendingCount > 0)
                    <span class="badge" style="background: #ef4444; color: white; border-radius: 12px; padding: 2px 8px; font-size: 0.7rem;">{{ $pendingCount }}</span>
                    @endif
                </a>
            </li>

            <div class="sidebar-section" style="color: #34d399;">ORDERS & CUSTOMERS</div>
            <li class="nav-item"><a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}"><i class="fa-solid fa-shopping-bag"></i> Passenger Orders</a></li>
            <li class="nav-item"><a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Customers</a></li>
            <li class="nav-item"><a href="{{ route('admin.reviews.index') }}" class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}"><i class="fa-solid fa-star"></i> Reviews & Ratings</a></li>

            <div class="sidebar-section" style="color: #38bdf8;">DRIVERS</div>
            <li class="nav-item"><a href="{{ route('admin.drivers.index') }}" class="nav-link {{ request()->routeIs('admin.drivers.index') ? 'active' : '' }}"><i class="fa-regular fa-id-badge"></i> Drivers</a></li>
            @php
            $pendingDriversCount = \App\Models\Driver::where('status', 0)->count();
            @endphp
            <li class="nav-item">
                <a href="{{ route('admin.drivers.pending') }}" class="nav-link {{ request()->routeIs('admin.drivers.pending') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                    <div><i class="fa-solid fa-clock-rotate-left"></i> Pending Drivers</div>
                    @if($pendingDriversCount > 0)
                    <span class="badge" style="background: #ef4444; color: white; border-radius: 12px; padding: 2px 8px; font-size: 0.7rem;">{{ $pendingDriversCount }}</span>
                    @endif
                </a>
            </li>

            <div class="sidebar-section" style="color: #a78bfa;">FINANCE</div>
            <li class="nav-item"><a href="{{ route('admin.finance.index') }}" class="nav-link {{ request()->routeIs('admin.finance.index') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Finance Dashboard</a></li>
            <li class="nav-item"><a href="{{ route('admin.finance.settlements.index') }}" class="nav-link {{ request()->routeIs('admin.finance.settlements.*') ? 'active' : '' }}"><i class="fa-solid fa-money-check-dollar"></i> Financial Settlements</a></li>
            <li class="nav-item"><a href="{{ route('admin.finance.transactions.index') }}" class="nav-link {{ request()->routeIs('admin.finance.transactions.*') ? 'active' : '' }}"><i class="fa-solid fa-arrow-right-arrow-left"></i> Transactions</a></li>
            <li class="nav-item"><a href="{{ route('admin.finance.payouts.index') }}" class="nav-link {{ request()->routeIs('admin.finance.payouts.*') ? 'active' : '' }}"><i class="fa-solid fa-clock-rotate-left"></i> Payout History</a></li>
            <li class="nav-item"><a href="{{ route('admin.finance.vendor-payouts.index') }}" class="nav-link {{ request()->routeIs('admin.finance.vendor-payouts.*') ? 'active' : '' }}"><i class="fa-solid fa-store"></i> Vendor Payouts</a></li>
            <li class="nav-item"><a href="{{ route('admin.finance.commissions.index') }}" class="nav-link {{ request()->routeIs('admin.finance.commissions.*') ? 'active' : '' }}"><i class="fa-solid fa-percent"></i> Commission Settings</a></li>

            <li class="nav-item">
                <a href="{{ route('admin.vendor-commission.index') }}"
                    class="nav-link {{ request()->routeIs('admin.vendor-commission.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-percent"></i> Vendor Commission Setting
                </a>
            </li>



            <div class="sidebar-section" style="color: #818cf8;">SUPPORT & REPORTS</div>
            <li class="nav-item"><a href="{{ route('admin.chat.index') }}" class="nav-link {{ request()->routeIs('admin.chat.*') ? 'active' : '' }}"><i class="fa-regular fa-comments"></i> Live Chat</a></li>

            @php
            $unreadTickets = \App\Models\SupportTicket::where('admin_read', false)->count();
            @endphp
            <li class="nav-item">
                <a href="{{ route('admin.support.tickets.index') }}" class="nav-link {{ request()->routeIs('admin.support.tickets.*') ? 'active' : '' }}" style="display: flex; justify-content: space-between; align-items: center;">
                    <span><i class="fa-solid fa-ticket"></i> Support Tickets</span>
                    @if($unreadTickets > 0)
                    <span class="badge" style="background: #ef4444; color: white; border-radius: 12px; padding: 2px 8px; font-size: 0.7rem;">{{ $unreadTickets }}</span>
                    @endif
                </a>
            </li>

            <li class="nav-item"><a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"><i class="fa-regular fa-chart-bar"></i> Reports & Analytics</a></li>

            <div class="sidebar-section" style="color: #e2e8f0;">SYSTEM</div>
            <li class="nav-item"><a href="{{ route('admin.system-users.index') }}" class="nav-link {{ request()->routeIs('admin.system-users.*') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i> Password Management</a></li>
        </ul>
    </div>

    <!-- Main Wrapper -->
    <div class="main-wrapper">

        <!-- Topbar -->
        <header class="topbar">
            <div class="topbar-left">
                <i class="fa-solid fa-bars menu-toggle" id="menuToggle"></i>
            </div>

            <div class="topbar-right">
                @php
                $currentUser = auth()->user();
                $lastNotifView = $currentUser ? $currentUser->last_viewed_notifications_at : null;
                $lastMsgView = $currentUser ? $currentUser->last_viewed_messages_at : null;

                // Fetch dynamic data
                $pendingRestaurants = \App\Models\Restaurant::where('status', 0)->latest()->get();
                $pendingDrivers = \App\Models\Driver::where('status', 0)->latest()->get();
                $latestCustomer = \App\Models\User::where('role', 'customer')->latest()->first();

                // Count only UNSEEN notifications
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

                    // New Messages count is now handled where the icon is rendered
                    @endphp

                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <div class="topbar-icon" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="cursor: pointer;">
                            <i class="fa-regular fa-bell"></i>
                            @if($newNotificationsCount > 0)
                            <span class="icon-badge badge-red" id="notifBadge">{{ $newNotificationsCount }}</span>
                            @endif
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <li class="dropdown-header">
                                <h6 class="mb-0">Notifications</h6>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            @if($pendingRestaurants->count() > 0)
                            @foreach($pendingRestaurants->take(2) as $restaurant)
                            <li><a class="dropdown-item py-2" href="{{ route('admin.restaurants.pending') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; min-width: 35px;">
                                            <i class="fa-solid fa-store"></i>
                                        </div>
                                        <div style="overflow: hidden;">
                                            <p class="mb-0 text-truncate" style="font-size: 0.85rem; font-weight:600;">{{ $restaurant->restaurant_name }}</p>
                                            <p class="mb-0 text-muted text-truncate" style="font-size: 0.75rem;">New restaurant registered</p>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $restaurant->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endforeach
                            @endif

                            @if($pendingDrivers->count() > 0)
                            @foreach($pendingDrivers->take(2) as $driver)
                            <li><a class="dropdown-item py-2" href="{{ route('admin.drivers.pending') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; min-width: 35px;">
                                            <i class="fa-regular fa-id-badge"></i>
                                        </div>
                                        <div style="overflow: hidden;">
                                            <p class="mb-0 text-truncate" style="font-size: 0.85rem; font-weight:600;">Driver {{ $driver->vehicle_number }}</p>
                                            <p class="mb-0 text-muted text-truncate" style="font-size: 0.75rem;">New driver application</p>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $driver->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endforeach
                            @endif

                            @if($latestCustomer)
                            <li><a class="dropdown-item py-2" href="{{ route('admin.users.index') }}">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 35px; height: 35px; min-width: 35px;">
                                            <i class="fa-solid fa-user-plus"></i>
                                        </div>
                                        <div style="overflow: hidden;">
                                            <p class="mb-0 text-truncate" style="font-size: 0.85rem; font-weight:600;">{{ $latestCustomer->name }}</p>
                                            <p class="mb-0 text-muted text-truncate" style="font-size: 0.75rem;">New customer signed up</p>
                                            <small class="text-muted" style="font-size: 0.7rem;">{{ $latestCustomer->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            @endif

                            @if($pendingRestaurants->count() == 0 && $pendingDrivers->count() == 0 && !$latestCustomer)
                            <li>
                                <div class="dropdown-item text-center text-muted py-3">No new notifications</div>
                            </li>
                            @endif
                        </ul>
                    </div>

                    <!-- Messages (Live Chat) -->
                    @php
                    $newMessagesCount = \App\Models\Message::where('is_admin', false)->where('is_read', false)->count();
                    @endphp
                    <a href="{{ route('admin.chat.index') }}" style="text-decoration: none; color: inherit;">
                        <div class="topbar-icon" id="messagesIcon" style="cursor: pointer; position: relative;">
                            <i class="fa-regular fa-message"></i>
                            @if($newMessagesCount > 0)
                            <span class="icon-badge badge-green" id="msgBadge">{{ $newMessagesCount }}</span>
                            @endif
                        </div>
                    </a>
                    <div class="topbar-icon" id="themeToggle" title="Toggle Dark Mode">
                        <i class="fa-regular fa-sun"></i>
                    </div>

                    <div class="dropdown">
                        <div class="user-profile ms-2" data-bs-toggle="dropdown">
                            <img src="https://ui-avatars.com/api/?name=SA&background=6366f1&color=fff&rounded=true&font-size=0.4" alt="User" class="user-avatar">
                            <div class="user-info">
                                <span class="user-name">Super Admin</span>
                                <span class="user-role">Administrator</span>
                            </div>
                            <i class="fa-solid fa-chevron-down ms-1" style="font-size:0.7rem; color:var(--text-muted)"></i>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger"><i class="fa-solid fa-arrow-right-from-bracket me-2"></i> Logout</button>
                                </form>
                            </li>
                        </ul>
                    </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="content-area">
            @yield('content')
        </div>

        <footer class="footer">
            <div>
                <div>&copy; 2026 <strong>QuickDines</strong>. All rights reserved.</div>
                <div style="margin-top: 4px;">Designed & Developed by <a href="https://quickdines.com" target="_blank" style="color: var(--primary-color); text-decoration: none; font-weight: 600;">QuickDines Pvt Ltd</a></div>
            </div>
            <div>Version 1.0.0</div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Sidebar Toggle JS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Notifications and Messages Read logic
            const notifDropdown = document.getElementById('notificationsDropdown');
            const msgDropdown = document.getElementById('messagesDropdown');

            if (notifDropdown) {
                notifDropdown.addEventListener('click', function() {
                    const badge = document.getElementById('notifBadge');
                    if (badge) {
                        badge.style.display = 'none';
                        fetch("{{ route('admin.notifications.mark-read') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).catch(err => console.log(err));
                    }
                });
            }

            if (msgDropdown) {
                msgDropdown.addEventListener('click', function() {
                    const badge = document.getElementById('msgBadge');
                    if (badge) {
                        badge.style.display = 'none';
                        fetch("{{ route('admin.messages.mark-read') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json'
                            }
                        }).catch(err => console.log(err));
                    }
                });
            }

            const menuToggle = document.getElementById('menuToggle');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const closeSidebarBtn = document.getElementById('closeSidebar');

            // --- UI Interaction Elements ---
            const globalSearch = document.getElementById('globalSearch');
            const fullscreenToggle = document.getElementById('fullscreenToggle');
            const themeToggle = document.getElementById('themeToggle');

            // 1. Sidebar Toggle Logic
            function toggleSidebar() {
                if (sidebar) sidebar.classList.toggle('active');
                if (overlay) overlay.classList.toggle('active');
            }

            if (menuToggle) menuToggle.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
            if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', toggleSidebar);

            // 2. Global Search Dashboard Filter
            if (globalSearch) {
                globalSearch.addEventListener('input', function(e) {
                    const query = e.target.value.toLowerCase();

                    // If we are on the dashboard, filter the cards and lists
                    const searchableItems = document.querySelectorAll('.stat-card, .list-item, .action-card, .section-card');

                    if (searchableItems.length > 0) {
                        searchableItems.forEach(item => {
                            // Don't hide section cards entirely if they have children, just filter the children
                            if (item.classList.contains('section-card')) {
                                return;
                            }

                            const text = item.textContent.toLowerCase();
                            if (query === '' || text.includes(query)) {
                                item.style.display = '';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }
                });
            }

            // 3. Fullscreen Toggle
            if (fullscreenToggle) {
                fullscreenToggle.addEventListener('click', function() {
                    if (!document.fullscreenElement) {
                        document.documentElement.requestFullscreen().catch(err => {
                            console.log(`Error attempting to enable fullscreen: ${err.message}`);
                        });
                        this.innerHTML = '<i class="fa-solid fa-compress"></i>';
                    } else {
                        if (document.exitFullscreen) {
                            document.exitFullscreen();
                            this.innerHTML = '<i class="fa-solid fa-expand"></i>';
                        }
                    }
                });
            }

            // 4. Dark Theme Toggle Logic
            // Check for saved user preference, if any, on load of the website
            const currentTheme = localStorage.getItem('theme') ? localStorage.getItem('theme') : null;
            if (currentTheme) {
                document.body.classList.add(currentTheme);
                if (currentTheme === 'dark-theme') {
                    themeToggle.innerHTML = '<i class="fa-regular fa-moon"></i>';
                }
            }

            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    document.body.classList.toggle('dark-theme');
                    let theme = 'light-theme';
                    if (document.body.classList.contains('dark-theme')) {
                        theme = 'dark-theme';
                        this.innerHTML = '<i class="fa-regular fa-moon"></i>';
                    } else {
                        this.innerHTML = '<i class="fa-regular fa-sun"></i>';
                    }
                    localStorage.setItem('theme', theme);
                });
            }

            // 5. Sidebar Scroll Position State
            if (sidebar) {
                // Restore scroll position
                const savedScrollTop = sessionStorage.getItem('adminSidebarScrollTop');
                if (savedScrollTop) {
                    sidebar.scrollTop = parseInt(savedScrollTop, 10);
                }

                // Save scroll position on scroll
                let scrollTimeout;
                sidebar.addEventListener('scroll', function() {
                    if (scrollTimeout) {
                        cancelAnimationFrame(scrollTimeout);
                    }
                    scrollTimeout = requestAnimationFrame(function() {
                        sessionStorage.setItem('adminSidebarScrollTop', sidebar.scrollTop);
                    });
                });
            }
        });
    </script>

    @yield('scripts')

    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Global Notification System -->
    <audio id="global-notification-sound" src="{{ asset('notification.mp3') }}" preload="auto"></audio>
    <script>
        function showBrowserNotification(title, body, url) {
            if (!("Notification" in window)) return;

            const options = {
                body: body,
                icon: "{{ asset('images/logo.png') }}"
            };

            if (Notification.permission === "granted") {
                let n = new Notification(title, options);
                if (url) n.onclick = function() {
                    window.location.href = url;
                };
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        let n = new Notification(title, options);
                        if (url) n.onclick = function() {
                            window.location.href = url;
                        };
                    }
                });
            }
        }

        window.playNotificationSound = () => {
            try {
                const audio = document.getElementById('global-notification-sound');
                if (audio) {
                    audio.muted = false; // Ensure not muted
                    audio.currentTime = 0;
                    let playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise.catch(error => {
                            console.log("Audio playback was prevented by the browser. Awaiting user interaction.");
                        });
                    }
                }
            } catch (e) {
                console.error("Audio error:", e);
            }
        };

        // Unlock audio context on first user interaction
        document.addEventListener('click', function unlockAudio() {
            const audio = document.getElementById('global-notification-sound');
            if (audio) {
                audio.muted = true;
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audio.muted = false;
                }).catch(e => {});
            }
            document.removeEventListener('click', unlockAudio);
        }, {
            once: true
        });

        let currentNotificationCount = {{ $newNotificationsCount ?? 0 }};
let currentMessageCount = {{ $newMessagesCount ?? 0 }};

        setInterval(() => {
            fetch('{{ route("admin.notifications.check") }}')
                .then(res => res.json())
                .then(data => {
                        let hasNewMessage = data.messages > currentMessageCount;
                        let hasNewNotification = data.notifications > currentNotificationCount;

                        if (hasNewMessage || hasNewNotification) {
                            if (typeof window.playNotificationSound === 'function') {
                                window.playNotificationSound();
                            }

                            if (hasNewMessage) {
                                Toastify({
                                    text: "New message received!",
                                    duration: 5000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#6366f1",
                                        borderRadius: "8px",
                                        fontWeight: "600"
                                    }
                                }).showToast();
                                showBrowserNotification("New Message", "You have a new message.", null);
                            }

                            if (hasNewNotification) {
                                Toastify({
                                    text: "New system notification!",
                                    duration: 5000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    style: {
                                        background: "#3b82f6",
                                        borderRadius: "8px",
                                        fontWeight: "600"
                                    }
                                }).showToast();
                                showBrowserNotification("New Notification", "You received a new system notification.", null);
                            }
                        }

                        // Update Messages Badge
                        let msgIcon = document.getElementById('messagesIcon');
                        if (msgIcon && data.messages > 0) {
                            let msgBadge = document.getElementById('msgBadge');
                            if (!msgBadge) {
                                msgBadge = document.createElement('span');
                                msgBadge.id = 'msgBadge';
                                msgBadge.className = 'icon-badge badge-green';
                                msgIcon.appendChild(msgBadge);
                            }
                            msgBadge.innerText = data.messages;
                            msgBadge.style.display = 'block';
                        }

                        // Update Notifications Badge
                        let notifIcon = document.getElementById('notificationsDropdown');
                        if (notifIcon && data.notifications > 0) {
                            let notifBadge = document.getElementById('notifBadge');
                            if (!notifBadge) {
                                notifBadge = document.createElement('span');
                                notifBadge.id = 'notifBadge';
                                notifBadge.className = 'icon-badge badge-red';
                                notifIcon.appendChild(notifBadge);
                            }
                            notifBadge.innerText = data.notifications;
                            notifBadge.style.display = 'block';
                        }
                    }

                    currentNotificationCount = data.notifications; currentMessageCount = data.messages;
                });
        }, 8000); // Poll every 8 seconds
    </script>
</body>

</html>
