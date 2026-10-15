@extends('layouts.admin')

@section('content')
<style>
    .page-header { display:flex; flex-wrap:wrap; gap:16px; justify-content:space-between; align-items:center; margin-bottom:24px; }
    .page-title { font-size:1.5rem; font-weight:700; color:#1e293b; margin:0; }
    .page-sub { font-size:0.875rem; color:#64748b; margin-top:4px; }

    .stats-grid { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; margin-bottom:24px; }
    @media (max-width:1200px) { .stats-grid { grid-template-columns:repeat(2,1fr); } }
    @media (max-width:576px) { .stats-grid { grid-template-columns:1fr; } }
    .stat-card { background:#fff; border-radius:12px; padding:20px; display:flex; align-items:center; gap:16px; box-shadow:0 1px 3px rgba(0,0,0,.05); border:1px solid #f1f5f9; }
    .stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.25rem; flex-shrink:0; }
    .stat-icon.blue { background:#eff6ff; color:#3b82f6; }
    .stat-icon.purple { background:#f5f3ff; color:#8b5cf6; }
    .stat-icon.green { background:#f0fdf4; color:#22c55e; }
    .stat-icon.orange { background:#fff7ed; color:#f97316; }
    .stat-info p { margin:0; font-size:.875rem; color:#64748b; font-weight:500; }
    .stat-info h3 { margin:4px 0 0; font-size:1.25rem; font-weight:700; color:#1e293b; }
    .stat-info .sub-text { font-size:.75rem; color:#94a3b8; margin-top:2px; }

    .filter-bar { background:#fff; padding:16px; border-radius:12px; display:flex; flex-wrap:wrap; gap:12px; align-items:center; margin-bottom:24px; box-shadow:0 1px 3px rgba(0,0,0,.05); border:1px solid #f1f5f9; }
    .filter-select { flex:1 1 160px; min-width:150px; position:relative; }
    .filter-select label { position:absolute; top:-8px; left:12px; background:#fff; padding:0 4px; font-size:.7rem; color:#64748b; font-weight:500; }
    .filter-select select, .filter-select input { width:100%; padding:10px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:.875rem; color:#1e293b; outline:none; background:#fff; }
    .btn-filter-solid { flex:0 1 110px; justify-content:center; background:#6366f1; color:#fff; border:none; padding:10px 20px; border-radius:8px; font-size:.875rem; font-weight:500; display:flex; align-items:center; gap:8px; cursor:pointer; }
    .btn-reset { flex:0 1 100px; justify-content:center; background:#fff; color:#64748b; border:1px solid #e2e8f0; padding:10px 20px; border-radius:8px; font-size:.875rem; font-weight:500; display:flex; align-items:center; gap:8px; text-decoration:none; }

    .table-container { background:#fff; border-radius:12px; box-shadow:0 1px 3px rgba(0,0,0,.05); border:1px solid #f1f5f9; overflow-x:auto; }
    .table-custom { width:100%; border-collapse:collapse; }
    .table-custom th { background:#f8fafc; padding:12px 16px; font-size:.75rem; font-weight:600; color:#475569; text-transform:uppercase; letter-spacing:.5px; text-align:left; border-bottom:1px solid #e2e8f0; white-space:nowrap; }
    .table-custom td { padding:16px; font-size:.875rem; color:#475569; border-bottom:1px solid #f1f5f9; vertical-align:middle; white-space:nowrap; }
    .table-custom tbody tr:hover { background:#f8fafc; }
    .table-custom .num { text-align:right; }
    .table-custom tfoot td { background:#f8fafc; font-weight:700; color:#1e293b; border-top:2px solid #e2e8f0; border-bottom:none; }

    .partner-details { display:flex; align-items:center; gap:12px; }
    .partner-avatar { width:38px; height:38px; border-radius:50%; background:#eef2ff; color:#6366f1; display:flex; align-items:center; justify-content:center; font-weight:700; text-transform:uppercase; flex-shrink:0; }
    .partner-name { font-weight:600; color:#1e293b; }
    .partner-sub { font-size:.75rem; color:#94a3b8; }

    .amt-payable { font-weight:700; color:#1e293b; }
    .amt-settled { font-weight:600; color:#16a34a; }
    .amt-pending { font-weight:700; color:#d97706; }
    .progress-wrap { min-width:110px; }
    .progress-track { height:6px; background:#e2e8f0; border-radius:999px; overflow:hidden; }
    .progress-fill { height:100%; background:#22c55e; border-radius:999px; }
    .progress-label { font-size:.7rem; color:#94a3b8; margin-top:4px; }
    .link-view { color:#6366f1; text-decoration:none; font-weight:600; font-size:.8rem; }
    .empty-row { text-align:center; padding:48px 16px !important; color:#94a3b8; }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Vendor Payouts</h1>
        <div class="page-sub">Restaurant-wise totals from settlements. Pending = Payable − Settled.</div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <p>Gross Customer Payments</p>
            <h3>₹{{ number_format($totals['gross'], 2) }}</h3>
            <div class="sub-text">Across {{ $rows->count() }} restaurant(s)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-store"></i></div>
        <div class="stat-info">
            <p>Net Vendor Payable</p>
            <h3>₹{{ number_format($totals['payable'], 2) }}</h3>
            <div class="sub-text">After commission, GST &amp; fees</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-circle-check"></i></div>
        <div class="stat-info">
            <p>Settled</p>
            <h3>₹{{ number_format($totals['settled'], 2) }}</h3>
            <div class="sub-text">Already paid to vendors</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-info">
            <p>Pending</p>
            <h3>₹{{ number_format($totals['pending'], 2) }}</h3>
            <div class="sub-text">Payable − Settled</div>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.finance.vendor-payouts.index') }}" class="filter-bar">
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
    <a href="{{ route('admin.finance.vendor-payouts.index') }}" class="btn-reset"><i class="fa-solid fa-rotate-right"></i> Reset</a>
</form>

<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Restaurant</th>
                <th class="num">Gross Payments</th>
                <th class="num">Commission</th>
                <th class="num">GST</th>
                <th class="num">Platform Fees</th>
                <th class="num">Net Payable</th>
                <th class="num">Settled</th>
                <th class="num">Pending</th>
                <th>Progress</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $r)
            @php $pct = $r->payable > 0 ? round(($r->settled / $r->payable) * 100) : 0; @endphp
            <tr>
                <td>
                    <div class="partner-details">
                        <div class="partner-avatar">{{ substr($r->name, 0, 1) }}</div>
                        <div>
                            <div class="partner-name">{{ $r->name }}</div>
                            <div class="partner-sub">{{ $r->settlement_count }} settlement(s)</div>
                        </div>
                    </div>
                </td>
                <td class="num">₹{{ number_format($r->gross, 2) }}</td>
                <td class="num">₹{{ number_format($r->commission, 2) }}</td>
                <td class="num">₹{{ number_format($r->gst, 2) }}</td>
                <td class="num">₹{{ number_format($r->platform_fees, 2) }}</td>
                <td class="num amt-payable">₹{{ number_format($r->payable, 2) }}</td>
                <td class="num amt-settled">₹{{ number_format($r->settled, 2) }}</td>
                <td class="num amt-pending">₹{{ number_format($r->pending, 2) }}</td>
                <td>
                    <div class="progress-wrap">
                        <div class="progress-track"><div class="progress-fill" style="width: {{ $pct }}%;"></div></div>
                        <div class="progress-label">{{ $pct }}% settled</div>
                    </div>
                </td>
                <td>
                    <a class="link-view" href="{{ route('admin.finance.settlements.index', ['restaurant_id' => $r->restaurant_id]) }}">View <i class="fa-solid fa-arrow-right"></i></a>
                </td>
            </tr>
            @empty
            <tr><td colspan="10" class="empty-row">No settlement records found.</td></tr>
            @endforelse
        </tbody>
        @if($rows->count())
        <tfoot>
            <tr>
                <td>Total</td>
                <td class="num">₹{{ number_format($totals['gross'], 2) }}</td>
                <td class="num">₹{{ number_format($totals['commission'], 2) }}</td>
                <td class="num">₹{{ number_format($totals['gst'], 2) }}</td>
                <td class="num">₹{{ number_format($totals['platform_fees'], 2) }}</td>
                <td class="num">₹{{ number_format($totals['payable'], 2) }}</td>
                <td class="num">₹{{ number_format($totals['settled'], 2) }}</td>
                <td class="num">₹{{ number_format($totals['pending'], 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection
