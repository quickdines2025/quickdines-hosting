<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Panel - QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --bg-dark: #04112e;
            --sidebar-width: 260px;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f8f9fa; color: #334155; display: flex; min-height: 100vh; }
        
        .sidebar {
            width: var(--sidebar-width);
            background: #fff;
            border-right: 1px solid #e2e8f0;
            position: fixed;
            height: 100%;
            display: flex;
            flex-direction: column;
            z-index: 100;
        }
        .brand {
            padding: 24px;
            font-size: 24px;
            font-weight: 800;
            color: #202a5d;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .brand i { color: var(--primary); }
        .nav-links { padding: 20px 0; flex-grow: 1; }
        .nav-links a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 24px;
            color: #64748b;
            text-decoration: none;
            font-weight: 600;
            transition: 0.3s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
            background: rgba(37, 99, 235, 0.05);
            border-right: 3px solid var(--primary);
        }
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            padding: 30px 40px;
        }
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: #fff;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
        }
        .page-title { font-size: 20px; font-weight: 700; color: #0f172a; }
        .user-profile { display: flex; align-items: center; gap: 12px; font-weight: 600; }
        .user-avatar {
            width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0;
            display: flex; align-items: center; justify-content: center; color: #64748b;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.02);
            border: 1px solid #e2e8f0;
            margin-bottom: 24px;
        }
        .btn {
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary { background: var(--primary); color: white; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-success { background: #10b981; color: white; }
        
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        th { font-size: 13px; color: #64748b; text-transform: uppercase; font-weight: 700; }
        td { font-size: 14px; color: #1e293b; font-weight: 500; }
        .badge { padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600; }
        .badge-pending { background: #fef08a; color: #854d0e; }
        .badge-preparing { background: #fed7aa; color: #9a3412; }
        .badge-ready { background: #bbf7d0; color: #166534; }
        .badge-delivered { background: #dcfce3; color: #166534; }
        
        input, select, textarea {
            width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px;
            margin-bottom: 16px; font-family: inherit; outline: none;
        }
        input:focus, select:focus, textarea:focus { border-color: var(--primary); }
        
        .noti-btn { width: 40px; height: 40px; border-radius: 50%; background: #f8fafc; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; position: relative; cursor: pointer; transition: 0.2s; }
        .noti-btn:hover { background: #e2e8f0; }
        .noti-badge { position: absolute; top: -5px; right: -5px; min-width: 18px; height: 18px; padding: 0 4px; background: #ef4444; color: white; border-radius: 12px; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white; }
        
        .mobile-header { display: none; }
        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 90; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.show { transform: translateX(0); }
            .sidebar-overlay.show { display: block; }
            .main-content { margin-left: 0; padding: 15px; width: 100%; overflow-x: hidden; }
            .mobile-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
            .mobile-header .btn-toggle { background: #fff; border: 1px solid #e2e8f0; padding: 8px 12px; border-radius: 8px; cursor: pointer; color: #0f172a; }
        }

        .profile-dropdown { position: relative; display: inline-block; cursor: pointer; }
        .profile-dropdown-content {
            display: none; position: absolute; right: 0; top: 100%; min-width: 160px;
            background-color: #fff; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1);
            z-index: 100; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;
            margin-top: 10px;
        }
        .profile-dropdown-content a, .profile-dropdown-content button {
            color: #334155; padding: 12px 16px; text-decoration: none; display: flex; align-items: center; gap: 10px;
            font-size: 14px; font-weight: 500; width: 100%; border: none; background: none; text-align: left; cursor: pointer;
        }
        .profile-dropdown-content a:hover, .profile-dropdown-content button:hover { background-color: #f8fafc; color: #ef4444; }
        .profile-dropdown.show .profile-dropdown-content { display: block; }
    </style>
    @stack('styles')
</head>
<body>
    <div class="sidebar">
        <div class="brand">
            <i class="fa-solid fa-utensils"></i> Vendor Panel
        </div>
        <div class="nav-links" style="overflow-y: auto; padding-bottom: 60px;">
            <a href="{{ route('vendor.dashboard') }}" class="{{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
            <a href="{{ route('vendor.tracking') }}" class="{{ request()->routeIs('vendor.tracking') ? 'active' : '' }}">
                <i class="fa-solid fa-location-dot"></i> Live Bus Tracking
            </a>
            @php
                $activeOrdersCount = 0;
                if(auth()->check() && auth()->user()->restaurant) {
                    $activeOrdersCount = \App\Models\Order::where('restaurant_id', auth()->user()->restaurant->id)
                        ->whereIn('order_status', ['pending', 'accepted', 'preparing', 'ready'])
                        ->count();
                }
            @endphp
            <a href="{{ route('vendor.orders.index') }}" class="{{ request()->routeIs('vendor.orders.*') ? 'active' : '' }}" style="justify-content: space-between;">
                <span style="display:flex; align-items:center; gap:12px;"><i class="fa-solid fa-bus"></i> Orders</span>
                @if($activeOrdersCount > 0)
                    <span style="background: #ef4444; color: white; padding: 2px 8px; border-radius: 12px; font-size: 10px; font-weight: bold;">{{ $activeOrdersCount }}</span>
                @endif
            </a>
            <a href="{{ route('vendor.menus.index') }}" class="{{ request()->routeIs('vendor.menus.*') ? 'active' : '' }}">
                <i class="fa-solid fa-book-open"></i> Menu Management
            </a>
            <a href="{{ route('vendor.analytics') }}" class="{{ request()->routeIs('vendor.analytics') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-simple"></i> Analytics
            </a>
            <a href="{{ route('vendor.wallet.index') }}" class="{{ request()->routeIs('vendor.wallet.*') ? 'active' : '' }}">
                <i class="fa-solid fa-wallet"></i> Wallet
            </a>
            <a href="{{ route('vendor.payments') }}" class="{{ request()->routeIs('vendor.payments') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i> Customer Payments
            </a>
            <a href="{{ route('vendor.transactions') }}" class="{{ request()->routeIs('vendor.transactions') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer"></i> Transactions
            </a>
            <a href="{{ route('vendor.settlements') }}" class="{{ request()->routeIs('vendor.settlements') ? 'active' : '' }}">
                <i class="fa-solid fa-hand-holding-dollar"></i> Settlements
            </a>
            <a href="{{ route('vendor.kyc') }}" class="{{ request()->routeIs('vendor.kyc') ? 'active' : '' }}">
                <i class="fa-regular fa-id-card"></i> KYC & Bank Details
            </a>
            <a href="{{ route('vendor.profile') }}" class="{{ request()->routeIs('vendor.profile') ? 'active' : '' }}">
                <i class="fa-regular fa-user"></i> Profile
            </a>
            <a href="{{ route('vendor.reviews') }}" class="{{ request()->routeIs('vendor.reviews') ? 'active' : '' }}">
                <i class="fa-regular fa-star"></i> Reviews
            </a>
            <a href="{{ route('vendor.support') }}" class="{{ request()->routeIs('vendor.support') ? 'active' : '' }}">
                <i class="fa-regular fa-comments"></i> Live Chat
            </a>
            <a href="{{ route('vendor.tickets') }}" class="{{ request()->routeIs('vendor.tickets') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket"></i> Support Tickets
            </a>
            <div style="padding: 24px 24px 10px 24px; margin-top: 10px; border-top: 1px solid #e2e8f0;">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="width: 100%; display:flex; align-items:center; gap:12px; padding:12px 0; background:transparent; border:none; color:#ef4444; font-weight:600; font-size:15px; cursor:pointer;">
                        <i class="fa-solid fa-right-from-bracket"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="sidebar-overlay" onclick="document.querySelector('.sidebar').classList.remove('show'); this.classList.remove('show');"></div>

    <div class="main-content">
        <div class="mobile-header">
            <div style="font-weight: 800; color: #202a5d; font-size: 18px;"><i class="fa-solid fa-utensils" style="color: var(--primary);"></i> Vendor Panel</div>
            <button class="btn-toggle" onclick="document.querySelector('.sidebar').classList.toggle('show'); document.querySelector('.sidebar-overlay').classList.toggle('show');"><i class="fa-solid fa-bars"></i></button>
        </div>
        <div class="topbar">
            <div class="page-title">@yield('title', 'Dashboard')</div>
            <div style="display: flex; align-items: center; gap: 16px;">
                @yield('header_actions')
                <div class="noti-btn" onclick="window.location.href='{{ route('vendor.orders.index') }}'">
                    <i class="fa-regular fa-bell"></i>
                    <div class="noti-badge" id="vendorNotiBadge" style="display: none;">0</div>
                </div>
                
                <div class="profile-dropdown" onclick="this.classList.toggle('show')">
                    <div class="user-profile">
                        <span>{{ Auth::user()->name }}</span>
                        <div class="user-avatar"><i class="fa-solid fa-user"></i></div>
                    </div>
                    <div class="profile-dropdown-content">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">
                                <i class="fa-solid fa-right-from-bracket"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #dcfce3; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 600;">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
        
        <footer style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: white; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.9rem; margin-top: auto;">
            <div>
                <div>&copy; 2026 <strong>QuickDines</strong>. All rights reserved.</div>
                <div style="margin-top: 4px;">Designed & Developed by <a href="https://quickdines.com" target="_blank" style="color: var(--primary); text-decoration: none; font-weight: 600;">QuickDines Pvt Ltd</a></div>
            </div>
            <div>Version 1.0.0</div>
        </footer>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Global Notification System -->
    <audio id="global-notification-sound" src="{{ asset('notification.mp3') }}" preload="auto"></audio>
    <script>
        function showBrowserNotification(title, body, url) {
            if (!("Notification" in window)) return;
            
            const options = { body: body, icon: "{{ asset('images/logo.png') }}" };
            
            if (Notification.permission === "granted") {
                let n = new Notification(title, options);
                n.onclick = function() { window.location.href = url; };
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then(permission => {
                    if (permission === "granted") {
                        let n = new Notification(title, options);
                        n.onclick = function() { window.location.href = url; };
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
            if(audio) {
                audio.muted = true;
                audio.play().then(() => {
                    audio.pause();
                    audio.currentTime = 0;
                    audio.muted = false;
                }).catch(e => {});
            }
            document.removeEventListener('click', unlockAudio);
        }, { once: true });

        let currentMessageCount = 0;
        let lastNewOrderTime = "{{ auth()->check() && auth()->user()->restaurant ? (\App\Models\Order::where('restaurant_id', auth()->user()->restaurant->id)->where('order_status', 'pending')->max('created_at') ?? '') : '' }}";
        let lastOrderUpdateTime = "{{ auth()->check() && auth()->user()->restaurant ? (\App\Models\Order::where('restaurant_id', auth()->user()->restaurant->id)->max('updated_at') ?? '') : '' }}";
        
        setInterval(() => {
            fetch('{{ route("vendor.notifications.check") }}')
                .then(res => res.json())
                .then(data => {
                    let hasNewMessage = data.messages > currentMessageCount;
                    let hasNewOrder = data.latest_order_time && data.latest_order_time !== lastNewOrderTime;
                    let hasOrderUpdate = data.latest_order_update && data.latest_order_update !== lastOrderUpdateTime;

                    if (hasNewMessage || hasNewOrder || hasOrderUpdate) {
                        if (typeof window.playNotificationSound === 'function') {
                            window.playNotificationSound();
                        }
                        
                        if (hasNewMessage) {
                            let msgUrl = "{{ route('vendor.support') }}";
                            Toastify({
                                text: "New message from Support!",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: { background: "#6366f1", borderRadius: "8px", fontWeight: "600" },
                                onClick: function(){ window.location.href = msgUrl; }
                            }).showToast();
                            showBrowserNotification("New Message", "You have received a new message from Support.", msgUrl);
                        }

                        if (hasNewOrder) {
                            let orderUrl = "{{ route('vendor.orders.index') }}";
                            Toastify({
                                text: "You received a new Order!",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: { background: "#10b981", borderRadius: "8px", fontWeight: "600" },
                                onClick: function(){ window.location.href = orderUrl; }
                            }).showToast();
                            showBrowserNotification("New Order!", "You have received a new order. Check your dashboard.", orderUrl);
                        } else if (hasOrderUpdate) {
                            let orderUrl = "{{ route('vendor.orders.index') }}";
                            Toastify({
                                text: "An order status was updated!",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: { background: "#3b82f6", borderRadius: "8px", fontWeight: "600" },
                                onClick: function(){ window.location.href = orderUrl; }
                            }).showToast();
                            showBrowserNotification("Order Updated", "An order status was recently updated.", orderUrl);
                            
                            // Optionally reload if on the orders page
                            if (window.location.pathname.includes('/vendor/orders')) {
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        }
                    }
                    
                    if(data.messages !== undefined) currentMessageCount = data.messages;
                    if(data.latest_order_time !== undefined) lastNewOrderTime = data.latest_order_time;
                    if(data.latest_order_update !== undefined) lastOrderUpdateTime = data.latest_order_update;
                    
                    // Update global badge
                    let badge = document.getElementById('vendorNotiBadge');
                    if (badge) {
                        let totalNotifs = (data.messages || 0) + (data.new_orders || 0);
                        if (totalNotifs > 0) {
                            badge.style.display = 'flex';
                            badge.innerText = totalNotifs;
                        } else {
                            badge.style.display = 'none';
                        }
                    }
                });
        }, 2000); // Poll every 2 seconds
        
        // Initial fetch to set badge immediately on load
        fetch('{{ route("vendor.notifications.check") }}')
            .then(res => res.json())
            .then(data => {
                let badge = document.getElementById('vendorNotiBadge');
                if (badge) {
                    let totalNotifs = (data.messages || 0) + (data.new_orders || 0);
                    if (totalNotifs > 0) {
                        badge.style.display = 'flex';
                        badge.innerText = totalNotifs;
                    }
                }
            });
    </script>
    
    @stack('scripts')
@include('components.notification-sound', ['checkRoute' => route('vendor.notifications.check')])

    <script>
        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            var dropdown = document.querySelector('.profile-dropdown');
            if (dropdown && !dropdown.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</body>
</html>



