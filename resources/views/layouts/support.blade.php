<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Support Panel - QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary: #2563eb; --bg-dark: #0f172a; --sidebar-width: 260px; --text-muted: #94a3b8; }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: #f8fafc; color: #334155; display: flex; min-height: 100vh; overflow-x: hidden; }
        
        .sidebar {
            width: var(--sidebar-width); background: var(--bg-dark); color: white;
            position: fixed; height: 100vh; display: flex; flex-direction: column; z-index: 100;
        }
        .brand { padding: 20px 24px; display: flex; align-items: center; gap: 12px; }
        .brand-icon { color: #fbbf24; font-size: 24px; }
        .brand-text { display: flex; flex-direction: column; }
        .brand-title { font-size: 18px; font-weight: 700; color: white; line-height: 1.2; }
        .brand-subtitle { font-size: 11px; font-weight: 500; color: var(--text-muted); }
        
        .nav-links { padding: 10px 16px; flex-grow: 1; overflow-y: auto; }
        .nav-links::-webkit-scrollbar { width: 4px; }
        .nav-links::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 4px; }
        
        .nav-section { font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin: 20px 0 8px 8px; letter-spacing: 0.5px; }
        
        .nav-item {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px;
            color: #cbd5e1; text-decoration: none; font-weight: 500; font-size: 13px;
            border-radius: 8px; margin-bottom: 2px; transition: all 0.2s;
        }
        .nav-item:hover { color: white; background: rgba(255,255,255,0.05); }
        .nav-item.active { color: white; background: var(--primary); font-weight: 600; box-shadow: 0 4px 6px rgba(37,99,235,0.2); }
        
        .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 0; display: flex; flex-direction: column; min-width: 0; }
        .topbar { display: flex; justify-content: space-between; align-items: center; padding: 16px 32px; background: #fff; border-bottom: 1px solid #e2e8f0; }
        .topbar-left { display: flex; align-items: center; gap: 16px; font-size: 18px; font-weight: 600; color: #0f172a; }
        .topbar-right { display: flex; align-items: center; gap: 24px; }
        
        .noti-bell { position: relative; color: #64748b; font-size: 20px; cursor: pointer; }
        .noti-badge { position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; font-size: 10px; font-weight: bold; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 2px solid white; }
        
        .user-profile { display: flex; align-items: center; gap: 12px; }
        .user-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
        .user-info { display: flex; flex-direction: column; }
        .user-name { font-size: 13px; font-weight: 600; color: #1e293b; }
        .user-status { font-size: 11px; color: #10b981; display: flex; align-items: center; gap: 4px; }
        .user-status::before { content: ''; width: 6px; height: 6px; background: #10b981; border-radius: 50%; display: inline-block; }
        
        .content-area { padding: 32px; flex: 1; background: #f8fafc; }
        
        .btn { padding: 8px 16px; border-radius: 6px; font-weight: 600; cursor: pointer; border: none; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 13px; transition: all 0.2s; }
        .btn-primary { background: var(--bg-dark); color: white; }
        .btn-primary:hover { background: #1e293b; }
        
        /* Utility Classes */
        .card { background: #fff; border-radius: 12px; padding: 24px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); border: 1px solid #e2e8f0; }
        .grid-4 { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
        .grid-5 { display: grid; grid-template-columns: repeat(5, 1fr); gap: 20px; }
        .grid-2-3 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }

        .sidebar-overlay { display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 90; }
        
        @media (max-width: 1200px) {
            .sidebar { transform: translateX(-100%); transition: transform 0.3s ease; }
            .sidebar.active { transform: translateX(0); }
            .sidebar-overlay.active { display: block; }
            .main-content { margin-left: 0; }
            .topbar { padding: 16px 20px; }
            .content-area { padding: 15px; }
            .grid-3, .grid-4, .grid-5, .grid-2-3, .settings-grid, .stats-grid { grid-template-columns: 1fr !important; }
            .filter-bar { flex-direction: column; align-items: stretch; }
            .filter-bar > * { width: 100% !important; margin-bottom: 10px; }
            .user-name { display: none; }
            
            table { display: block; width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; white-space: nowrap; }
        }
    </style>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="sidebar" id="sidebar">
        <div class="brand">
            <i class="fa-solid fa-utensils brand-icon"></i>
            <div class="brand-text">
                <span class="brand-title">QuickDines</span>
                <span class="brand-subtitle">Smart Dining</span>
            </div>
        </div>
        <div class="nav-links">
            <a href="{{ route('support.dashboard') }}" class="nav-item {{ request()->routeIs('support.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house" style="width: 20px; text-align: center;"></i> Dashboard
            </a>
            
            <div class="nav-section">SUPPORT MANAGEMENT</div>
            
            <a href="{{ route('support.chat.index') }}" class="nav-item {{ request()->routeIs('support.chat.*') ? 'active' : '' }}">
                <i class="fa-regular fa-comments" style="width: 20px; text-align: center;"></i> Live Chat
            </a>
            <a href="{{ route('support.orders.verification') }}" class="nav-item {{ request()->routeIs('support.orders.verification') ? 'active' : '' }}">
                <i class="fa-solid fa-clipboard-check" style="width: 20px; text-align: center;"></i> Order Verification
            </a>
            <a href="{{ route('support.cancellations') }}" class="nav-item {{ request()->routeIs('support.cancellations') ? 'active' : '' }}">
                <i class="fa-regular fa-circle-xmark" style="width: 20px; text-align: center;"></i> Cancellation Management
            </a>
            <a href="{{ route('support.refunds') }}" class="nav-item {{ request()->routeIs('support.refunds') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer" style="width: 20px; text-align: center;"></i> Refund Management
            </a>
            <a href="{{ route('support.tickets.index') }}" class="nav-item {{ request()->routeIs('support.tickets.*') ? 'active' : '' }}">
                <i class="fa-solid fa-ticket" style="width: 20px; text-align: center;"></i> Ticket Management
            </a>

            
            <a href="{{ route('support.settings') }}" class="nav-item {{ request()->routeIs('support.settings*') ? 'active' : '' }}" style="margin-top: 20px;">
                <i class="fa-solid fa-gear" style="width: 20px; text-align: center;"></i> Settings
            </a>
        </div>
        <div style="padding: 16px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-item" style="width: 100%; border: none; background: transparent; color: #ef4444; cursor: pointer;">
                    <i class="fa-solid fa-arrow-right-from-bracket" style="width: 20px; text-align: center;"></i> Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="topbar">
            <div class="topbar-left">
                <i class="fa-solid fa-bars" id="mobileMenuToggle" style="cursor: pointer; color: #64748b; margin-right: 8px; font-size: 20px;"></i>
            </div>
            <div class="topbar-right">
                <a href="{{ route('support.chat.index') }}" class="noti-bell" style="text-decoration: none; display: inline-block; color: inherit;">
                    <i class="fa-regular fa-bell"></i>
                    @php
                        $unreadMessages = \App\Models\Message::where('is_admin', false)->where('is_read', false)->count();
                    @endphp
                    @if($unreadMessages > 0)
                        <div class="noti-badge">{{ $unreadMessages }}</div>
                    @endif
                </a>
                <div class="user-profile">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=e2e8f0&color=475569" class="user-avatar" alt="Avatar">
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->name }}</span>
                        <span class="user-status">Online</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
                <div style="background: #dcfce3; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 600; font-size: 14px;">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            if(mobileMenuToggle && sidebar) {
                mobileMenuToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    sidebarOverlay.classList.toggle('active');
                });
                
                sidebarOverlay.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    sidebarOverlay.classList.remove('active');
                });
            }
        });
    </script>
@include('components.notification-sound', ['checkRoute' => route('support.notifications.check')])
</body>
</html>





