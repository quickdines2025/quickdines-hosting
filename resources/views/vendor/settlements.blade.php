@extends('layouts.vendor')

@section('title', 'Settlements')

@push('styles')
<style>
    .st-head { margin-bottom: 24px; }
    .st-head h2 { font-size: 24px; font-weight: 800; color: #0f172a; margin: 0; }
    .st-head p { font-size: 13px; color: #64748b; margin-top: 4px; }

    .st-stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 24px; }
    .st-stat { background: #fff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 20px; position: relative; overflow: hidden; }
    .st-stat::before { content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #cbd5e1; }
    .st-stat.blue::before { background: #2563eb; }
    .st-stat.green::before { background: #16a34a; }
    .st-stat.amber::before { background: #d97706; }
    .st-stat small { display: block; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .5px; }
    .st-stat h3 { font-size: 26px; font-weight: 800; color: #0f172a; margin-top: 8px; }
    .st-stat span { display: block; font-size: 12px; color: #94a3b8; margin-top: 4px; }

    .st-filters { display: flex; flex-wrap: wrap; gap: 12px; align-items: flex-end; }
    .st-filters .f { flex: 1 1 140px; min-width: 130px; }
    .st-filters label { display: block; font-size: 12px; font-weight: 600; color: #64748b; margin-bottom: 6px; }
    .st-filters input, .st-filters select { margin-bottom: 0; }
    .st-filters .btns { display: flex; gap: 8px; }
    .btn-ghost { background: #f1f5f9; color: #334155; }

    .st-table td, .st-table th { padding: 14px 12px; }
    .st-table tbody tr:hover { background: #f8fafc; }
    .st-table tbody tr:last-child td { border-bottom: none; }
    .st-sub { font-size: 12px; color: #94a3b8; font-weight: 500; margin-top: 3px; }
    .st-num { text-align: right; }
    .st-receive { font-weight: 800; font-size: 16px; color: #0f172a; }
    .st-pill { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; }
    .st-pill.settled { background: #dcfce7; color: #15803d; }
    .st-pill.pending { background: #fef3c7; color: #b45309; }
    .st-pager { display: flex; justify-content: space-between; align-items: center; padding-top: 16px; font-size: 13px; color: #64748b; }
    .st-pager a, .st-pager span.dis { padding: 8px 14px; border-radius: 6px; border: 1px solid #e2e8f0; text-decoration: none; color: #334155; font-weight: 600; background: #fff; }
    .st-pager span.dis { color: #cbd5e1; }

    @media (max-width: 1100px) { .st-stats { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 640px) { .st-stats { grid-template-columns: 1fr; } .st-table-wrap { overflow-x: auto; } .st-table { min-width: 720px; } }
</style>
@endpush

@section('content')
<div class="st-head">
    <h2>Settlements</h2>
    <p>What QuickDines owes you for each order, and what has already been paid to you.</p>
</div>

<div class="st-stats">
    <div class="st-stat blue">
        <small>Customer Payments</small>
        <h3>₹{{ number_format($stats['gross'], 2) }}</h3>
        <span>Total paid by customers</span>
    </div>
    <div class="st-stat">
        <small>Total Payable To You</small>
        <h3>₹{{ number_format($stats['payable'], 2) }}</h3>
        <span>After commission, GST &amp; fees</span>
    </div>
    <div class="st-stat green">
        <small>Paid To You</small>
        <h3 style="color:#15803d;">₹{{ number_format($stats['received'], 2) }}</h3>
        <span>Settled by QuickDines</span>
    </div>
    <div class="st-stat amber">
        <small>Pending</small>
        <h3 style="color:#b45309;">₹{{ number_format($stats['pending'], 2) }}</h3>
        <span>Payable − Paid</span>
    </div>
</div>

<div class="card">
    <form method="GET" action="{{ route('vendor.settlements') }}" class="st-filters">
        <div class="f">
            <label>Order ID</label>
            <input type="text" name="order_id" value="{{ request('order_id') }}" placeholder="e.g. 105">
        </div>
        <div class="f">
            <label>Status</label>
            <select name="status">
                <option value="">All</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="settled" {{ request('status') == 'settled' ? 'selected' : '' }}>Settled</option>
            </select>
        </div>
        <div class="f">
            <label>From</label>
            <input type="date" name="date_from" value="{{ request('date_from') }}">
        </div>
        <div class="f">
            <label>To</label>
            <input type="date" name="date_to" value="{{ request('date_to') }}">
        </div>
        <div class="btns">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter"></i> Filter</button>
            <a href="{{ route('vendor.settlements') }}" class="btn btn-ghost">Reset</a>
        </div>
    </form>
</div>

<div class="card">
    <div class="st-table-wrap">
        <table class="st-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th class="st-num">Customer Paid</th>
                    <th class="st-num">Deductions</th>
                    <th class="st-num">You Receive</th>
                    <th>Status</th>
                    <th>Paid On</th>
                </tr>
            </thead>
            <tbody>
                @forelse($settlements as $s)
                @php
                    $deductions = $s->commission_amount + $s->gst_amount + $s->platform_fee;
                    $fmt = function ($v) { return rtrim(rtrim(number_format($v, 2), '0'), '.'); };
                @endphp
                <tr>
                    <td>
                        <div style="font-weight:700;">#{{ $s->order_id }}</div>
                        <div class="st-sub">SETT{{ str_pad($s->id, 6, '0', STR_PAD_LEFT) }} · {{ $s->created_at->format('d M, Y') }}</div>
                    </td>
                    <td class="st-num">₹{{ number_format($s->amount, 2) }}</td>
                    <td class="st-num">
                        <div>− ₹{{ number_format($deductions, 2) }}</div>
                        <div class="st-sub">
                            Commission ₹{{ number_format($s->commission_amount, 2) }} ({{ $fmt($s->commission_percentage) }}%)<br>
                            GST ₹{{ number_format($s->gst_amount, 2) }} ({{ $fmt($s->gst_percentage) }}%) · Fee ₹{{ number_format($s->platform_fee, 2) }}
                        </div>
                    </td>
                    <td class="st-num st-receive">₹{{ number_format($s->net_amount, 2) }}</td>
                    <td>
                        @if($s->status === 'completed')
                            <span class="st-pill settled"><i class="fa-solid fa-circle-check"></i> Settled</span>
                        @else
                            <span class="st-pill pending"><i class="fa-regular fa-clock"></i> {{ ucfirst($s->status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($s->settled_at)
                            <div style="font-weight:600;">{{ $s->settled_at->format('d M, Y') }}</div>
                            <div class="st-sub">UTR: {{ $s->reference_id ?: '—' }}</div>
                        @else
                            <span style="color:#94a3b8;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:48px 0; color:#94a3b8;">
                        <i class="fa-solid fa-hand-holding-dollar" style="font-size:36px; opacity:.5; display:block; margin-bottom:12px;"></i>
                        No settlements found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($settlements->hasPages())
    <div class="st-pager">
        <div>Showing {{ $settlements->firstItem() }}–{{ $settlements->lastItem() }} of {{ $settlements->total() }}</div>
        <div style="display:flex; gap:8px;">
            @if($settlements->onFirstPage())
                <span class="dis">Previous</span>
            @else
                <a href="{{ $settlements->previousPageUrl() }}">Previous</a>
            @endif
            @if($settlements->hasMorePages())
                <a href="{{ $settlements->nextPageUrl() }}">Next</a>
            @else
                <span class="dis">Next</span>
            @endif
        </div>
    </div>
    @endif
</div>
@endsection
