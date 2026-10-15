@extends('layouts.admin')

@section('content')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    /* Custom Premium Flatpickr Theme Overrides (Airbnb base) */
    .flatpickr-calendar {
        font-family: 'Inter', sans-serif !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        border: none !important;
    }

    /* Override Airbnb's Red to our Dashboard Indigo */
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.inRange, .flatpickr-day.startRange.inRange, .flatpickr-day.endRange.inRange, .flatpickr-day.selected:focus, .flatpickr-day.startRange:focus, .flatpickr-day.endRange:focus, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.nextMonthDay {
        background: #6366f1 !important;
        border-color: #6366f1 !important;
    }
    .flatpickr-day.inRange, .flatpickr-day.prevMonthDay.inRange, .flatpickr-day.nextMonthDay.inRange, .flatpickr-day.today.inRange, .flatpickr-day.prevMonthDay.today.inRange, .flatpickr-day.nextMonthDay.today.inRange, .flatpickr-day:hover, .flatpickr-day.prevMonthDay:hover, .flatpickr-day.nextMonthDay:hover, .flatpickr-day:focus, .flatpickr-day.prevMonthDay:focus, .flatpickr-day.nextMonthDay:focus {
        background: #e0e7ff !important;
        border-color: #e0e7ff !important;
        color: #1e293b !important;
    }

    /* Dark Theme Support */
    body.dark-theme .flatpickr-calendar {
        background: #1e293b !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5) !important;
    }
    body.dark-theme .flatpickr-day, body.dark-theme .flatpickr-months .flatpickr-month, body.dark-theme .flatpickr-current-month input.cur-year, body.dark-theme .flatpickr-weekday {
        color: #f8fafc !important;
        fill: #f8fafc !important;
    }
    body.dark-theme .flatpickr-day.inRange, body.dark-theme .flatpickr-day:hover {
        background: #334155 !important;
        border-color: #334155 !important;
        color: #f8fafc !important;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }

    .breadcrumb-custom {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .breadcrumb-custom a {
        color: var(--text-muted);
        text-decoration: none;
    }

    .breadcrumb-custom span {
        margin: 0 5px;
    }

    .date-picker {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        color: var(--text-main);
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
    }

    /* Stat Cards */
    .stat-card {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        height: 100%;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .stat-content {
        flex: 1;
    }

    .stat-title {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        margin-bottom: 5px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
    }

    .stat-trend {
        font-size: 0.75rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        flex-wrap: nowrap;
        margin-top: 5px;
    }

    .trend-up { color: #10b981; }

    @media (max-width: 768px) {
        .stat-card {
            padding: 1rem 0.75rem;
            gap: 0.5rem;
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        .stat-value {
            font-size: 1.1rem;
        }
        .stat-title {
            font-size: 0.7rem;
            margin-bottom: 2px;
        }
    }

    .trend-label {
        font-size: 0.65rem;
        color: #94a3b8;
        font-weight: 500;
        display: flex;
        flex-direction: column;
        line-height: 1.1;
        margin-left: 6px;
        white-space: nowrap;
    }

    /* Card Layouts */
    .section-card {
        padding: 1.5rem;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .card-header-flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .card-title {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }

    .card-action {
        font-size: 0.8rem;
        color: var(--primary-color);
        font-weight: 500;
        text-decoration: none;
    }

    /* List Items */
    .list-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .list-item:last-child {
        border-bottom: none;
    }

    .item-left {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .item-icon {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f1f5f9;
        color: var(--primary-color);
    }

    .item-img {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        object-fit: cover;
    }

    .item-details {
        display: flex;
        flex-direction: column;
    }

    .item-name {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .item-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
        margin-top: 2px;
    }

    .item-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
    }

    .item-value {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-main);
    }

    .item-status {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 3px 8px;
        border-radius: 4px;
        margin-top: 3px;
    }

    .status-preparing { color: #3b82f6; background: #eff6ff; }
    .status-accepted { color: #f59e0b; background: #fffbeb; }
    .status-ready { color: #10b981; background: #ecfdf5; }
    .status-picked { color: #8b5cf6; background: #f5f3ff; }
    .status-active { color: #10b981; background: transparent; font-weight: 700; }

    /* Live Orders Total */
    .live-orders-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        margin-top: auto;
        border-top: 1px solid #f1f5f9;
        font-size: 0.85rem;
        font-weight: 600;
    }

    /* Action Cards */
    .action-card {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.2rem;
        border-radius: 12px;
        text-decoration: none;
        transition: transform 0.2s;
        border: 1px solid rgba(0,0,0,0.02);
    }

    .action-card:hover {
        transform: translateY(-2px);
    }

    .action-left {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .action-icon {
        width: 45px;
        height: 45px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .action-text h6 {
        margin: 0 0 3px 0;
        font-weight: 700;
        font-size: 0.95rem;
    }

    .action-text p {
        margin: 0;
        font-size: 0.75rem;
        opacity: 0.8;
    }

    .action-arrow {
        font-size: 1rem;
    }

    /* Colors for Action Cards */
    .action-purple { background: #faf5ff; color: #7e22ce; }
    .action-purple .action-icon { background: #f3e8ff; }
    .action-purple .action-text p { color: #9333ea; }

    .action-green { background: #f0fdf4; color: #15803d; }
    .action-green .action-icon { background: #dcfce7; }
    .action-green .action-text p { color: #16a34a; }

    .action-orange { background: #fff7ed; color: #c2410c; }
    .action-orange .action-icon { background: #ffedd5; }
    .action-orange .action-text p { color: #1d4ed8; }

    .action-pink { background: #fdf2f8; color: #be185d; }
    .action-pink .action-icon { background: #fce7f3; }
    .action-pink .action-text p { color: #db2777; }

    .action-blue { background: #eff6ff; color: #1d4ed8; }
    .action-blue .action-icon { background: #dbeafe; }
    .action-blue .action-text p { color: #2563eb; }

    /* Chart wrappers */
    .chart-wrapper {
        position: relative;
        height: 250px;
        width: 100%;
    }
    .doughnut-wrapper {
        position: relative;
        height: 200px;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    /* QR Scan legend */
    .qr-legend {
        margin-top: 20px;
    }
    .legend-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.75rem;
        margin-bottom: 8px;
    }
    .legend-label {
        display: flex;
        align-items: center;
        gap: 8px;
        color: var(--text-main);
        font-weight: 500;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    .legend-value {
        color: var(--text-muted);
    }

    /* Dropdown overrides */
    .filter-dropdown {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 6px;
        color: var(--text-muted);
        font-weight: 500;
        cursor: pointer;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <div class="breadcrumb-custom">
            <a href="#">Home</a> <span>&gt;</span> Dashboard
        </div>
    </div>
    
    <div class="date-picker shadow-sm dashboard-date-trigger" style="cursor: pointer;">
        <i class="fa-regular fa-calendar text-muted"></i>
        <span>{{ $thisWeekStart->format('M d, Y') }} - {{ $thisWeekEnd->format('M d, Y') }}</span>
        <i class="fa-solid fa-chevron-down text-muted ms-2" style="font-size: 0.7rem;"></i>
    </div>
</div>

<!-- Stat Cards Row -->
<div class="row g-3 mb-4">
    @php
        function renderTrend($trend) {
            $isUp = $trend >= 0;
            $colorClass = $isUp ? 'trend-up' : 'text-danger';
            $icon = $isUp ? 'fa-caret-up' : 'fa-caret-down';
            $trendAbs = abs($trend);
            return '<div class="stat-trend '.$colorClass.'">
                        <span><i class="fa-solid '.$icon.'"></i> '.$trendAbs.'%</span>
                        <span class="trend-label"><span>vs</span><span>last week</span></span>
                    </div>';
        }
    @endphp

    <div class="col-6 col-md-4 col-xl-4">
        <div class="glass-card stat-card">
            <div class="stat-icon" style="background: #f3e8ff; color: #8b5cf6;">
                <i class="fa-solid fa-bus-simple"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Total Buses</div>
                <div class="stat-value">{{ number_format($totalBuses) }}</div>
                {!! renderTrend($busesTrend) !!}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="glass-card stat-card">
            <div class="stat-icon" style="background: #dcfce7; color: #10b981;">
                <i class="fa-solid fa-bus"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Active Buses</div>
                <div class="stat-value">{{ number_format($activeBuses) }}</div>
                {!! renderTrend($activeBusesTrend) !!}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="glass-card stat-card">
            <div class="stat-icon" style="background: #ffedd5; color: #f97316;">
                <i class="fa-solid fa-store"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Total Restaurants</div>
                <div class="stat-value">{{ number_format($totalRestaurants) }}</div>
                {!! renderTrend($restaurantsTrend) !!}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="glass-card stat-card">
            <div class="stat-icon" style="background: #dbeafe; color: #3b82f6;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Total Customers</div>
                <div class="stat-value">{{ number_format($totalCustomers) }}</div>
                {!! renderTrend($customersTrend) !!}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="glass-card stat-card">
            <div class="stat-icon" style="background: #fce7f3; color: #ec4899;">
                <i class="fa-solid fa-bag-shopping"></i>
            </div>
            <div class="stat-content">
                <div class="stat-title">Today's Orders</div>
                <div class="stat-value">{{ number_format($todaysOrders) }}</div>
                {!! renderTrend($ordersTrend) !!}
            </div>
        </div>
    </div>

    <div class="col-6 col-md-4 col-xl-4">
        <div class="glass-card stat-card">
            <div class="stat-icon" style="background: #f3e8ff; color: #8b5cf6;">
                <i class="fa-solid fa-wallet"></i>
            </div>
            <div class="stat-content position-relative" style="min-width: 0;">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="stat-title mb-0 dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer; color: var(--primary);" id="revenueCardTitle">Revenue (Week / Today) &nbsp; <i class='fa-solid fa-caret-down'></i></div>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="font-size: 0.8rem; min-width: 120px; z-index: 1050;">
                        <li><a class="dropdown-item" href="#" onclick="toggleRevenue('week', event)">This Week</a></li>
                        <li><a class="dropdown-item" href="#" onclick="toggleRevenue('today', event)">Today</a></li>
                    </ul>
                </div>
                <div class="stat-value" id="revenueCardValue" style="font-size: 1.2rem;">₹<br>{{ number_format($revenueThisWeek) }}</div>
                <div id="revenueCardTrend">
                    {!! renderTrend($revenueTrend) !!}
                </div>

                <!-- Hidden Data -->
                <div id="hiddenTodayRevenue" style="display:none;">₹<br>{{ number_format($todaysRevenue) }}</div>
                <div id="hiddenTodayTrend" style="display:none;">{!! renderTrend($todaysRevenueTrend) !!}</div>
                <div id="hiddenWeekRevenue" style="display:none;">₹<br>{{ number_format($revenueThisWeek) }}</div>
                <div id="hiddenWeekTrend" style="display:none;">{!! renderTrend($revenueTrend) !!}</div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-4">
        <div class="glass-card section-card">
            <div class="card-header-flex">
                <h3 class="card-title">Orders Overview</h3>
                <div class="filter-dropdown dashboard-date-trigger" style="cursor: pointer;">
                    <span>{{ $thisWeekStart->format('M d') }} - {{ $thisWeekEnd->format('M d') }}</span>
                    <i class="fa-solid fa-chevron-down ms-1"></i>
                </div>
            </div>
            <div class="d-flex gap-4 mb-2 ms-2" style="font-size: 0.75rem; font-weight: 500;">
                <div class="d-flex align-items-center gap-2"><span style="width: 8px; height: 8px; border-radius: 50%; background: #3b82f6;"></span> Completed Orders</div>
                <div class="d-flex align-items-center gap-2"><span style="width: 8px; height: 8px; border-radius: 50%; background: #ec4899;"></span> Cancelled Orders</div>
            </div>
            <div class="chart-wrapper mt-3">
                <canvas id="ordersChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="glass-card section-card">
            <div class="card-header-flex">
                <h3 class="card-title">Revenue Overview</h3>
                <div class="filter-dropdown dashboard-date-trigger" style="cursor: pointer;">
                    <span>{{ $thisWeekStart->format('M d') }} - {{ $thisWeekEnd->format('M d') }}</span>
                    <i class="fa-solid fa-chevron-down ms-1"></i>
                </div>
            </div>
            <div class="chart-wrapper mt-4">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="glass-card section-card">
            <div class="card-header-flex">
                <h3 class="card-title">Live Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="card-action">View All</a>
            </div>

            <div class="list-container mt-3">
                @forelse($liveOrders as $order)
                <div class="list-item {{ $loop->last ? 'border-bottom-0 pb-0' : '' }}">
                    <div class="item-left">
                        @php
                            $restName = $order->restaurant ? $order->restaurant->restaurant_name : 'Restaurant';
                            $imgInitial = strtoupper(substr($restName, 0, 2));
                        @endphp
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($imgInitial) }}&background=f1f5f9&color=333" class="item-img" alt="{{ $restName }}">
                        <div class="item-details">
                            <span class="item-name">Order #QD{{ $order->id }}</span>
                            <span class="item-sub">{{ $restName }}</span>
                        </div>
                    </div>
                    <div class="item-right">
                        <span class="item-value">₹ {{ number_format($order->total_amount) }}</span>
                        <span class="item-status status-{{ strtolower($order->order_status) }}">{{ ucfirst($order->order_status) }}</span>
                    </div>
                </div>
                @empty
                <div class="p-3 text-center text-muted">No live orders at the moment.</div>
                @endforelse

                <div class="live-orders-total mt-3">
                    <span>Total Live Orders</span>
                    <span>{{ $totalLiveOrders }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Lists Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-3">
        <div class="glass-card section-card">
            <div class="card-header-flex">
                <h3 class="card-title">Top Buses by Orders</h3>
                <a href="{{ route('admin.buses.index') }}" class="card-action">View All</a>
            </div>

            @forelse($topBuses as $bus)
            <div class="list-item px-0 {{ $loop->last ? 'border-bottom-0' : '' }}">
                <div class="item-left">
                    <div class="item-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-bus"></i></div>
                    <div class="item-details">
                        <span class="item-name">{{ $bus->bus_number }}</span>
                        <span class="item-sub">Route Available in Booking</span>
                    </div>
                </div>
                <div class="item-right">
                    <span class="item-value text-dark" style="font-size:0.75rem;">{{ number_format($bus->orders_count) }} Orders</span>
                </div>
            </div>
            @empty
            <div class="p-3 text-center text-muted">No bus orders yet.</div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-3">
        <div class="glass-card section-card">
            <div class="card-header-flex">
                <h3 class="card-title">Top Restaurants</h3>
                <a href="{{ route('admin.vendors.index') }}" class="card-action">View All</a>
            </div>

            @forelse($topRestaurants as $restaurant)
            <div class="list-item px-0 {{ $loop->last ? 'border-bottom-0' : '' }}">
                <div class="item-left">
                    @php $imgInitial = strtoupper(substr($restaurant->restaurant_name, 0, 2)); @endphp
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($imgInitial) }}&background=1e293b&color=fff" class="item-img" alt="{{ $restaurant->restaurant_name }}">
                    <div class="item-details">
                        <span class="item-name">{{ $restaurant->restaurant_name }}</span>
                    </div>
                </div>
                <div class="item-right">
                    <span class="item-value text-dark" style="font-size:0.75rem;">{{ number_format($restaurant->orders_count) }} Orders</span>
                </div>
            </div>
            @empty
            <div class="p-3 text-center text-muted">No restaurant orders yet.</div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-4">
        <div class="glass-card section-card">
            <div class="card-header-flex">
                <h3 class="card-title">Recent Bus Registrations</h3>
                <a href="{{ route('admin.buses.index') }}" class="card-action">View All</a>
            </div>

            @forelse($recentBuses as $bus)
            <div class="list-item px-0 {{ $loop->last ? 'border-bottom-0' : '' }}">
                <div class="item-left">
                    <div class="item-icon" style="background:#f0fdf4; color:#10b981;"><i class="fa-solid fa-bus"></i></div>
                    <div class="item-details">
                        <span class="item-name">{{ $bus->bus_number }}</span>
                    </div>
                </div>
                <div class="item-right d-flex flex-row align-items-center gap-3">
                    <span class="item-sub">{{ $bus->created_at->format('M d, Y') }}</span>
                    <span class="item-status {{ $bus->status ? 'status-active' : 'status-inactive' }}">{{ $bus->status ? 'Active' : 'Inactive' }}</span>
                </div>
            </div>
            @empty
            <div class="p-3 text-center text-muted">No buses registered.</div>
            @endforelse
        </div>
    </div>

    <div class="col-lg-2">
        <div class="glass-card section-card">
            <h3 class="card-title mb-4">QR Scans (This Week)</h3>

            <div class="doughnut-wrapper">
                <canvas id="qrChart"></canvas>
            </div>

            <div class="qr-legend">
                @foreach($qrScans['breakdown'] as $scan)
                <div class="legend-item">
                    <div class="legend-label"><div class="legend-dot" style="background:{{ $scan['color'] }};"></div> {{ $scan['label'] }}</div>
                    <div class="legend-value">{{ number_format($scan['value']) }} ({{ $scan['percent'] }}%)</div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3">
    <div class="col">
        <a href="{{ route('admin.buses.create') }}" class="action-card action-purple">
            <div class="action-left">
                <div class="action-icon"><i class="fa-solid fa-bus-simple"></i></div>
                <div class="action-text">
                    <h6>Add New Bus</h6>
                    <p>Create a new bus</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fa-solid fa-chevron-right"></i></div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.restaurants.assign') }}" class="action-card action-green">
            <div class="action-left">
                <div class="action-icon"><i class="fa-solid fa-store"></i></div>
                <div class="action-text">
                    <h6>Assign Restaurants</h6>
                    <p>Assign to bus</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fa-solid fa-chevron-right"></i></div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.qr.index') }}" class="action-card action-orange">
            <div class="action-left">
                <div class="action-icon"><i class="fa-solid fa-qrcode"></i></div>
                <div class="action-text">
                    <h6>Generate QR Code</h6>
                    <p>Create QR for bus</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fa-solid fa-chevron-right"></i></div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.vendors.create') }}" class="action-card action-pink">
            <div class="action-left">
                <div class="action-icon"><i class="fa-solid fa-utensils"></i></div>
                <div class="action-text">
                    <h6>Add Restaurant</h6>
                    <p>Approve new restaurant</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fa-solid fa-chevron-right"></i></div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.reports.index') }}" class="action-card action-blue">
            <div class="action-left">
                <div class="action-icon"><i class="fa-solid fa-chart-line"></i></div>
                <div class="action-text">
                    <h6>View Reports</h6>
                    <p>Analytics & reports</p>
                </div>
            </div>
            <div class="action-arrow"><i class="fa-solid fa-chevron-right"></i></div>
        </a>
    </div>
</div>

<!-- Live Bus Tracking Grid -->
<div class="d-flex justify-content-between align-items-center mb-4 mt-5">
    <h2 class="page-title" style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Network Command Center <span style="font-size: 13px; font-weight: 500; color: #64748b;">Monitor all active buses in real-time</span></h2>
</div>

@if(isset($trackingBuses) && $trackingBuses->count() > 0)
    <div class="row g-4 mb-4">
        @foreach($trackingBuses as $bus)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0; padding: 15px 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 0; color: #0f172a; display: flex; align-items: center;">
                                <i class="fa-solid fa-bus text-primary me-2"></i> {{ $bus->bus_number }}
                            </h3>
                            <div style="display: flex; gap: 8px;">
                                <span style="font-size: 11px; padding: 4px 8px; background: {{ $bus->journey_start_time ? '#dcfce7' : '#f1f5f9' }}; color: {{ $bus->journey_start_time ? '#16a34a' : '#64748b' }}; border-radius: 20px;">
                                    {{ $bus->journey_start_time ? 'Journey Started' : 'Not Started' }}
                                </span>
                            </div>
                        </div>
                        @if($bus->busRoute)
                            <div style="font-size: 12px; color: #64748b; margin-top: 5px;">Route: {{ $bus->busRoute->name }}</div>
                        @endif
                    </div>

                    <div class="card-body" style="padding: 20px; max-height: 400px; overflow-y: auto;">
                        @if($bus->journey_delay_minutes > 0)
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Bus is running <strong>{{ $bus->journey_delay_minutes }} minutes</strong> late.</span>
                            </div>
                            <a href="{{ route('admin.bus-routes.show', $bus->bus_route_id) }}" class="btn btn-sm btn-danger" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                View Details
                            </a>
                        </div>
                        @elseif($bus->journey_delay_minutes < 0)
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Bus is running <strong>{{ abs($bus->journey_delay_minutes) }} minutes</strong> early.</span>
                            </div>
                            <a href="{{ route('admin.bus-routes.show', $bus->bus_route_id) }}" class="btn btn-sm btn-success" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;">
                                View Details
                            </a>
                        </div>
                        @endif

                        @if($bus->busRoute && $bus->busRoute->stops->count() > 0)
                            <div style="position: relative; padding-left: 30px;">
                                <!-- Timeline Vertical Line -->
                                <div style="position: absolute; top: 10px; bottom: 20px; left: 11px; width: 2px; background: #e2e8f0; z-index: 1;"></div>

                                @php
                                    $currentStopPassed = false;
                                @endphp

                                @foreach($bus->busRoute->stops as $stop)
                                    @php
                                        $isCurrentStop = $bus->current_stop_id == $stop->id;
                                        if ($isCurrentStop) {
                                            $currentStopPassed = true;
                                        }

                                        $statusColor = '#94a3b8'; // default gray (upcoming)
                                        $statusIcon = 'fa-circle';
                                        $textColor = '#64748b';

                                        if (!$currentStopPassed && !$isCurrentStop) {
                                            // Passed stop
                                            $statusColor = '#16a34a';
                                            $statusIcon = 'fa-solid fa-circle-check';
                                            $textColor = '#475569';
                                        } elseif ($isCurrentStop) {
                                            // Current stop
                                            $statusColor = '#3b82f6';
                                            $statusIcon = 'fa-solid fa-location-crosshairs';
                                            $textColor = '#0f172a';
                                        }
                                    @endphp

                                    <div style="position: relative; margin-bottom: {{ $loop->last ? '0' : '20px' }}; z-index: 2;">
                                        <!-- Marker -->
                                        <div style="position: absolute; left: -30px; top: 2px; width: 24px; height: 24px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: {{ $statusColor }}; font-size: 14px;">
                                            <i class="{{ $statusIcon }}"></i>
                                        </div>

                                        <!-- Stop Info -->
                                        <div>
                                            <h4 style="font-size: 14px; font-weight: {{ $isCurrentStop ? '700' : '600' }}; color: {{ $textColor }}; margin-bottom: 2px;">{{ $stop->stop_name }}</h4>

                                            <div style="display: flex; gap: 15px; font-size: 12px; color: #64748b;">
                                                @if($bus->journey_start_time || $stop->arrival_time)
                                                    <span style="color: {{ $isCurrentStop ? '#3b82f6' : '#64748b' }}; font-weight: 500;">
                                                        <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                                                        @if($bus->journey_delay_minutes > 0 && $stop->arrival_time)
                                                            <del class="text-muted" style="margin-right: 4px; font-size: 11px; opacity: 0.7;">{{ \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') }}</del>
                                                            <span style="color: #ef4444;">{{ $bus->calculateEtaForStop($stop) ? $bus->calculateEtaForStop($stop)->format('h:i A') : 'N/A' }}</span>
                                                        @else
                                                            ETA: {{ $bus->calculateEtaForStop($stop) ? $bus->calculateEtaForStop($stop)->format('h:i A') : 'N/A' }}
                                                        @endif
                                                    </span>
                                                @else
                                                    <span>
                                                        <i class="fa-solid fa-hourglass-start" style="margin-right: 4px;"></i>Pending Schedule
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted p-4">
                                <i class="fa-solid fa-route fs-2 mb-2 opacity-50"></i>
                                <p class="mb-0">No route defined for this bus.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif

@endsection

@section('scripts')
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        // --- Orders Overview Chart (Line Area) ---
        const ctxOrders = document.getElementById('ordersChart').getContext('2d');

        // Gradient for Completed
        const gradientCompleted = ctxOrders.createLinearGradient(0, 0, 0, 300);
        gradientCompleted.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
        gradientCompleted.addColorStop(1, 'rgba(59, 130, 246, 0)');

        // Gradient for Cancelled
        const gradientCancelled = ctxOrders.createLinearGradient(0, 0, 0, 300);
        gradientCancelled.addColorStop(0, 'rgba(236, 72, 153, 0.2)');
        gradientCancelled.addColorStop(1, 'rgba(236, 72, 153, 0)');

        new Chart(ctxOrders, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'Completed Orders',
                        data: {!! json_encode($completedOrdersChart) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: gradientCompleted,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#3b82f6',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Cancelled Orders',
                        data: {!! json_encode($cancelledOrdersChart) !!},
                        borderColor: '#ec4899',
                        backgroundColor: gradientCancelled,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#ec4899',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false, drawBorder: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: {
                        grid: { borderDash: [4, 4], color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 }, callback: function(value) { return value >= 1000 ? (value/1000) + 'K' : value; } },
                        beginAtZero: true
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        // --- Revenue Overview Chart (Line Area) ---
        const ctxRevenue = document.getElementById('revenueChart').getContext('2d');

        const gradientRevenue = ctxRevenue.createLinearGradient(0, 0, 0, 300);
        gradientRevenue.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
        gradientRevenue.addColorStop(1, 'rgba(16, 185, 129, 0)');

        new Chart(ctxRevenue, {
            type: 'line',
            data: {
                labels: {!! json_encode($dates) !!},
                datasets: [
                    {
                        label: 'Revenue',
                        data: {!! json_encode($revenueChart) !!},
                        borderColor: '#10b981',
                        backgroundColor: gradientRevenue,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#10b981',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false, drawBorder: false }, ticks: { color: '#94a3b8', font: { size: 11 } } },
                    y: {
                        grid: { borderDash: [4, 4], color: '#f1f5f9', drawBorder: false },
                        ticks: { color: '#94a3b8', font: { size: 11 }, callback: function(value) { return value + 'L'; }, stepSize: 2, max: 10 },
                        beginAtZero: true
                    }
                },
                interaction: { intersect: false, mode: 'index' }
            }
        });

        // --- QR Scans Doughnut Chart ---
        const ctxQR = document.getElementById('qrChart').getContext('2d');

        // Custom plugin to draw text in center of doughnut
        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw: function(chart) {
                const width = chart.width, height = chart.height, ctx = chart.ctx;
                ctx.restore();

                ctx.font = "bold 24px Inter, sans-serif";
                ctx.textBaseline = "middle";
                ctx.fillStyle = "#1e293b";

                const text = "{{ number_format($qrScans['total']) }}", textX = Math.round((width - ctx.measureText(text).width) / 2), textY = height / 2 - 8;
                ctx.fillText(text, textX, textY);

                ctx.font = "500 12px Inter, sans-serif";
                ctx.fillStyle = "#64748b";
                const subText = "Total Scans", subTextX = Math.round((width - ctx.measureText(subText).width) / 2), subTextY = height / 2 + 12;
                ctx.fillText(subText, subTextX, subTextY);

                ctx.save();
            }
        };

        new Chart(ctxQR, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_column($qrScans['breakdown'], 'label')) !!},
                datasets: [{
                    data: {!! json_encode(array_column($qrScans['breakdown'], 'percent')) !!},
                    backgroundColor: {!! json_encode(array_column($qrScans['breakdown'], 'color')) !!},
                    borderWidth: 0,
                    cutout: '75%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': ' + context.raw + '%';
                            }
                        }
                    }
                }
            },
            plugins: [centerTextPlugin]
        });

        // Initialize Flatpickr on all date triggers
        flatpickr(".dashboard-date-trigger", {
            mode: "range",
            dateFormat: "Y-m-d",
            defaultDate: ["{{ $thisWeekStart->format('Y-m-d') }}", "{{ $thisWeekEnd->format('Y-m-d') }}"],
            onChange: function(selectedDates, dateStr, instance) {
                if (selectedDates.length === 2) {
                    const start = instance.formatDate(selectedDates[0], "Y-m-d");
                    const end = instance.formatDate(selectedDates[1], "Y-m-d");
                    // Redirect with query parameters
                    window.location.href = "{{ route('admin.dashboard') }}?start_date=" + start + "&end_date=" + end;
                }
            }
        });
    });

    function toggleRevenue(type, event) {
        event.preventDefault();
        const title = document.getElementById('revenueCardTitle');
        const val = document.getElementById('revenueCardValue');
        const trend = document.getElementById('revenueCardTrend');

        if (type === 'today') {
            title.innerHTML = "Revenue (Today) &nbsp; <i class='fa-solid fa-caret-down'></i>";
            val.innerHTML = document.getElementById('hiddenTodayRevenue').innerHTML;
            trend.innerHTML = document.getElementById('hiddenTodayTrend').innerHTML;
        } else {
            title.innerHTML = "Revenue (Selected) &nbsp; <i class='fa-solid fa-caret-down'></i>";
            val.innerHTML = document.getElementById('hiddenWeekRevenue').innerHTML;
            trend.innerHTML = document.getElementById('hiddenWeekTrend').innerHTML;
        }
    }
</script>
@endsection
