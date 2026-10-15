@extends('layouts.vendor')

@section('title')
    Welcome back, {{ Auth::user()->name }}!
    <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block; margin-top: 4px;">Manage orders and grow your business.</span>
@endsection

@section('header_actions')
    <div class="status-toggle">
        <div class="status-dot"></div> Open
    </div>
@endsection

@push('styles')
<style>
    /* Top Header Adjustments */
    .topbar { margin-bottom: 24px; }
    .page-title { font-size: 24px; font-weight: 800; display: flex; flex-direction: column; gap: 4px; }
    .page-title span { font-size: 13px; font-weight: 500; color: #64748b; }
    
    .header-actions { display: flex; align-items: center; gap: 16px; }
    .status-toggle {
        display: flex; align-items: center; gap: 8px; background: #f8fafc;
        border: 1px solid #e2e8f0; padding: 6px 12px; border-radius: 20px; font-size: 13px; font-weight: 600;
    }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; background: #10b981; }
    .noti-btn { width: 40px; height: 40px; border-radius: 50%; background: #fff; border: 1px solid #e2e8f0; display: flex; align-items: center; justify-content: center; position: relative; cursor: pointer; }
    .noti-badge { position: absolute; top: 0; right: 0; width: 16px; height: 16px; background: #ef4444; color: white; border-radius: 50%; font-size: 10px; display: flex; align-items: center; justify-content: center; font-weight: bold; border: 2px solid white; }
    
    /* Stats Grid */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card {
        background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0;
    }
    .s-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .s-info p { font-size: 12px; color: #64748b; margin-bottom: 4px; font-weight: 600; }
    .s-info h3 { font-size: 22px; font-weight: 800; color: #0f172a; }
    .s-link { font-size: 12px; font-weight: 600; color: var(--primary); text-decoration: none; display: flex; align-items: center; gap: 4px; margin-top: 8px; }

    /* Main Sections */
    .main-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    .bottom-grid { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 24px; }
    
    .panel-card { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .panel-header h3 { font-size: 16px; font-weight: 700; color: #0f172a; }
    .view-all { font-size: 12px; color: var(--primary); font-weight: 600; text-decoration: none; }

    /* Orders List */
    .recent-orders { display: flex; flex-direction: column; gap: 16px; }
    .order-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; }
    .order-item:last-child { border: none; padding-bottom: 0; }
    .oi-left { display: flex; gap: 12px; align-items: center; }
    .oi-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
    .oi-details .id { font-size: 12px; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
    .oi-details .meta { font-size: 11px; color: #64748b; }
    .oi-middle { flex: 1; text-align: center; }
    .oi-middle .name { font-size: 13px; font-weight: 600; color: #0f172a; margin-bottom: 2px; }
    .oi-right { text-align: right; }
    .oi-price { font-size: 14px; font-weight: 700; color: #0f172a; margin-bottom: 4px; }
    
    /* Menu Management List */
    .menu-item { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 16px; margin-bottom: 16px; }
    .menu-item:last-child { border: none; padding-bottom: 0; margin-bottom: 0; }
    .mi-left { display: flex; gap: 12px; align-items: center; flex: 1; }
    .mi-img { width: 40px; height: 40px; border-radius: 8px; object-fit: cover; }
    .mi-name { font-size: 14px; font-weight: 600; color: #0f172a; }
    .mi-price { font-size: 13px; color: #64748b; width: 60px; text-align: right; margin-right: 20px;}

    /* Toggle Switch */
    .switch { position: relative; display: inline-block; width: 36px; height: 20px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .3s; border-radius: 20px; }
    .slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 2px; bottom: 2px; background-color: white; transition: .3s; border-radius: 50%; box-shadow: 0 1px 2px rgba(0,0,0,0.2); }
    input:checked + .slider { background-color: #10b981; }
    input:checked + .slider:before { transform: translateX(16px); }

    /* Bottom Widgets */
    .chart-container { height: 180px; width: 100%; }
    .sales-head { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
    .sales-amount { font-size: 24px; font-weight: 800; color: #0f172a; }
    .sales-trend { font-size: 12px; color: #10b981; font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .sales-trend-text { font-size: 12px; color: #64748b; }

    .wallet-boxes { display: flex; gap: 12px; margin-bottom: 20px; }
    .w-box { flex: 1; background: #f8fafc; padding: 12px; border-radius: 8px; }
    .w-box p { font-size: 11px; color: #64748b; margin-bottom: 4px; }
    .w-box h4 { font-size: 16px; font-weight: 700; color: #0f172a; }
    .btn-withdraw { width: 100%; text-align: center; display: block; padding: 10px; background: #f0f9ff; color: #0284c7; font-weight: 600; font-size: 13px; border-radius: 8px; text-decoration: none; margin-bottom: 16px;}
    .w-bottom { display: flex; justify-content: space-between; font-size: 12px; border-top: 1px solid #e2e8f0; padding-top: 16px; }
    .w-bottom span:last-child { font-weight: 700; color: #0f172a; }

    .txn-item { display: flex; justify-content: space-between; align-items: center; font-size: 12px; margin-bottom: 16px; }
    .txn-item:last-child { margin-bottom: 0; }
    .txn-left { color: #64748b; width: 120px; }
    .txn-middle { flex: 1; font-weight: 500; color: #0f172a; }
    .txn-right { font-weight: 700; }
    .t-plus { color: #10b981; }
    .t-minus { color: #ef4444; }

    /* Footer / KYC */
    .kyc-footer { display: flex; align-items: center; justify-content: space-between; background: #fff; padding: 16px 24px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; }
    .kyc-title { font-size: 14px; font-weight: 700; color: #0f172a; }
    .kyc-badges { display: flex; gap: 24px; }
    .k-badge { display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 600; color: #0f172a; }
    .k-icon { width: 24px; height: 24px; background: #dcfce3; color: #166534; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 12px; }
    .k-text span { display: block; color: #10b981; font-size: 10px; font-weight: 700; }

    /* Mobile Responsiveness */
    @media (max-width: 768px) {
        .topbar { display: flex; flex-direction: column !important; align-items: flex-start !important; gap: 15px; }
        .header-actions { flex-wrap: wrap; width: 100%; justify-content: flex-start; gap: 10px; }
        .page-title { font-size: 20px; }
        
        .stats-grid { grid-template-columns: 1fr 1fr; gap: 12px; }
        .main-grid { grid-template-columns: 1fr; gap: 16px; }
        .bottom-grid { grid-template-columns: 1fr; gap: 16px; }
        
        .kyc-footer { flex-direction: column; align-items: flex-start; gap: 16px; padding: 16px; }
        .kyc-badges { flex-direction: column; gap: 12px; width: 100%; }
        .k-badge { width: 100%; }
        
        .txn-item { flex-direction: column; align-items: flex-start; gap: 4px; }
        .txn-left { width: 100%; font-size: 11px; }
        .txn-right { align-self: flex-end; margin-top: -20px; }
    }
    @media (max-width: 480px) {
        .stats-grid { grid-template-columns: 1fr; }
    }

</style>
@endpush

@section('content')



<!-- Top Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="s-icon" style="background: #e0f2fe; color: #0284c7;"><i class="fa-solid fa-bus"></i></div>
        <div class="s-info">
            <p>Approaching Buses</p>
            <h3>{{ $busesCount }}</h3>
            <a href="{{ route('vendor.tracking') }}" class="s-link">View buses <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="s-icon" style="background: #dcfce7; color: #16a34a;"><i class="fa-solid fa-clipboard-check"></i></div>
        <div class="s-info">
            <p>Today's Orders</p>
            <h3>{{ $todaysOrders }}</h3>
            <a href="{{ route('vendor.orders.index') }}" class="s-link" style="color:#16a34a;">View orders <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="s-icon" style="background: #ffedd5; color: #1d4ed8;"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        <div class="s-info">
            <p>Today's Revenue</p>
            <h3>₹{{ number_format($todaysRevenue) }}</h3>
            <a href="{{ route('vendor.analytics') }}" class="s-link" style="color:#1d4ed8;">View earnings <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
    <div class="stat-card">
        <div class="s-icon" style="background: #f3e8ff; color: #9333ea;"><i class="fa-regular fa-clock"></i></div>
        <div class="s-info">
            <p>Preparing Orders</p>
            <h3>{{ $preparingOrdersCount }}</h3>
            <a href="{{ route('vendor.orders.index') }}?status=preparing" class="s-link" style="color:#9333ea;">View details <i class="fa-solid fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<!-- Main Middle Grid -->
<div class="main-grid">
    <!-- Recent Orders -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Recent Orders</h3>
            <a href="{{ route('vendor.orders.index') }}" class="view-all">View all</a>
        </div>
        <div class="recent-orders">
            @forelse($orders->take(4) as $order)
            <div class="order-item">
                <div class="oi-left">
                    <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80" class="oi-img">
                    <div class="oi-details">
                        <div class="id">#ORD{{ $order->id + 12344 }}</div>
                        <div class="meta">{{ $order->bus->bus_number ?? 'N/A' }} &bull; Seat {{ $order->seat_number ?? 'Any' }}</div>
                    </div>
                </div>
                <div class="oi-middle">
                    <div class="name">{{ $order->user->name ?? 'Guest' }}</div>
                </div>
                <div class="oi-right">
                    <div class="oi-price">₹{{ number_format($order->total_amount) }}</div>
                    <span class="badge badge-{{ strtolower($order->order_status) }}">{{ ucfirst($order->order_status) }}</span>
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 20px; color: #64748b; font-size: 13px;">No recent orders found.</div>
            @endforelse
        </div>
    </div>

    <!-- Menu Management -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Menu Management</h3>
            <a href="{{ route('vendor.menus.index') }}" class="view-all">View all</a>
        </div>
        <div class="menu-list">
            @forelse($menus as $menu)
            <div class="menu-item">
                <div class="mi-left">
                    <img src="{{ $menu->image ? asset('storage/' . $menu->image) : 'https://images.unsplash.com/photo-1563379926898-05f45c51040c?auto=format&fit=crop&w=100&q=80' }}" class="mi-img">
                    <div class="mi-name">{{ $menu->item_name }}</div>
                </div>
                <div class="mi-price">₹{{ number_format($menu->price) }}</div>
                <div>
                    <label class="switch">
                        <input type="checkbox" class="menu-toggle" data-id="{{ $menu->id }}" {{ $menu->availability ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 20px; color: #64748b; font-size: 13px;">No menu items added yet.</div>
            @endforelse
            
            <a href="{{ route('vendor.menus.index') }}" class="btn btn-primary" style="width: 100%; justify-content: center; margin-top: 8px;">
                <i class="fa-solid fa-plus"></i> Add New Item
            </a>
        </div>
    </div>
</div>

<!-- Bottom Grid -->
<div class="bottom-grid">
    <!-- Today's Sales Chart -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Today's Sales</h3>
            <a href="{{ route('vendor.analytics') }}" class="view-all">View report</a>
        </div>
        <div class="sales-head">
            <span class="sales-amount">₹{{ number_format($todaysRevenue) }}</span>
            @if($revenueTrend >= 0)
                <span class="sales-trend"><i class="fa-solid fa-arrow-up"></i> {{ $revenueTrend }}%</span>
            @else
                <span class="sales-trend" style="color:#ef4444;"><i class="fa-solid fa-arrow-down"></i> {{ abs($revenueTrend) }}%</span>
            @endif
            <span class="sales-trend-text">vs yesterday</span>
        </div>
        <div class="chart-container">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    <!-- Wallet Summary -->
    <div class="panel-card">
        <div class="panel-header" style="margin-bottom: 16px;">
            <h3>Wallet Summary</h3>
        </div>
        <div class="wallet-boxes">
            <div class="w-box" style="display:flex; align-items:center; gap:12px;">
                <div style="background:#0ea5e9; color:white; width:36px; height:36px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:16px;"><i class="fa-solid fa-wallet"></i></div>
                <div>
                    <p>Available Balance</p>
                    <h4>₹{{ number_format($availableBalance) }}</h4>
                </div>
            </div>
            <div class="w-box text-right" style="background:transparent; padding:0; display:flex; flex-direction:column; justify-content:center;">
                <p>Today's Earnings</p>
                <h4>₹{{ number_format($todaysRevenue) }}</h4>
            </div>
        </div>
        <a href="{{ route('vendor.wallet.index') }}" class="btn-withdraw">Withdraw Money</a>
        <div class="w-bottom">
            <span>This Month Earnings</span>
            <span>₹{{ number_format($thisMonthEarnings) }}</span>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="panel-card">
        <div class="panel-header">
            <h3>Recent Transactions</h3>
            <a href="{{ route('vendor.wallet.index') }}" class="view-all">View all</a>
        </div>
        <div class="txn-list">
            @forelse($recentTransactions as $txn)
            <div class="txn-item">
                <div class="txn-left">{{ $txn->created_at->format('d M, h:i A') }}</div>
                <div class="txn-middle">
                    @if($txn->type == 'credit')
                        {{ $txn->order_id ? 'Order #ORD' . ($txn->order_id + 12344) : 'Credit' }}
                    @else
                        Withdrawal
                    @endif
                </div>
                <div class="txn-right {{ $txn->type == 'credit' ? 't-plus' : 't-minus' }}">
                    {{ $txn->type == 'credit' ? '+' : '-' }} ₹{{ number_format($txn->amount) }}
                </div>
            </div>
            @empty
            <div class="text-center" style="padding: 10px; color: #64748b; font-size: 13px;">No recent transactions found.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Chart Initialization
        const ctx = document.getElementById('salesChart');
        if(ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode(array_reverse($salesLabels)) !!},
                    datasets: [{
                        label: 'Sales (₹)',
                        data: {!! json_encode(array_reverse($salesData)) !!},
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 3,
                        pointBackgroundColor: '#3b82f6'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 10 }, color: '#64748b' } },
                        y: { 
                            grid: { borderDash: [4, 4], color: '#f1f5f9' }, 
                            ticks: { 
                                font: { size: 10 }, color: '#64748b',
                                callback: function(value) { return value / 1000 + 'K'; }
                            },
                            border: { display: false }
                        }
                    }
                }
            });
        }

        // AJAX Toggle for Menu Availability
        const toggles = document.querySelectorAll('.menu-toggle');
        toggles.forEach(toggle => {
            toggle.addEventListener('change', function() {
                const menuId = this.getAttribute('data-id');
                const isChecked = this.checked;
                
                fetch(`/vendor/menus/${menuId}/toggle`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    if(!data.success) {
                        alert('Failed to update status.');
                        this.checked = !isChecked; // revert
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert('Error updating status.');
                    this.checked = !isChecked; // revert
                });
            });
        });

    });
</script>
@endpush
