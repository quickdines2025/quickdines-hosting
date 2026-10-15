@extends('layouts.admin')


@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
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
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 768px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .stat-card {
            padding: 12px;
            gap: 10px;
            flex-direction: column;
            text-align: center;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.1rem;
        }

        .stat-info h3 {
            font-size: 1.2rem;
            margin-top: 2px;
        }

        .stat-info p {
            font-size: 0.75rem;
        }
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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

    .stat-icon.blue {
        background: #eff6ff;
        color: #3b82f6;
    }

    .stat-icon.green {
        background: #f0fdf4;
        color: #22c55e;
    }

    .stat-icon.orange {
        background: #fff7ed;
        color: #f97316;
    }

    .stat-icon.red {
        background: #fef2f2;
        color: #ef4444;
    }

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
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .filter-search {
        flex: 1 1 200px;
        position: relative;
    }

    .filter-search i {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #94a3b8;
    }

    .filter-search input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        outline: none;
    }

    .filter-select {
        flex: 1 1 140px;
        min-width: 140px;
    }

    .filter-select select,
    .filter-date {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: white;
    }

    .btn-filter-solid,
    .btn-reset,
    .btn-export {
        flex: 1 1 100px;
        justify-content: center;
        display: flex;
        align-items: center;
        gap: 8px;
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

    .btn-export {
        background: white;
        color: #1e293b;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
    }

    /* Table Styles */
    .transactions-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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

    .badge-soft-green {
        background: #dcfce7;
        color: #16a34a;
    }

    .badge-soft-blue {
        background: #e0e7ff;
        color: #4f46e5;
    }

    .badge-soft-yellow {
        background: #fef08a;
        color: #ca8a04;
    }

    .badge-soft-red {
        background: #fee2e2;
        color: #dc2626;
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

    .amount-positive {
        color: #16a34a;
    }

    .amount-negative {
        color: #dc2626;
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
        <h1 class="page-title">Transactions</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Customer Payments
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <p>Total Transactions</p>
            <h3>{{ number_format($stats['total_count']) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-money-bill-wave"></i></div>
        <div class="stat-info">
            <p>Customer Payments</p>
            <h3>₹{{ number_format($stats['total_amount'], 2) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-regular fa-circle-check"></i></div>
        <div class="stat-info">
            <p>Successful</p>
            <h3>{{ number_format($stats['successful']) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div class="stat-info">
            <p>Refunded</p>
            <h3>{{ number_format($stats['refunded']) }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="stat-info">
            <p>Failed</p>
            <h3>{{ number_format($stats['failed']) }}</h3>
        </div>
    </div>
</div>

<form method="GET" action="{{ route('admin.finance.transactions.index') }}" class="filter-bar" style="flex-wrap:wrap;">
    <div class="filter-search">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Transaction ID (e.g. TRX0012)">
    </div>
    <div class="filter-search">
        <i class="fa-solid fa-hashtag"></i>
        <input type="text" name="order_id" value="{{ request('order_id') }}" placeholder="Order ID">
    </div>
    <div class="filter-search">
        <i class="fa-solid fa-user"></i>
        <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Customer name / mobile">
    </div>
    <div class="filter-select">
        <select name="restaurant_id">
            <option value="">All Restaurants</option>
            @foreach($restaurants as $restaurant)
            <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="status">
            <option>All Status</option>
            <option value="completed" {{ in_array(request('status'), ['completed','success']) ? 'selected' : '' }}>Paid / Completed</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
    </div>
    <div>
        <input type="date" name="date_from" class="filter-date" value="{{ request('date_from') }}" title="From date">
    </div>
    <div>
        <input type="date" name="date_to" class="filter-date" value="{{ request('date_to') }}" title="To date">
    </div>
    <button type="submit" class="btn-filter-solid"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route('admin.finance.transactions.index') }}" class="btn-reset" style="text-decoration:none;"><i class="fa-solid fa-rotate-right"></i> Reset</a>
    <button type="submit" formaction="{{ route('admin.finance.transactions.export') }}" class="btn-export"><i class="fa-solid fa-download"></i> Export</button>
</form>

<div class="transactions-card">
    <table class="table">
        <thead>
            <tr>
                <th>Transaction ID</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Restaurant</th>
                <th>Customer Paid (₹)</th>
                <th>Payment Method</th>
                <th>Razorpay Payment ID</th>
                <th>Payment Status</th>
                <th>Date & Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $trx)
            @php
                $order = $trx->order;
                $customerName = optional($trx->customer)->name ?? optional($order)->passenger_name ?? 'Unknown Customer';
                $restaurantName = optional(optional($order)->restaurant)->restaurant_name ?? '-';
                $rzpPaymentId = $trx->reference_id ?: (optional($order)->razorpay_payment_id ?: '-');
                $statusClass = 'badge-soft-green';
                if (in_array(strtolower($trx->status), ['failed', 'rejected'])) $statusClass = 'badge-soft-red';
                if (in_array(strtolower($trx->status), ['refunded', 'pending'])) $statusClass = 'badge-soft-yellow';
                $statusLabel = in_array(strtolower($trx->status), ['completed', 'success']) ? 'Paid' : ucfirst($trx->status);
            @endphp
            <tr>
                <td>{{ $trx->tx_id }}</td>
                <td>{{ $trx->order_id ?? '-' }}</td>
                <td><strong>{{ $customerName }}</strong></td>
                <td>{{ $restaurantName }}</td>
                <td class="amount-positive">₹{{ number_format($trx->amount, 2) }}</td>
                <td>{{ strtoupper($trx->payment_method ?? '-') }}</td>
                <td style="font-size:0.8rem;">{{ $rzpPaymentId }}</td>
                <td><span class="badge-soft {{ $statusClass }}">{{ $statusLabel }}</span></td>
                <td>
                    <div class="partner-info">
                        <strong>{{ $trx->created_at->format('M d, Y') }}</strong>
                        <span>{{ $trx->created_at->format('h:i A') }}</span>
                    </div>
                </td>
                <td>
                    <button type="button" class="btn-view" onclick="openDetailsModal(this)"
                        data-tx-id="{{ $trx->tx_id }}"
                        data-order-id="{{ $trx->order_id ?? '-' }}"
                        data-type="{{ $restaurantName }}"
                        data-amount="₹{{ number_format($trx->amount, 2) }}"
                        data-status="{{ $statusLabel }}"
                        data-partner="{{ $customerName }}"
                        data-method="{{ strtoupper($trx->payment_method ?? '-') }}"
                        data-date="{{ $trx->created_at->format('M d, Y h:i A') }}"
                        data-utr="{{ $rzpPaymentId }}">
                        <i class="fa-regular fa-eye"></i>
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" style="text-align: center; padding: 40px; color:#64748b;">No customer payments found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="padding:16px; border-top:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
        <span style="font-size:0.875rem; color:#64748b;">Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions</span>
        <div style="display:flex; gap:8px;">
            {{ $transactions->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

<!-- Transaction Details Modal -->
<div class="modal fade" id="transactionDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px; border:none; box-shadow:0 10px 40px rgba(0,0,0,0.1);">
            <div class="modal-header" style="border-bottom:1px solid #f1f5f9; padding:20px 24px;">
                <h5 class="modal-title" style="font-weight:700; color:#1e293b;">Transaction Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding:24px;">
                <div style="background:#f8fafc; border-radius:12px; padding:20px; margin-bottom:20px;">
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Transaction ID</span>
                        <strong id="modal-tx-id" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Order ID</span>
                        <strong id="modal-order-id" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Customer</span>
                        <strong id="modal-partner" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Restaurant</span>
                        <strong id="modal-type" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Customer Paid</span>
                        <strong id="modal-amount" style="color:#1e293b; font-size:1.1rem;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Status</span>
                        <strong id="modal-status" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Payment Method</span>
                        <strong id="modal-method" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between; margin-bottom:16px;">
                        <span style="color:#64748b; font-size:0.875rem;">Razorpay Payment ID</span>
                        <strong id="modal-utr" style="color:#1e293b;">-</strong>
                    </div>
                    <div style="display:flex; justify-content:space-between;">
                        <span style="color:#64748b; font-size:0.875rem;">Date & Time</span>
                        <strong id="modal-date" style="color:#1e293b;">-</strong>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="border-top:1px solid #f1f5f9; padding:16px 24px;">
                <button type="button" style="background:white; color:#64748b; border:1px solid #e2e8f0; padding:10px 20px; border-radius:8px; font-weight:500;" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openDetailsModal(btn) {
        document.getElementById('modal-tx-id').innerText = btn.getAttribute('data-tx-id');
        document.getElementById('modal-order-id').innerText = btn.getAttribute('data-order-id');
        document.getElementById('modal-type').innerText = btn.getAttribute('data-type');
        document.getElementById('modal-amount').innerText = btn.getAttribute('data-amount');
        document.getElementById('modal-status').innerText = btn.getAttribute('data-status');
        document.getElementById('modal-partner').innerText = btn.getAttribute('data-partner');
        document.getElementById('modal-method').innerText = btn.getAttribute('data-method');
        document.getElementById('modal-utr').innerText = btn.getAttribute('data-utr');
        document.getElementById('modal-date').innerText = btn.getAttribute('data-date');

        var myModal = new bootstrap.Modal(document.getElementById('transactionDetailsModal'));
        myModal.show();
    }
</script>
@endsection
