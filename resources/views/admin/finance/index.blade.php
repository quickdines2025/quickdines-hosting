@extends('layouts.admin')

@section('content')
@php
    $money = function ($v) { return '₹' . number_format($v, 2); };
    $payable = $stats['vendor_payable'];
    $settledPct = $payable > 0 ? round(($stats['settled'] / $payable) * 100) : 0;
    $paid = $stats['customer_payments'];
    $pct = function ($v) use ($paid) { return $paid > 0 ? round(($v / $paid) * 100, 1) : 0; };
    $vendorShare = $pct($stats['vendor_payable']);
    $commShare = $pct($stats['commission']);
    $gstShare = $pct($stats['gst']);
    $feeShare = $pct($stats['platform_fees']);
@endphp
<style>
    .page-header { display:flex; flex-wrap:wrap; gap:16px; justify-content:space-between; align-items:center; margin-bottom:24px; }
    .page-title { font-size:1.5rem; font-weight:700; color:#1e293b; margin:0; }
    .page-sub { font-size:.875rem; color:#64748b; margin-top:4px; }

    .filter-bar { background:#fff; padding:16px; border-radius:12px; display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:24px; box-shadow:0 1px 3px rgba(0,0,0,.05); border:1px solid #f1f5f9; }
    .filter-select { flex:1 1 160px; min-width:150px; position:relative; }
    .filter-select label { position:absolute; top:-8px; left:12px; background:#fff; padding:0 4px; font-size:.7rem; color:#64748b; font-weight:500; }
    .filter-select select, .filter-select input { width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:.875rem; color:#1e293b; outline:none; background:#fff; }
    .btn-filter-solid { flex:0 1 110px; justify-content:center; background:#6366f1; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-size:.875rem; font-weight:500; display:flex; align-items:center; gap:8px; cursor:pointer; }
    .btn-reset { flex:0 1 100px; justify-content:center; background:#fff; color:#64748b; border:1px solid #e2e8f0; padding:10px 20px; border-radius:8px; font-size:.875rem; font-weight:500; display:flex; align-items:center; gap:8px; text-decoration:none; }

    .section-title { font-size:.8rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.6px; margin:8px 0 12px; }
    .stats-grid { display:grid; gap:20px; margin-bottom:24px; }
    .stats-grid.c2 { grid-template-columns:repeat(2,1fr); }
    .stats-grid.c3 { grid-template-columns:repeat(3,1fr); }
    @media (max-width:992px) { .stats-grid.c3 { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:576px) { .stats-grid.c2, .stats-grid.c3 { grid-template-columns:1fr; } }
    .stat-card { background:#fff; border-radius:12px; padding:20px; display:flex; align-items:center; gap:16px; box-shadow:0 1px 3px rgba(0,0,0,.05); border:1px solid #f1f5f9; }
    .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
    .stat-icon.blue { background:#eff6ff; color:#3b82f6; }
    .stat-icon.slate { background:#f1f5f9; color:#475569; }
    .stat-icon.purple { background:#f5f3ff; color:#8b5cf6; }
    .stat-icon.green { background:#f0fdf4; color:#22c55e; }
    .stat-icon.orange { background:#fff7ed; color:#f97316; }
    .stat-icon.pink { background:#fdf2f8; color:#ec4899; }
    .stat-icon.teal { background:#f0fdfa; color:#14b8a6; }
    .stat-info p { margin:0; font-size:.875rem; color:#64748b; font-weight:500; }
    .stat-info h3 { margin:4px 0 0; font-size:1.35rem; font-weight:700; color:#1e293b; }
    .stat-info .sub-text { font-size:.75rem; color:#94a3b8; margin-top:2px; }

    .panel { background:#fff; border-radius:12px; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,.05); border:1px solid #f1f5f9; margin-bottom:24px; }
    .panel h4 { margin:0 0 4px; font-size:1rem; font-weight:700; color:#1e293b; }
    .panel .hint { font-size:.8rem; color:#94a3b8; margin-bottom:16px; }
    .split-bar { display:flex; height:14px; border-radius:999px; overflow:hidden; background:#e2e8f0; }
    .split-bar span { display:block; height:100%; }
    .legend { display:flex; flex-wrap:wrap; gap:20px; margin-top:16px; }
    .legend-item { display:flex; align-items:center; gap:8px; font-size:.85rem; color:#475569; }
    .dot { width:10px; height:10px; border-radius:50%; display:inline-block; }
    .legend-item strong { color:#1e293b; }

    .earn-row { display:flex; flex-wrap:wrap; align-items:center; gap:12px 28px; }
    .earn-item small { display:block; font-size:.75rem; color:#94a3b8; }
    .earn-item div { font-weight:700; color:#1e293b; font-size:1.05rem; }
    .earn-op { color:#cbd5e1; font-size:1.2rem; font-weight:700; }
    .earn-total div { color:#16a34a; }

    .progress-track { height:10px; background:#e2e8f0; border-radius:999px; overflow:hidden; }
    .progress-fill { height:100%; background:#22c55e; border-radius:999px; }

    .quick-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:16px; }
    @media (max-width:992px) { .quick-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:576px) { .quick-grid { grid-template-columns:1fr; } }
    .quick-link { background:#fff; border:1px solid #f1f5f9; border-radius:12px; padding:16px 18px; display:flex; align-items:center; justify-content:space-between; text-decoration:none; color:#1e293b; font-weight:600; box-shadow:0 1px 3px rgba(0,0,0,.05); transition:.15s; }
    .quick-link:hover { border-color:#c7d2fe; color:#6366f1; transform:translateY(-1px); }
    .quick-link i.fa-arrow-right { color:#94a3b8; font-size:.8rem; }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Finance Dashboard</h1>
        <div class="page-sub">Customer payments, QuickDines earnings and vendor settlements.</div>
    </div>
</div>

<form method="GET" action="{{ route('admin.finance.index') }}" class="filter-bar">
    <div class="filter-select">
        <label>Restaurant</label>
        <select name="restaurant_id">
            <option value="">All Restaurants</option>
            @foreach($restaurants as $restaurant)
            <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <label>From</label>
        <input type="date" name="date_from" value="{{ request('date_from') }}">
    </div>
    <div class="filter-select">
        <label>To</label>
        <input type="date" name="date_to" value="{{ request('date_to') }}">
    </div>
    <button type="submit" class="btn-filter-solid"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route('admin.finance.index') }}" class="btn-reset"><i class="fa-solid fa-rotate-right"></i> Reset</a>
</form>

<div class="section-title">Customer payments</div>
<div class="stats-grid c2">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <p>Total Customer Payments</p>
            <h3>{{ $money($stats['customer_payments']) }}</h3>
            <div class="sub-text">Successful customer transactions</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon slate"><i class="fa-solid fa-receipt"></i></div>
        <div class="stat-info">
            <p>Total Orders</p>
            <h3>{{ number_format($stats['total_orders']) }}</h3>
            <div class="sub-text">Paid orders</div>
        </div>
    </div>
</div>

<div class="section-title">QuickDines earnings</div>
<div class="stats-grid c3">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-percent"></i></div>
        <div class="stat-info">
            <p>Total Commission</p>
            <h3>{{ $money($stats['commission']) }}</h3>
            <div class="sub-text">From settlements</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fa-solid fa-file-invoice"></i></div>
        <div class="stat-info">
            <p>GST on Commission</p>
            <h3>{{ $money($stats['gst']) }}</h3>
            <div class="sub-text">Applied on commission only</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon teal"><i class="fa-solid fa-coins"></i></div>
        <div class="stat-info">
            <p>Total Platform Fees</p>
            <h3>{{ $money($stats['platform_fees']) }}</h3>
            <div class="sub-text">From settlements</div>
        </div>
    </div>
</div>

<div class="section-title">Vendor settlements</div>
<div class="stats-grid c3">
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-store"></i></div>
        <div class="stat-info">
            <p>Total Vendor Payable</p>
            <h3>{{ $money($stats['vendor_payable']) }}</h3>
            <div class="sub-text">Paid − Commission − GST − Fee</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info">
            <p>Total Settled</p>
            <h3>{{ $money($stats['settled']) }}</h3>
            <div class="sub-text">Already paid to vendors</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <p>Pending Settlement</p>
            <h3 style="color:#d97706;">{{ $money($stats['pending']) }}</h3>
            <div class="sub-text">Payable − Settled</div>
        </div>
    </div>
</div>

<div class="panel">
    <h4>Where the customer's money goes</h4>
    <div class="hint">Share of {{ $money($stats['customer_payments']) }} customer payments (settled orders only).</div>
    <div class="split-bar">
        <span style="width:{{ $vendorShare }}%; background:#f97316;"></span>
        <span style="width:{{ $commShare }}%; background:#8b5cf6;"></span>
        <span style="width:{{ $gstShare }}%; background:#ec4899;"></span>
        <span style="width:{{ $feeShare }}%; background:#14b8a6;"></span>
    </div>
    <div class="legend">
        <div class="legend-item"><span class="dot" style="background:#f97316;"></span> Vendor <strong>{{ $money($stats['vendor_payable']) }}</strong> ({{ $vendorShare }}%)</div>
        <div class="legend-item"><span class="dot" style="background:#8b5cf6;"></span> Commission <strong>{{ $money($stats['commission']) }}</strong> ({{ $commShare }}%)</div>
        <div class="legend-item"><span class="dot" style="background:#ec4899;"></span> GST <strong>{{ $money($stats['gst']) }}</strong> ({{ $gstShare }}%)</div>
        <div class="legend-item"><span class="dot" style="background:#14b8a6;"></span> Platform fee <strong>{{ $money($stats['platform_fees']) }}</strong> ({{ $feeShare }}%)</div>
    </div>
</div>

<div class="stats-grid c2" style="margin-bottom:24px;">
    <div class="panel" style="margin-bottom:0;">
        <h4>QuickDines total earnings</h4>
        <div class="hint">Commission + GST + Platform fees</div>
        <div class="earn-row">
            <div class="earn-item"><small>Commission</small><div>{{ $money($stats['commission']) }}</div></div>
            <span class="earn-op">+</span>
            <div class="earn-item"><small>GST</small><div>{{ $money($stats['gst']) }}</div></div>
            <span class="earn-op">+</span>
            <div class="earn-item"><small>Platform fees</small><div>{{ $money($stats['platform_fees']) }}</div></div>
            <span class="earn-op">=</span>
            <div class="earn-item earn-total"><small>Total</small><div>{{ $money($stats['quickdines_earnings']) }}</div></div>
        </div>
    </div>
    <div class="panel" style="margin-bottom:0;">
        <h4>Settlement progress</h4>
        <div class="hint">{{ $money($stats['settled']) }} of {{ $money($stats['vendor_payable']) }} paid to vendors</div>
        <div class="progress-track"><div class="progress-fill" style="width:{{ $settledPct }}%;"></div></div>
        <div style="margin-top:10px; font-size:.85rem; color:#475569;"><strong>{{ $settledPct }}%</strong> settled · {{ $money($stats['pending']) }} pending</div>
    </div>
</div>

<div class="section-title">Quick links</div>
<div class="quick-grid">
    <a href="{{ route('admin.finance.transactions.index') }}" class="quick-link"><span><i class="fa-solid fa-money-bill-transfer"></i>&nbsp; Transactions</span><i class="fa-solid fa-arrow-right"></i></a>
    <a href="{{ route('admin.finance.settlements.index') }}" class="quick-link"><span><i class="fa-solid fa-scale-balanced"></i>&nbsp; Settlements</span><i class="fa-solid fa-arrow-right"></i></a>
    <a href="{{ route('admin.finance.vendor-payouts.index') }}" class="quick-link"><span><i class="fa-solid fa-store"></i>&nbsp; Vendor Payouts</span><i class="fa-solid fa-arrow-right"></i></a>
    <a href="{{ route('admin.vendor-commission.index') }}" class="quick-link"><span><i class="fa-solid fa-percent"></i>&nbsp; Commission Settings</span><i class="fa-solid fa-arrow-right"></i></a>
</div>
@endsection
