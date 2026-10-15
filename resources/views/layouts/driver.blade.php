@php
    $layoutDriver = \App\Models\Driver::where('user_id', auth()->id())->first();
    $assignedRestaurantsCount = 0;
    if ($layoutDriver && $layoutDriver->bus_id) {
        $assignedRestaurantsCount = \Illuminate\Support\Facades\DB::table('bus_restaurant')
            ->where('bus_id', $layoutDriver->bus_id)
            ->count();
    }
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Driver Panel - QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Leaflet CSS for Maps -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --bg-dark: #04112e;
            --bg-main: #f8fafc;
            --text-dark: #0f172a;
            --text-muted: #64748b;
            --card-bg: #ffffff;
            --border-color: #f1f5f9;
            --danger: #ef4444;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-main);
            color: var(--text-dark);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar styling */
        .sidebar {
            width: 260px;
            background-color: var(--bg-dark);
            color: white;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            z-index: 9999;
        }

        .sidebar-brand {
            padding: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: white;
        }
        .sidebar-brand i {
            color: var(--primary);
            font-size: 28px;
        }
        .brand-text h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .brand-text p {
            font-size: 10px;
            color: #94a3b8;
        }

        .nav-menu {
            display: flex;
            flex-direction: column;
            gap: 4px;
            padding: 10px 16px;
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            text-decoration: none;
            color: #94a3b8;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
        }

        .nav-item.active {
            color: white;
            background: var(--primary);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--primary);
            color: white;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            border-radius: 12px;
        }
        .nav-item.active .nav-badge {
            background: white;
            color: var(--primary);
        }

        .sidebar-bottom {
            padding: 20px 16px;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0; /* Important for flex child */
        }

        .top-header {
            background: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 9997;
        }

        .welcome-text h1 {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .welcome-text p {
            font-size: 13px;
            color: var(--text-muted);
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .status-toggle {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e2e8f0;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
        }
        .status-dot {
            width: 8px;
            height: 8px;
            background: #22c55e;
            border-radius: 50%;
        }

        .btn-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            border: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-dark);
            cursor: pointer;
            position: relative;
        }
        .notification-dot {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            border: 2px solid white;
        }
        .notification-badge-icon {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            padding: 2px 5px;
            border-radius: 10px;
            border: 2px solid white;
            font-weight: bold;
        }

        /* Notification Dropdown */
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            width: 320px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border: 1px solid #e2e8f0;
            margin-top: 10px;
            opacity: 0;
            visibility: hidden;
            transform: translateY(10px);
            transition: all 0.2s ease;
            z-index: 10000;
        }
        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }
        .dropdown-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 700;
            color: var(--text-dark);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .dropdown-header a {
            font-size: 0.8rem;
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }
        .dropdown-list {
            max-height: 300px;
            overflow-y: auto;
        }
        .dropdown-item {
            padding: 15px 20px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            gap: 15px;
            text-decoration: none;
            color: var(--text-dark);
            transition: background 0.2s;
        }
        .dropdown-item:hover {
            background: #f8fafc;
        }
        .dropdown-item-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #e0e7ff;
            color: #4f46e5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .dropdown-item-content h4 {
            font-size: 0.9rem;
            margin-bottom: 2px;
            font-weight: 600;
        }
        .dropdown-item-content p {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-bottom: 5px;
        }
        .dropdown-item-time {
            font-size: 0.7rem;
            color: #94a3b8;
        }
        .dropdown-empty {
            padding: 30px;
            text-align: center;
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .profile-dropdown {
            display: flex;
            align-items: center;
            gap: 12px;
            cursor: pointer;
        }
        .profile-dropdown img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-info h4 {
            font-size: 14px;
            font-weight: 700;
        }
        .profile-info p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .dashboard-container {
            padding: 24px 40px;
            flex-grow: 1;
        }

        /* Card styles */
        .card {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--border-color);
        }
        .card-header-title {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-header-title .view-all {
            font-size: 12px;
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }
        /* Mobile Responsive */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 20px;
            color: var(--text-dark);
            cursor: pointer;
            margin-right: 12px;
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(15, 23, 42, 0.6);
            z-index: 9998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .sidebar-overlay.show {
            display: block;
            opacity: 1;
        }

        @media (max-width: 1024px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .mobile-menu-toggle {
                display: block;
            }
            .top-header {
                padding: 16px 20px;
                justify-content: flex-start;
            }
            .header-actions {
                margin-left: auto;
                gap: 12px;
            }
            .welcome-text p {
                display: none;
            }
            .dashboard-container {
                padding: 16px 20px;
            }
        }
        
        @media (max-width: 768px) {
            .welcome-text h1 {
                font-size: 18px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                max-width: 120px;
            }
            .status-toggle {
                padding: 4px 8px;
                font-size: 11px;
            }
            .profile-info {
                display: none;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="{{ route('driver.dashboard') }}" class="sidebar-brand">
            <i class="fa-solid fa-bell-concierge"></i>
            <div class="brand-text">
                <h2>QuickDines</h2>
                <p>Food on Your Journey</p>
            </div>
        </a>

        <div class="nav-menu">
            <a href="{{ route('driver.dashboard') }}" class="nav-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i>
                <span>Dashboard</span>
            </a>
            <a href="{{ route('driver.orders.index') }}" class="nav-item {{ request()->routeIs('driver.orders.index') ? 'active' : '' }}">
                <i class="fa-solid fa-utensils"></i>
                <span>Assigned Restaurants</span>
            </a>
            <a href="{{ route('driver.passengers.index') }}" class="nav-item {{ request()->routeIs('driver.passengers.index') ? 'active' : '' }}">
                <i class="fa-solid fa-users"></i>
                <span>Passenger Orders</span>
            </a>
            <a href="{{ route('driver.earnings.index') }}" class="nav-item {{ request()->routeIs('driver.earnings.index') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i>
                <span>Wallet</span>
            </a>
            <a href="{{ route('driver.profile.index') }}" class="nav-item {{ request()->routeIs('driver.profile.index') ? 'active' : '' }}">
                <i class="fa-regular fa-user"></i>
                <span>Profile</span>
            </a>
            <a href="{{ route('driver.support.index') }}" class="nav-item {{ request()->routeIs('driver.support.index') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i>
                <span>Support Ticket</span>
            </a>
            <a href="{{ route('driver.chat.index') }}" class="nav-item {{ request()->routeIs('driver.chat.index') ? 'active' : '' }}">
                <i class="fa-regular fa-comments"></i>
                <span>Live Chat</span>
            </a>
        </div>

        <div class="sidebar-bottom">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();" class="nav-item" style="color: #ef4444;">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span>Logout</span>
                </a>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-header">
            <button class="mobile-menu-toggle" id="mobileMenuToggle">
                <i class="fa-solid fa-bars"></i>
            </button>
            
            <div class="welcome-text">
                <h1>Welcome back, Driver!</h1>
                <p>Drive safe and deliver happiness.</p>
            </div>
            
            <div class="header-actions">
                <div class="status-toggle">
                    <div class="status-dot"></div>
                    Online
                </div>
                
                <div style="position: relative;">
                    <button class="btn-icon" onclick="toggleDropdown('notificationDropdown')">
                        <i class="fa-regular fa-bell"></i>
                        @if($assignedRestaurantsCount > 0)
                            <div class="notification-badge-icon">{{ $assignedRestaurantsCount }}</div>
                        @else
                            <span class="notification-dot" style="background:#cbd5e1;"></span>
                        @endif
                    </button>
                    
                    <div class="dropdown-menu" id="notificationDropdown">
                        <div class="dropdown-header">
                            Notifications
                            <a href="{{ route('driver.notifications.index') }}">View All</a>
                        </div>
                        <div class="dropdown-list">
                            @if($assignedRestaurantsCount > 0)
                                <a href="{{ route('driver.orders.index') }}" class="dropdown-item">
                                    <div class="dropdown-item-icon" style="background:#dcfce7; color:#16a34a;"><i class="fa-solid fa-utensils"></i></div>
                                    <div class="dropdown-item-content">
                                        <h4>{{ $assignedRestaurantsCount }} Restaurant(s) Assigned</h4>
                                        <p>You have {{ $assignedRestaurantsCount }} restaurants assigned to your route.</p>
                                        <span class="dropdown-item-time">Just now</span>
                                    </div>
                                </a>
                            @else
                                <div class="dropdown-empty">
                                    <i class="fa-solid fa-bell-slash" style="font-size:24px; margin-bottom:10px; color:#cbd5e1;"></i>
                                    <p>No new notifications</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="profile-dropdown">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1e293b&color=fff" alt="Driver Profile">
                    <div class="profile-info">
                        <h4>{{ Auth::user()->name }}</h4>
                        <p>Bus: @yield('bus_number', 'Not Assigned')</p>
                    </div>
                    <i class="fa-solid fa-chevron-down text-muted" style="font-size: 12px;"></i>
                </div>
            </div>
        </header>

        <div class="dashboard-container">
            @yield('content')
            
            <footer style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: white; border-top: 1px solid #e2e8f0; color: var(--text-muted); font-size: 0.9rem; margin-top: 40px;">
                <div>
                    <div>&copy; 2026 <strong>QuickDines</strong>. All rights reserved.</div>
                    <div style="margin-top: 4px;">Designed & Developed by <a href="https://quickdines.com" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">QuickDines Pvt Ltd</a></div>
                </div>
                <div>Version 1.0.0</div>
            </footer>
        </div>
    </main>

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.querySelector('.sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            // Check if notifications read
            const driverId = '{{ auth()->id() }}';
            const storageKey = 'driver_notified_rest_count_' + driverId;

            if (localStorage.getItem(storageKey) === '{{ $assignedRestaurantsCount }}' && parseInt('{{ $assignedRestaurantsCount }}') > 0) {
                document.querySelectorAll('.notification-badge-icon, .badge').forEach(b => b.style.display = 'none');
            }

            window.toggleDropdown = function(id) {
                const el = document.getElementById(id);
                if (el.classList.contains('show')) {
                    el.classList.remove('show');
                } else {
                    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
                    el.classList.add('show');
                    // Mark as read
                    localStorage.setItem(storageKey, '{{ $assignedRestaurantsCount }}');
                    document.querySelectorAll('.notification-badge-icon, .badge').forEach(b => b.style.display = 'none');
                }
            };

            window.addEventListener('click', function(e) {
                if (!e.target.closest('.btn-icon') && !e.target.closest('.notification-bell')) {
                    document.querySelectorAll('.dropdown-menu').forEach(d => d.classList.remove('show'));
                }
            });

            function toggleMenu() {
                if(sidebar) sidebar.classList.toggle('show');
                if(sidebarOverlay) {
                    sidebarOverlay.classList.toggle('show');
                    if (sidebarOverlay.classList.contains('show')) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = 'auto';
                    }
                }
            }

            document.querySelectorAll('.mobile-menu-toggle, .mobile-menu-toggle-custom').forEach(toggle => {
                toggle.addEventListener('click', toggleMenu);
            });
            
            if(sidebarOverlay) {
                sidebarOverlay.addEventListener('click', toggleMenu);
            }
        });
    </script>
    
    @stack('scripts')
    <!-- Include global notification sound system -->
    @include('components.notification-sound', ['checkRoute' => route('driver.api.notifications')])

</body>
</html>

