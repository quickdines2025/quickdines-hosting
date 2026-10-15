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

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card {
            padding: 12px;
            gap: 10px;
            flex-direction: column;
            text-align: center;
        }
        .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }
        .stat-info h3 { font-size: 1.2rem; margin-top: 2px; }
        .stat-info p { font-size: 0.75rem; }
    }
    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
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
    }
    .stat-icon.blue { background: #eff6ff; color: #3b82f6; }
    .stat-icon.green { background: #f0fdf4; color: #22c55e; }
    .stat-icon.orange { background: #fff7ed; color: #f97316; }
    .stat-icon.red { background: #fef2f2; color: #ef4444; }

    .stat-info p {
        margin: 0;
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }
    .stat-info h3 {
        margin: 4px 0 0 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }

    /* Filter Bar */
    .filter-bar {
        background: #fff;
        padding: 16px;
        border-radius: 12px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    .filter-select {
        flex: 1 1 140px; min-width: 140px;
    }
    .filter-select select, .filter-date {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: white;
    }
    .btn-filter-solid, .btn-reset {
        flex: 1 1 100px; justify-content: center; display: flex; align-items: center; gap: 8px;
    }
    .btn-filter-solid {
        background: #6366f1;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
    }
    .btn-reset {
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Table Styles */
    .payouts-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        overflow: hidden;
    }
    .table {
        margin: 0;
        width: 100%;
        border-collapse: collapse;
    }
    .table th {
        background: #f8fafc;
        padding: 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        text-transform: uppercase;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid #e2e8f0;
        color: #334155;
        font-size: 0.875rem;
    }
    .table tr:last-child td {
        border-bottom: none;
    }

    .badge-soft {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .badge-soft-blue { background: #e0e7ff; color: #4f46e5; }
    .badge-soft-green { background: #dcfce7; color: #16a34a; }

    .partner-col {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .partner-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #1e293b;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        font-weight: 600;
    }
    .partner-info {
        display: flex;
        flex-direction: column;
    }
    .partner-info strong {
        color: #1e293b;
        font-weight: 600;
    }
    .partner-info span {
        font-size: 0.75rem;
        color: #64748b;
    }

    .btn-view {
        background: #eff6ff;
        color: #6366f1;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Payout History</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Payout History
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <p>Total Payouts</p>
            <h3>₹{{ number_format($stats['total_payouts'], 2) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-money-bill-trend-up"></i></div>
        <div class="stat-info">
            <p>This Week</p>
            <h3>₹{{ number_format($stats['this_week'], 2) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-regular fa-calendar-check"></i></div>
        <div class="stat-info">
            <p>This Month</p>
            <h3>₹{{ number_format($stats['this_month'], 2) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="stat-info">
            <p>Pending Payouts</p>
            <h3>₹{{ number_format($stats['pending_payouts'], 2) }}</h3>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.finance.payouts.index') }}" class="filter-bar">
    <div class="filter-select">
        <select name="user_type">
            <option>All Partners</option>
            <option value="Restaurant" {{ request('user_type') == 'Restaurant' ? 'selected' : '' }}>Restaurants</option>
            <option value="Driver" {{ request('user_type') == 'Driver' ? 'selected' : '' }}>Drivers</option>
        </select>
    </div>
    <div class="filter-select">
        <select name="status">
            <option>All Status</option>
            <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Paid</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Failed" {{ request('status') == 'Failed' ? 'selected' : '' }}>Failed</option>
        </select>
    </div>
    <div class="filter-select">
        <input type="date" name="date_from" class="filter-date" value="{{ request('date_from') }}" title="From date">
    </div>
    <div class="filter-select">
        <input type="date" name="date_to" class="filter-date" value="{{ request('date_to') }}" title="To date">
    </div>
    <button type="submit" class="btn-filter-solid"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route('admin.finance.payouts.index') }}" class="btn-reset" style="text-decoration:none;">â†» Reset</a>
    <a href="{{ route('admin.finance.payouts.export', request()->all()) }}" class="btn-reset" style="text-decoration:none; margin-left: auto;"><i class="fa-solid fa-download"></i> Export</a>
</form>

<div class="payouts-card">
    <table class="table">
        <thead>
            <tr>
                <th>Payout ID</th>
                <th>Partner</th>
                <th>Type</th>
                <th>Payout Method</th>
                <th>Amount (₹)</th>
                <th>Status</th>
                <th>Payout Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payouts as $payout)
            @php
                $partnerName = 'Unknown Partner';
                if ($payout->user_type == 'restaurant' || $payout->user_type == 'vendor') {
                    $partner = \App\Models\Restaurant::find($payout->user_id);
                    $partnerName = $partner ? ($partner->name ?? $partner->restaurant_name ?? 'Unknown Restaurant') : 'Unknown Restaurant';
                } elseif ($payout->user_type == 'driver') {
                    $partner = \App\Models\User::find($payout->user_id);
                    $partnerName = $partner ? $partner->name : 'Unknown Driver';
                }
            @endphp
            <tr>
                <td><strong>PYO100{{ $payout->id }}</strong></td>
                <td>
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div class="partner-avatar" style="width: 32px; height: 32px; border-radius: 50%; background: #{{ substr(md5($partnerName), 0, 6) }}; color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; font-size: 0.75rem;">
                            {{ substr($partnerName, 0, 2) }}
                        </div>
                        <div>
                            <div style="font-weight: 600; color: #1e293b; font-size: 0.875rem;">{{ $partnerName }}</div>
                            <div style="font-size: 0.75rem; color: #64748b;">{{ ucfirst($payout->user_type) }}</div>
                        </div>
                    </div>
                </td>
                <td><span class="badge badge-{{ $payout->user_type == 'restaurant' ? 'restaurant' : 'driver' }}">{{ ucfirst($payout->user_type) }} Payout</span></td>
                <td>
                    <div style="font-weight: 500;">{{ $payout->payment_method ?? 'System' }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ $payout->reference_id ?? '-' }}</div>
                </td>
                <td><strong>₹{{ number_format($payout->amount, 2) }}</strong></td>
                <td>
                    @if($payout->status == 'completed')
                        <span class="status-pill status-paid" style="background:#dcfce7; color:#16a34a; padding:6px 12px; border-radius:6px; font-size:0.75rem; font-weight:600;">Paid</span>
                    @elseif($payout->status == 'pending')
                        <span class="status-pill" style="background:#fff7ed; color:#1d4ed8; padding:6px 12px; border-radius:6px; font-size:0.75rem; font-weight:600;">Pending</span>
                    @else
                        <span class="status-pill" style="background:#fef2f2; color:#dc2626; padding:6px 12px; border-radius:6px; font-size:0.75rem; font-weight:600;">{{ ucfirst($payout->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $payout->created_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ $payout->created_at->format('h:i A') }}</div>
                </td>
                <td>
                    <button class="btn-view btn-view-details"
                        data-id="PYO100{{ $payout->id }}"
                        data-amount="{{ number_format($payout->amount, 2) }}"
                        data-status="{{ ucfirst($payout->status) }}"
                        data-statuslower="{{ $payout->status == 'completed' ? 'paid' : strtolower($payout->status) }}"
                        data-partner="{{ $partnerName }} ({{ ucfirst($payout->user_type) }})"
                        data-method="{{ $payout->payment_method ?? 'System' }}"
                        data-utr="{{ $payout->reference_id ?? '-' }}"
                        data-date="{{ $payout->created_at->format('M d, Y h:i A') }}"
                    >
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px; color: #64748b;">No payouts found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding: 16px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
        <span style="font-size: 0.875rem; color: #64748b;">Showing {{ $payouts->firstItem() ?? 0 }} to {{ $payouts->lastItem() ?? 0 }} of {{ $payouts->total() }} payouts</span>
        {{ $payouts->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border:none; border-radius:16px; box-shadow:0 10px 25px rgba(0,0,0,0.1);">
            <div class="modal-header" style="background:#f8fafc; border-bottom:1px solid #e2e8f0; border-radius:16px 16px 0 0; padding:20px 24px;">
                <h5 class="modal-title" style="font-weight:700; color:#1e293b;">Payout Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div style="text-align:center; margin-bottom:24px;">
                    <div style="font-size:2rem; font-weight:800; color:#1e293b;" id="modal-amount">₹0.00</div>
                    <span id="modal-status" class="status-pill" style="margin-top:8px;">-</span>
                </div>

                <div style="background:#f8fafc; border-radius:12px; padding:16px; margin-bottom:24px; border:1px solid #e2e8f0;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Payout ID</span>
                        <strong id="modal-id" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Partner</span>
                        <strong id="modal-partner" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Payment Method</span>
                        <strong id="modal-method" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Bank UTR / Ref ID</span>
                        <strong id="modal-utr" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span style="color:#64748b; font-size:0.875rem;">Date & Time</span>
                        <strong id="modal-date" style="color:#1e293b;">-</strong>
                    </div>
                </div>

                <button type="button" class="btn w-100" data-bs-dismiss="modal" style="background:#f1f5f9; color:#475569; font-weight:600; border-radius:8px; padding:12px;">Close Receipt</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const viewButtons = document.querySelectorAll('.btn-view-details');

        viewButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Populate Modal Data
                document.getElementById('modal-amount').innerText = '₹' + btn.getAttribute('data-amount');
                document.getElementById('modal-id').innerText = btn.getAttribute('data-id');

                let statusBadge = document.getElementById('modal-status');
                statusBadge.innerText = btn.getAttribute('data-status');

                // Reset classes
                statusBadge.className = 'status-pill';

                let statusLower = btn.getAttribute('data-statuslower');
                if (statusLower === 'paid' || statusLower === 'completed') {
                    statusBadge.classList.add('status-paid');
                } else if (statusLower === 'pending') {
                    statusBadge.style.background = '#fff7ed';
                    statusBadge.style.color = '#1d4ed8';
                } else {
                    statusBadge.style.background = '#fef2f2';
                    statusBadge.style.color = '#dc2626';
                }

                document.getElementById('modal-partner').innerText = btn.getAttribute('data-partner');
                document.getElementById('modal-method').innerText = btn.getAttribute('data-method');
                document.getElementById('modal-utr').innerText = btn.getAttribute('data-utr');
                document.getElementById('modal-date').innerText = btn.getAttribute('data-date');

                var myModal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
                myModal.show();
            });
        });
    });
</script>
@endsection
