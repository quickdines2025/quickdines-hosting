<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Customer Dashboard - QuickDines')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Toastify CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        :root {
            --primary: #2563eb;
            --primary-light: #eff6ff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-color: #f8fafc;
            --sidebar-bg: #ffffff;
            --card-bg: #ffffff;
            --border-color: #f1f5f9;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); overflow-x: hidden; }

        .app-container { display: flex; min-height: 100vh; }

        /* --- Sidebar --- */
        .sidebar { width: 260px; background: var(--sidebar-bg); border-right: 1px solid var(--border-color); padding: 20px 0; display: flex; flex-direction: column; position: fixed; height: 100vh; overflow-y: auto; z-index: 100; }
        .sidebar::-webkit-scrollbar { width: 5px; }
        .sidebar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 5px; }

        .brand { display: flex; align-items: center; gap: 10px; padding: 0 25px; margin-bottom: 30px; text-decoration: none; }
        .brand-icon { width: 32px; height: 32px; background: var(--primary); border-radius: 50% 50% 10px 10px; position: relative; display: flex; align-items: center; justify-content: center; }
        .brand-icon::after { content: ''; position: absolute; bottom: 0; width: 40px; height: 6px; background: var(--primary); border-radius: 10px; }
        .brand-text { font-size: 20px; font-weight: 800; color: #0f172a; line-height: 1.1; }
        
        .nav-menu { list-style: none; padding: 0 15px; flex: 1; }
        .nav-item { margin-bottom: 5px; }
        .nav-item a { display: flex; align-items: center; gap: 12px; padding: 12px 15px; text-decoration: none; color: var(--text-muted); border-radius: 8px; font-weight: 500; font-size: 14px; transition: all 0.3s; }
        .nav-item a:hover { background: var(--primary-light); color: var(--primary); }
        .nav-item a.active { background: var(--primary); color: white; box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2); }
        .nav-item a i { width: 20px; text-align: center; font-size: 16px; }

        .refer-card { margin: 20px; padding: 15px; background: var(--bg-color); border-radius: 12px; border: 1px dashed #cbd5e1; text-align: center; }
        .refer-card h4 { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 5px; }
        .refer-card p { font-size: 11px; color: var(--text-muted); margin-bottom: 12px; line-height: 1.4; }
        .btn-refer { display: inline-block; background: var(--primary); color: white; text-decoration: none; font-size: 12px; font-weight: 600; padding: 8px 15px; border-radius: 6px; }

        /* --- Main Content --- */
        .main-content { flex: 1; margin-left: 260px; display: flex; flex-direction: column; height: 100vh; overflow: hidden; }

        .top-header { height: 70px; background: var(--sidebar-bg); border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between; padding: 0 30px; }
        .header-left { display: flex; align-items: center; gap: 30px; }
        .location-selector { display: flex; align-items: center; gap: 10px; cursor: pointer; }
        .location-icon { color: var(--primary); font-size: 18px; }
        .location-info span { font-size: 11px; color: var(--text-muted); display: block; }
        .location-info h4 { font-size: 14px; font-weight: 600; color: var(--text-main); margin: 0; }
        
        .search-bar { display: flex; align-items: center; background: var(--bg-color); border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 15px; width: 300px; gap: 10px; }
        .search-bar i { color: var(--text-muted); }
        .search-bar input { border: none; background: transparent; outline: none; width: 100%; font-size: 13px; font-family: 'Inter', sans-serif; }

        .btn-continue { display: inline-block; background: var(--primary); color: white; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 600; transition: background 0.3s; }
        .btn-continue:hover { background: #1d4ed8; }

        .header-right { display: flex; align-items: center; gap: 20px; }
        .notification { position: relative; font-size: 20px; color: var(--text-muted); cursor: pointer; }
        .notification .badge { position: absolute; top: -5px; right: -5px; background: #ef4444; color: white; font-size: 10px; font-weight: 700; width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 2px solid white; }
        .user-profile { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .user-profile img { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .user-info h4 { font-size: 13px; font-weight: 600; color: var(--text-main); margin: 0; }
        .user-info p { font-size: 11px; color: var(--text-muted); margin: 0; }

        .page-wrapper { padding: 30px; height: calc(100vh - 70px); overflow-y: auto; }
        
        /* Unified Card Styles */
        .dash-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #f1f5f9; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        
        /* Mobile Responsive */
        .sidebar-toggle { display: none; font-size: 20px; color: var(--text-main); cursor: pointer; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 90; }

        .mobile-back-btn { display: none; }
        .desktop-only { display: block; }
        
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.active { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: block; }
            .sidebar-overlay.active { display: block; }
            
            .search-bar { display: none; }
            .header-left { gap: 15px; }
            .top-header { padding: 0 15px; }
            .page-wrapper { padding: 15px; height: calc(100vh - 70px - 60px); }
            
            /* Clean up mobile header */
            .mobile-back-btn { display: block; }
            .desktop-only, .btn-continue, .user-info { display: none !important; }
        }
        
        @yield('styles')
    </style>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" onclick="toggleSidebar()"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <a href="{{ route('home') }}" class="brand">
                <div class="brand-icon">
                    <i class="fa-solid fa-bell-concierge" style="color: white; font-size: 14px;"></i>
                </div>
                <div class="brand-text">
                    QuickDines
                </div>
            </a>

            @php
                $currentRoute = request()->route()->getName();
            @endphp

            <ul class="nav-menu">
                <li class="nav-item"><a href="{{ route('dashboard') }}" class="{{ $currentRoute == 'dashboard' ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list"></i> My Orders</a></li>
                <li class="nav-item"><a href="{{ route('track-order') }}" class="{{ $currentRoute == 'track-order' ? 'active' : '' }}"><i class="fa-solid fa-location-crosshairs"></i> Track Order</a></li>
                <li class="nav-item"><a href="{{ route('favourites') }}" class="{{ $currentRoute == 'favourites' ? 'active' : '' }}"><i class="fa-regular fa-heart"></i> Favourites</a></li>
                <li class="nav-item"><a href="{{ route('profile') }}" class="{{ $currentRoute == 'profile' ? 'active' : '' }}"><i class="fa-regular fa-user"></i> Profile</a></li>
                <li class="nav-item"><a href="{{ route('settings') }}" class="{{ $currentRoute == 'settings' ? 'active' : '' }}"><i class="fa-solid fa-gear"></i> Settings</a></li>
                <li class="nav-item"><a href="{{ route('customer.support') }}" class="{{ $currentRoute == 'customer.support' ? 'active' : '' }}"><i class="fa-solid fa-headset"></i> Help & Support (Chat)</a></li>
                <li class="nav-item"><a href="{{ route('customer.tickets') }}" class="{{ $currentRoute == 'customer.tickets' ? 'active' : '' }}"><i class="fa-solid fa-ticket"></i> Support Tickets</a></li>
            </ul>

        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Header -->
            <header class="top-header">
                <div class="header-left">
                    <div class="sidebar-toggle" onclick="toggleSidebar()">
                        <i class="fa-solid fa-bars"></i>
                    </div>
                    <a href="{{ route('home') }}" class="mobile-back-btn" style="color: var(--text-main); font-size: 13px; font-weight: 600; text-decoration: none; border: 1px solid #e2e8f0; padding: 4px 10px; border-radius: 6px; display: flex; align-items: center; gap: 6px;"><i class="fa-solid fa-arrow-left"></i> Back</a>
                    <div class="location-selector desktop-only">
                        <i class="fa-solid fa-location-dot location-icon"></i>
                        <div class="location-info">
                            <span>Delivering to</span>
                            <h4>Visakhapatnam <i class="fa-solid fa-chevron-down" style="font-size:10px; margin-left:5px;"></i></h4>
                        </div>
                    </div>
                    
                    <form action="{{ route('search') }}" method="GET" class="search-bar">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" name="query" placeholder="Search for restaurants or cuisines..." value="{{ request('query') }}">
                    </form>
                </div>

                <div class="header-right">
                    <a href="{{ route('home') }}" class="btn-continue">Continue Ordering</a>
                    <div class="user-profile" onclick="toggleProfileDropdown()" style="position: relative; cursor: pointer;">
                        @php
                            $headerAvatar = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User').'&background=e2e8f0&color=0f172a';
                        @endphp
                        <img src="{{ $headerAvatar }}" alt="User">
                        <div class="user-info">
                            <h4>{{ auth()->user()->name ?? 'User' }}</h4>
                            <p>{{ auth()->user()->mobile ?? 'Phone' }} <i class="fa-solid fa-chevron-down" style="font-size:9px; margin-left:3px;"></i></p>
                        </div>
                        
                        <!-- Dropdown Menu -->
                        <div class="profile-dropdown" id="profileDropdown" style="display: none; position: absolute; top: calc(100% + 5px); right: 0; background: white; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border: 1px solid #e2e8f0; width: 160px; z-index: 1000; overflow: hidden;">
                            <a href="{{ route('profile') }}" style="display: block; padding: 12px 15px; color: #0f172a; text-decoration: none; font-size: 14px; font-weight: 500; border-bottom: 1px solid #f1f5f9; transition: background 0.2s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='white'"><i class="fa-regular fa-user" style="margin-right: 8px; color: #2563eb;"></i> Profile</a>
                            <form method="POST" action="{{ route('customer.logout') }}" style="margin: 0;">
                                @csrf
                                <button type="submit" style="display: block; width: 100%; text-align: left; background: white; border: none; padding: 12px 15px; color: #ef4444; font-size: 14px; font-weight: 500; cursor: pointer; transition: background 0.2s;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'"><i class="fa-solid fa-arrow-right-from-bracket" style="margin-right: 8px;"></i> Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <div class="page-wrapper">
                @yield('content')
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.querySelector('.sidebar-overlay').classList.toggle('active');
        }

        function toggleProfileDropdown() {
            const dropdown = document.getElementById('profileDropdown');
            if (dropdown.style.display === 'none' || dropdown.style.display === '') {
                dropdown.style.display = 'block';
            } else {
                dropdown.style.display = 'none';
            }
        }

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const userProfile = document.querySelector('.user-profile');
            const dropdown = document.getElementById('profileDropdown');
            if (userProfile && dropdown && !userProfile.contains(e.target)) {
                dropdown.style.display = 'none';
            }
        });
    </script>
    
    <!-- Toastify JS -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Global Notification System -->
    <audio id="global-notification-sound" src="{{ asset('notification.mp3') }}" preload="auto"></audio>
    <script>
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
        let lastOrderUpdate = "{{ \App\Models\Order::where('user_id', auth()->id())->max('updated_at') ?? '' }}";
        
        setInterval(() => {
            fetch('{{ route("customer.notifications.check") }}')
                .then(res => res.json())
                .then(data => {
                    let hasNewMessage = data.messages > currentMessageCount;
                    let hasOrderUpdate = data.order_update && data.order_update !== lastOrderUpdate;

                    if (hasNewMessage || hasOrderUpdate) {
                        window.playNotificationSound();
                        
                        if (hasNewMessage) {
                            Toastify({
                                text: "New message from Support!",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: { background: "#6366f1", borderRadius: "8px", fontWeight: "600" },
                                onClick: function(){ window.location.href = "{{ route('customer.support') }}"; }
                            }).showToast();
                        }

                        if (hasOrderUpdate) {
                            Toastify({
                                text: "Your order status was updated!",
                                duration: 5000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                style: { background: "#10b981", borderRadius: "8px", fontWeight: "600" },
                                onClick: function(){ window.location.href = "{{ route('track-order') }}"; }
                            }).showToast();
                            
                            // Automatically reload if user is already on the track order page
                            if (window.location.pathname.includes('/track-order')) {
                                setTimeout(() => window.location.reload(), 1500);
                            }
                        }
                    }
                    
                    if(data.messages !== undefined) currentMessageCount = data.messages;
                    if(data.order_update !== undefined) lastOrderUpdate = data.order_update;
                });
        }, 2000); // Poll every 2 seconds
    </script>
    
    @yield('scripts')
@include('components.notification-sound', ['checkRoute' => route('customer.notifications.check')])
</body>
</html>

