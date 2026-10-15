@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .breadcrumb {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 4px;
    }
    .breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }

    .header-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }
    .btn-outline {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #475569;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card {
            padding: 12px;
            gap: 10px;
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }
        .stat-info h3 { font-size: 1.2rem; }
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }
    .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.emerald { background: #ecfdf5; color: #10b981; }
    .stat-icon.indigo { background: #e0e7ff; color: #6366f1; }
    .stat-icon.orange { background: #fff7ed; color: #f97316; }
    .stat-icon.teal { background: #f0fdfa; color: #14b8a6; }
    
    .stat-info { flex: 1; }
    .stat-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 600;
    }
    .stat-info h3 {
        margin: 4px 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }
    .stat-trend {
        font-size: 0.75rem;
        font-weight: 600;
    }
    .stat-trend.up { color: #10b981; }

    /* Dashboard Layout */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) {
        .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 992px) {
        .dashboard-grid { grid-template-columns: 1fr; }
    }
    
    .chart-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    .chart-header {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 24px;
    }

    /* Mock Charts */
    .mock-line-chart {
        height: 200px;
        width: 100%;
        background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,40 Q10,30 20,40 T40,20 T60,30 T80,10 T100,20" fill="none" stroke="%236366f1" stroke-width="2"/><path d="M0,40 Q10,30 20,40 T40,20 T60,30 T80,10 T100,20 L100,50 L0,50 Z" fill="rgba(99,102,241,0.1)" stroke="none"/></svg>');
        background-size: 100% 100%;
        position: relative;
    }
    .mock-line-chart-2 {
        height: 200px;
        width: 100%;
        background-image: url('data:image/svg+xml;utf8,<svg viewBox="0 0 100 50" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,30 Q15,40 30,20 T60,30 T85,15 T100,25" fill="none" stroke="%238b5cf6" stroke-width="2"/><path d="M0,30 Q15,40 30,20 T60,30 T85,15 T100,25 L100,50 L0,50 Z" fill="rgba(139,92,246,0.1)" stroke="none"/></svg>');
        background-size: 100% 100%;
        position: relative;
    }
    
    .x-axis {
        display: flex;
        justify-content: space-between;
        margin-top: 12px;
        font-size: 0.7rem;
        color: #94a3b8;
    }

    /* Donut Chart */
    .donut-container {
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .donut {
        width: 160px;
        height: 160px;
        border-radius: 50%;
        background: conic-gradient(
            #10b981 0% 64%, 
            #f59e0b 64% 80%, 
            #ef4444 80% 97%, 
            #64748b 97% 100%
        );
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .donut::after {
        content: '';
        width: 120px;
        height: 120px;
        background: #fff;
        border-radius: 50%;
        position: absolute;
    }
    .donut-inner {
        position: relative;
        z-index: 10;
        text-align: center;
    }
    .donut-inner h3 { margin: 0; font-size: 1.5rem; font-weight: 800; color: #1e293b; }
    .donut-inner p { margin: 0; font-size: 0.7rem; color: #64748b; }

    .donut-2 {
        background: conic-gradient(
            #3b82f6 0% 58%, 
            #10b981 58% 80%, 
            #f59e0b 80% 93%, 
            #ef4444 93% 100%
        );
    }
    .donut-2-inner h3 { margin: 0; font-size: 1.25rem; font-weight: 800; color: #1e293b; }

    .legend {
        display: flex;
        flex-direction: column;
        gap: 12px;
        flex: 1;
    }
    .legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.75rem;
        color: #475569;
    }
    .legend-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 8px;
    }

    /* Lists */
    .list-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .list-row:last-child { border-bottom: none; }
    .list-avatar {
        width: 32px; height: 32px; border-radius: 50%;
        background: #e2e8f0; display: flex; align-items: center; justify-content: center;
        font-weight: 700; font-size: 0.75rem; color: #475569;
    }
    .list-info { flex: 1; }
    .list-name { font-weight: 600; font-size: 0.875rem; color: #1e293b; }
    .list-val { font-size: 0.75rem; color: #64748b; text-align: right; }
    .progress-bar-bg {
        width: 60px; height: 4px; background: #f1f5f9; border-radius: 4px; overflow: hidden;
    }
    .progress-bar-fill {
        height: 100%; background: #6366f1; border-radius: 4px;
    }

</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Reports & Analytics</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Reports & Analytics
        </div>
    </div>
    <div class="header-actions">
        <button class="btn-outline"><i class="fa-regular fa-calendar"></i> Jul 27, 2026 - Aug 02, 2026</button>
        <button class="btn-outline"><i class="fa-solid fa-download"></i> Export</button>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-bag-shopping"></i></div>
        <div class="stat-info">
            <p>Total Orders</p>
            <h3>{{ number_format($totalOrders) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        <div class="stat-info">
            <p>Total Revenue</p>
            <h3>₹{{ number_format($totalRevenue, 2) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon indigo"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <p>Total Customers</p>
            <h3>{{ number_format($totalCustomers) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-store"></i></div>
        <div class="stat-info">
            <p>Total Restaurants</p>
            <h3>{{ number_format($totalRestaurants) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-motorcycle"></i></div>
        <div class="stat-info">
            <p>Total Drivers</p>
            <h3>{{ number_format($totalDrivers) }}</h3>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="chart-card">
        <div class="chart-header">Order Overview (Last 7 Days)</div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="orderChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">Revenue Overview (Last 7 Days)</div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <div class="chart-card">
        <div class="chart-header">Orders by Status</div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<div class="dashboard-grid">
    <div class="chart-card">
        <div class="chart-header">Top Restaurants</div>
        @forelse($topRestaurants as $index => $restaurant)
            @php 
                $colors = ['f97316', 'eab308', '10b981', 'ef4444', '3b82f6'];
                $color = $colors[$index % 5];
                $percent = $topRestaurants->first()->orders_count > 0 ? ($restaurant->orders_count / $topRestaurants->first()->orders_count) * 100 : 0;
            @endphp
            <div class="list-row">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($restaurant->restaurant_name) }}&background={{ $color }}&color=fff" class="list-avatar">
                <div class="list-info">
                    <div class="list-name">{{ $restaurant->restaurant_name }}</div>
                </div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" style="width: {{ $percent }}%;"></div></div>
                <div class="list-val">{{ $restaurant->orders_count }} Orders</div>
            </div>
        @empty
            <div class="list-row" style="color:#64748b; font-size:0.875rem;">No restaurants found.</div>
        @endforelse
    </div>
    
    <div class="chart-card">
        <div class="chart-header">Top Drivers</div>
        @forelse($topDrivers as $index => $driver)
            @php 
                $colors = ['3b82f6', 'ef4444', 'eab308', 'f97316', '6366f1'];
                $color = $colors[$index % 5];
                $percent = $topDrivers->first()->orders_count > 0 ? ($driver->orders_count / $topDrivers->first()->orders_count) * 100 : 0;
            @endphp
            <div class="list-row">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->user ? $driver->user->name : 'Driver') }}&background={{ $color }}&color=fff" class="list-avatar">
                <div class="list-info">
                    <div class="list-name">{{ $driver->user ? $driver->user->name : 'Driver' }}</div>
                </div>
                <div class="progress-bar-bg"><div class="progress-bar-fill" style="width: {{ $percent }}%; background:#10b981;"></div></div>
                <div class="list-val">{{ $driver->orders_count }} Deliveries</div>
            </div>
        @empty
            <div class="list-row" style="color:#64748b; font-size:0.875rem;">No drivers found.</div>
        @endforelse
    </div>
    
    <div class="chart-card">
        <div class="chart-header">Revenue by Payment Method</div>
        <div style="position: relative; height: 250px; width: 100%;">
            <canvas id="paymentChart"></canvas>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const dates = {!! json_encode($dates) !!};
    const ordersData = {!! json_encode($ordersData) !!};
    const revenueData = {!! json_encode($revenueData) !!};
    const orderStatuses = {!! json_encode($orderStatuses) !!};
    const paymentMethods = {!! json_encode($paymentMethods) !!};

    // Common Chart Options
    const commonOptions = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { display: false } },
            y: { grid: { color: '#f1f5f9' }, beginAtZero: true }
        }
    };

    // 1. Order Overview Chart
    new Chart(document.getElementById('orderChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Orders',
                data: ordersData,
                borderColor: '#6366f1',
                backgroundColor: 'rgba(99,102,241,0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: commonOptions
    });

    // 2. Revenue Overview Chart
    new Chart(document.getElementById('revenueChart').getContext('2d'), {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Revenue (₹)',
                data: revenueData,
                borderColor: '#8b5cf6',
                backgroundColor: 'rgba(139,92,246,0.1)',
                borderWidth: 2,
                tension: 0.4,
                fill: true
            }]
        },
        options: commonOptions
    });

    // 3. Orders by Status Donut
    new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(orderStatuses),
            datasets: [{
                data: Object.values(orderStatuses),
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444', '#64748b', '#3b82f6'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
            }
        }
    });

    // 4. Revenue by Payment Method Donut
    new Chart(document.getElementById('paymentChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(paymentMethods),
            datasets: [{
                data: Object.values(paymentMethods),
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { position: 'right', labels: { usePointStyle: true, boxWidth: 8 } }
            }
        }
    });
});
</script>
@endsection
