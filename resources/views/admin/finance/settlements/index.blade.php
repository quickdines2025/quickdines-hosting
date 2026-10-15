@extends('layouts.admin')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
<style>
    .flatpickr-calendar {
        font-family: 'Inter', sans-serif !important;
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
        border: none !important;
    }

    .flatpickr-day.selected,
    .flatpickr-day.startRange,
    .flatpickr-day.endRange,
    .flatpickr-day.selected.inRange,
    .flatpickr-day.startRange.inRange,
    .flatpickr-day.endRange.inRange,
    .flatpickr-day.selected:focus,
    .flatpickr-day.startRange:focus,
    .flatpickr-day.endRange:focus,
    .flatpickr-day.selected:hover,
    .flatpickr-day.startRange:hover,
    .flatpickr-day.endRange:hover {
        background: #6366f1 !important;
        border-color: #6366f1 !important;
    }

    .flatpickr-day.inRange,
    .flatpickr-day:hover {
        background: #e0e7ff !important;
        border-color: #e0e7ff !important;
        color: #1e293b !important;
    }


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

    .breadcrumb a:hover {
        color: #6366f1;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
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

    .stat-icon.purple {
        background: #f5f3ff;
        color: #8b5cf6;
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

    .stat-info .sub-text {
        font-size: 0.75rem;
        color: #94a3b8;
        margin-top: 2px;
    }

    /* Two Column Layout */
    .settlements-layout {
        display: flex;
        gap: 24px;
    }

    .settlements-main {
        flex: 1;
        min-width: 0;
    }

    .settlements-sidebar {
        width: 320px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        gap: 24px;
    }

    @media (max-width: 992px) {
        .settlements-layout {
            flex-direction: column;
        }

        .settlements-sidebar {
            width: 100%;
        }
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

    .filter-select {
        flex: 1 1 140px;
        min-width: 140px;
        position: relative;
    }

    .filter-select label {
        position: absolute;
        top: -8px;
        left: 12px;
        background: #fff;
        padding: 0 4px;
        font-size: 0.7rem;
        color: #64748b;
        font-weight: 500;
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
    }

    .btn-filter-solid {
        flex: 1 1 100px;
        justify-content: center;
        background: #6366f1;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-reset {
        flex: 1 1 100px;
        justify-content: center;
        background: white;
        color: #64748b;
        border: 1px solid #e2e8f0;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Tabs */
    .nav-tabs-custom {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 0;
        padding: 0 16px;
        background: #fff;
        border-radius: 12px 12px 0 0;
    }

    .nav-tabs-custom a {
        padding: 16px 0;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        font-size: 0.875rem;
        position: relative;
    }

    .nav-tabs-custom a.active {
        color: #6366f1;
    }

    .nav-tabs-custom a.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        right: 0;
        height: 2px;
        background: #6366f1;
    }

    /* Table */
    .table-container {
        background: #fff;
        border-radius: 0 0 12px 12px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
        border-top: none;
        overflow-x: auto;
    }

    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }

    .table-custom th {
        background: #f8fafc;
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: #475569;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
    }

    .table-custom td {
        padding: 16px;
        font-size: 0.875rem;
        border-bottom: 1px solid #f1f5f9;
        vertical-align: middle;
    }

    .partner-details {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .partner-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #1e293b;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .partner-name {
        font-weight: 600;
        color: #1e293b;
    }

    .partner-sub {
        font-size: 0.75rem;
        color: #64748b;
    }

    /* Badges */
    .badge-type {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-type.restaurant {
        background: #ecfdf5;
        color: #10b981;
    }

    .badge-type.driver {
        background: #eff6ff;
        color: #3b82f6;
    }

    .badge-status {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .badge-status.pending {
        background: #fff7ed;
        color: #f97316;
    }

    .badge-status.processing {
        background: #eff6ff;
        color: #3b82f6;
    }

    .badge-status.completed {
        background: #ecfdf5;
        color: #10b981;
    }

    .badge-status.failed {
        background: #fef2f2;
        color: #ef4444;
    }

    /* Actions */
    .action-group {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 1px solid #e2e8f0;
        background: #fff;
        color: #6366f1;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .btn-icon.gray {
        color: #64748b;
    }

    .btn-icon.red {
        color: #ef4444;
    }

    .btn-settle-sm {
        background: #6366f1;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 500;
        cursor: pointer;
    }

    .btn-settle-sm:disabled {
        background: #f1f5f9;
        color: #94a3b8;
        cursor: not-allowed;
    }

    /* Side Panel */
    .side-panel {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        border: 1px solid #f1f5f9;
    }

    .side-panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .side-panel-title {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }

    .side-panel-link {
        font-size: 0.75rem;
        color: #6366f1;
        text-decoration: none;
        font-weight: 500;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.875rem;
    }

    .detail-label {
        color: #64748b;
    }

    .detail-value {
        font-weight: 500;
        color: #1e293b;
        text-align: right;
    }

    .detail-value.green {
        color: #10b981;
        font-weight: 600;
        font-size: 1rem;
    }

    .btn-settle-lg {
        background: #6366f1;
        color: white;
        border: none;
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        font-weight: 500;
        margin-top: 10px;
    }

    .btn-outline-lg {
        background: white;
        color: #6366f1;
        border: 1px solid #e0e7ff;
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        font-weight: 500;
        margin-top: 10px;
    }

    .payout-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .payout-item:last-child {
        border-bottom: none;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Financial Settlements</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a> &gt; Financial Settlements
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-info">
            <p>Total Payable</p>
            <h3>₹{{ $stats['total_payable'] }}</h3>
            <div class="sub-text">Vendor payable (all settlements)</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
        <div class="stat-info">
            <p>Settled This Week</p>
            <h3>₹{{ $stats['settled_this_week'] }}</h3>
            <div class="sub-text">{{ $stats['settlements_this_week_count'] }} Settlements</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-regular fa-clock"></i></div>
        <div class="stat-info">
            <p>Pending (Payable − Settled)</p>
            <h3>₹{{ $stats['pending_settlements'] }}</h3>
            <div class="sub-text">{{ $stats['pending_count'] }} Pending</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        <div class="stat-info">
            <p>Total Settled</p>
            <h3>₹{{ $stats['total_settled'] }}</h3>
            <div class="sub-text">All time</div>
        </div>
    </div>
</div>

<form class="filter-bar" method="GET" action="{{ route('admin.finance.settlements.index') }}">



    <div class="filter-select">
        <label>Restaurant</label>
        <select name="restaurant_id">
            <option value="">All</option>
            @foreach($restaurants as $restaurant)
            <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <label>Status</label>
        <select name="status">
            <option value="All">All</option>
            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Settled" {{ in_array(request('status'), ['Settled','Completed']) ? 'selected' : '' }}>Settled</option>
        </select>
    </div>
    <div class="filter-select">
        <label>Order ID</label>
        <input type="text" name="order_id" value="{{ request('order_id') }}" placeholder="e.g. 105" class="filter-date" autocomplete="off">
    </div>
    <div class="filter-select">
        <label>Customer</label>
        <input type="text" name="customer" value="{{ request('customer') }}" placeholder="Name / mobile" class="filter-date" autocomplete="off">
    </div>

    <div class="filter-select">
        <label>Date Range</label>
        <input
            type="text"
            name="date"
            id="settlementDateRange"
            class="filter-date"
            value="{{ request('date') }}"
            placeholder="Select date range"
            autocomplete="off">
    </div>

    <button type="submit" class="btn-filter-solid"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route('admin.finance.settlements.index') }}" class="btn-reset" style="text-decoration:none;"><i class="fa-solid fa-rotate-left"></i> Reset</a>
</form>

<div class="settlements-layout">
    <!-- Main Table Area -->
    <div class="settlements-main">
        <div class="nav-tabs-custom">
            <a href="{{ route('admin.finance.settlements.index', array_merge(request()->all(), ['tab' => 'All Settlements'])) }}" class="{{ request('tab', 'All Settlements') == 'All Settlements' ? 'active' : '' }}">All Settlements</a>
            <a href="{{ route('admin.finance.settlements.index', array_merge(request()->all(), ['tab' => 'Payable'])) }}" class="{{ request('tab') == 'Payable' ? 'active' : '' }}">Payable</a>
            <a href="{{ route('admin.finance.settlements.index', array_merge(request()->all(), ['tab' => 'Pending'])) }}" class="{{ request('tab') == 'Pending' ? 'active' : '' }}">Pending</a>
            <a href="{{ route('admin.finance.settlements.index', array_merge(request()->all(), ['tab' => 'Processing'])) }}" class="{{ request('tab') == 'Processing' ? 'active' : '' }}">Processing</a>
            <a href="{{ route('admin.finance.settlements.index', array_merge(request()->all(), ['tab' => 'Completed'])) }}" class="{{ request('tab') == 'Completed' ? 'active' : '' }}">Settled</a>
            <a href="{{ route('admin.finance.settlements.index', array_merge(request()->all(), ['tab' => 'Failed'])) }}" class="{{ request('tab') == 'Failed' ? 'active' : '' }}">Failed</a>
        </div>

        <div class="table-container">
            <table class="table-custom">
                <thead>
                    <tr>
                        <th>Settlement ID</th>
                        <th>Order ID</th>
                        <th>Restaurant</th>
                        <th>Customer</th>
                        <th>Customer Paid (₹)</th>
                        <th>Commission (₹)</th>
                        <th>GST on Commission (₹)</th>
                        <th>Platform Fee (₹)</th>
                        <th>Vendor Payable (₹)</th>
                        <th>Status</th>
                        <th>Settlement Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($settlements) > 0)
                    @foreach($settlements as $row)
                    <tr>
                        <td style="font-weight:600; color:#475569;">{{ $row->id }}</td>
                        <td>#{{ $row->order_id }}</td>
                        <td>
                            <div class="partner-details">
                                <div class="partner-avatar">
                                    {{ substr($row->partner_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="partner-name">{{ $row->partner_name }}</div>
                                    <div class="partner-sub">{{ $row->partner_type }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $row->customer_name }}</td>
                        <td style="font-weight:500;">₹{{ $row->amount }}</td>
                        <td style="color:#64748b;">
                            ₹{{ $row->commission }}
                            <span style="font-size:12px; color:#94a3b8;">({{ $row->commission_percentage }}%)</span>
                        </td>
                        <td style="color:#64748b;">
                            ₹{{ $row->gst }}
                            <span style="font-size:12px; color:#94a3b8;">({{ $row->gst_percentage }}%)</span>
                        </td>
                        <td style="color:#64748b;">₹{{ $row->platform_fee }}</td>
                        <td style="font-weight:600; color:#475569;">₹{{ $row->net_amount }}</td>
                        <td>
                            <span class="badge-status {{ strtolower($row->status) }}">{{ $row->status_label }}</span>
                        </td>
                        <td style="color:#64748b;">
                            {{ $row->settled_date }}
                            <div style="font-size:11px; color:#94a3b8;">Created {{ $row->date }}</div>
                        </td>
                        <td>
                            <div class="action-group">
                                <button type="button" class="btn-icon btn-view-details"
                                    data-id="{{ $row->id }}"
                                    data-rawid="{{ $row->raw_id }}"
                                    data-partner="{{ $row->partner_name }}"
                                    data-initial="{{ substr($row->partner_name, 0, 1) }}"
                                    data-type="{{ $row->partner_type }}"
                                    data-amount="{{ $row->amount }}"
                                    data-commission="{{ $row->commission }}"
                                    data-commission-percent="{{ $row->commission_percentage }}"
                                    data-gst="{{ $row->gst }}"
                                    data-gst-percent="{{ $row->gst_percentage }}"
                                    data-platformfee="{{ $row->platform_fee }}"
                                    data-net="{{ $row->net_amount }}"
                                    data-status="{{ $row->status }}"
                                    data-statuslower="{{ strtolower($row->status) }}"
                                    data-date="{{ $row->date }}"
                                    data-bankname="{{ $row->bank_name }}"
                                    data-accno="{{ $row->account_number }}"
                                    data-ifsc="{{ $row->ifsc_code }}"
                                    data-branch="{{ $row->branch_name }}"
                                    data-ref="{{ $row->reference_id }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                @if($row->status == 'Pending')
                                <form action="{{ route('admin.finance.settlements.settle', $row->raw_id) }}" method="POST" style="margin:0; display:flex; gap:8px;">
                                    @csrf
                                    <input type="text" name="reference_id" placeholder="UTR (Optional)" style="border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px 8px; font-size: 0.75rem; width: 120px; outline: none;">
                                    <button type="submit" class="btn-settle-sm">Settle</button>
                                </form>
                                @elseif($row->status == 'Processing')
                                <button class="btn-settle-sm" disabled>Processing</button>
                                @elseif($row->status == 'Failed')
                                <button class="btn-icon red"><i class="fa-solid fa-rotate-right"></i></button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach

                    @else

                    <tr>
                        <td colspan="12" style="text-align:center; padding:40px; color:#94a3b8;">
                            <i class="fa-solid fa-receipt"
                                style="font-size:2rem; margin-bottom:10px; display:block;"></i>

                            <div style="font-size:16px; font-weight:600;">
                                No settlements found
                            </div>

                            <div style="font-size:13px; margin-top:5px;">
                                Try changing your filters or date range.
                            </div>
                        </td>
                    </tr>
                    @endif

                </tbody>
            </table>
            <div style="padding: 16px; border-top: 1px solid #f1f5f9; display:flex; justify-content:space-between; align-items:center; color:#64748b; font-size:0.875rem;">
                <div>
                    Showing
                    {{ $transactions->firstItem() ?? 0 }}
                    to
                    {{ $transactions->lastItem() ?? 0 }}
                    of
                    {{ $transactions->total() }}
                    settlements
                </div>


                <div>
                    {{ $transactions->onEachSide(1)->links() }}
                </div>


            </div>
        </div>
    </div>

    <!-- Sidebar Area -->
    <div class="settlements-sidebar">
        <div class="side-panel">
            <div class="side-panel-header">
                <h3 class="side-panel-title">Settlement Details</h3>
            </div>
            <div id="sd-empty-state" style="text-align:center; padding: 40px 0; color:#94a3b8;">
                <i class="fa-solid fa-hand-pointer" style="font-size: 2rem; margin-bottom: 10px;"></i>
                <p style="font-size: 0.875rem;">Select a settlement from the table<br>to view details</p>
            </div>

            <div id="sd-content" style="display:none;">
                <span class="badge-status" id="sd-status-badge" style="margin-bottom:16px; display:inline-block;"></span>

                <div class="partner-details" style="margin-bottom: 24px;">
                    <div class="partner-avatar" id="sd-avatar" style="width:40px; height:40px; font-size:1rem;"></div>
                    <div>
                        <div class="partner-name" id="sd-partner-name"></div>
                        <div class="partner-sub" id="sd-partner-type"></div>
                    </div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Settlement ID</div>
                    <div class="detail-value" id="sd-id"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Total Amount</div>
                    <div class="detail-value" id="sd-amount"></div>
                </div>
                <div class="detail-row">
                    <div class="detail-label">Commission</div>
                    <div class="detail-value" id="sd-commission"></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">GST</div>
                    <div class="detail-value" id="sd-gst"></div>
                </div>

                <div class="detail-row">
                    <div class="detail-label">Platform Fee</div>
                    <div class="detail-value" id="sd-platform-fee"></div>
                </div>


                <hr style="border:0; border-top:1px dashed #e2e8f0; margin:16px 0;">
                <div class="detail-row">
                    <div class="detail-label">Net Payable</div>
                    <div class="detail-value green" id="sd-net"></div>
                </div>
                <hr style="border:0; border-top:1px dashed #e2e8f0; margin:16px 0;">
                <div class="detail-row">
                    <div class="detail-label">Date</div>
                    <div class="detail-value" id="sd-date"></div>
                </div>
                <div class="detail-row" id="sd-ref-row" style="display:none; margin-top: 10px;">
                    <div class="detail-label">Bank Transaction ID</div>
                    <div class="detail-value" id="sd-ref" style="font-family: monospace; background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-size: 0.8rem; font-weight: 600;"></div>
                </div>

                <!-- Banking Details Section -->
                <div style="background: #f8fafc; border-radius: 8px; padding: 16px; margin: 20px 0; border: 1px solid #e2e8f0;">
                    <h4 style="font-size: 0.875rem; font-weight: 600; color: #1e293b; margin: 0 0 12px 0;"><i class="fa-solid fa-building-columns" style="color: #64748b; margin-right: 6px;"></i> Partner Bank Details</h4>
                    <div style="font-size: 0.8rem; color: #475569;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <span style="color:#64748b;">Bank:</span> <strong id="sd-bank" style="color:#1e293b;">-</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <span style="color:#64748b;">Account No:</span> <strong id="sd-acc" style="color:#1e293b;">-</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                            <span style="color:#64748b;">IFSC:</span> <strong id="sd-ifsc" style="color:#1e293b;">-</strong>
                        </div>
                        <div style="display:flex; justify-content:space-between;">
                            <span style="color:#64748b;">Branch:</span> <strong id="sd-branch" style="color:#1e293b;">-</strong>
                        </div>
                    </div>
                </div>

                <form id="sd-settle-form" action="" method="POST" style="margin-bottom:10px;">
                    @csrf
                    <div style="margin-bottom: 12px;">
                        <label style="font-size: 0.75rem; color: #64748b; margin-bottom: 4px; display: block;">Bank Transaction ID / UTR (Optional)</label>
                        <input type="text" name="reference_id" class="form-control" style="width: 100%; border: 1px solid #e2e8f0; border-radius: 8px; padding: 10px; font-size: 0.875rem;" placeholder="e.g. UTR123456789">
                    </div>
                    <button type="submit" class="btn-settle-lg">Settle Now</button>
                </form>
                <a href="{{ route('admin.finance.transactions.index') }}" id="sd-view-txn-btn" class="btn-outline-lg" style="display:block; text-align:center; text-decoration:none;">View Transaction</a>
            </div>
        </div>

        <div class="side-panel">
            <div class="side-panel-header">
                <h3 class="side-panel-title">Recent Payouts</h3>
                <a href="#" class="side-panel-link">View All</a>
            </div>

            @forelse($recentPayouts as $payout)
            <div class="payout-item">
                <div class="partner-details">
                    <div class="partner-avatar" style="width:28px;height:28px;font-size:0.65rem;">
                        {{ substr($payout->partner_name, 0, 1) }}
                    </div>
                    <div>
                        <div class="partner-name" style="font-size:0.875rem;">{{ $payout->partner_name }}</div>
                        <div class="partner-sub" style="font-size:0.65rem;">{{ $payout->date }}</div>
                    </div>
                </div>
                <div style="text-align:right;">
                    <div class="partner-name" style="font-size:0.875rem;">₹{{ $payout->amount }}</div>
                    <span class="badge-status completed" style="font-size:0.6rem; padding:2px 6px;">Paid</span>
                </div>
            </div>
            @empty
            <div style="text-align:center; color:#94a3b8; font-size:0.875rem; padding: 20px 0;">No recent payouts</div>
            @endforelse
        </div>
    </div>
</div>

@endsection



@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {

        flatpickr("#settlementDateRange", {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: false,

            onChange: function(selectedDates, dateStr, instance) {
                console.log("Selected:", dateStr);
            }
        });

        const viewButtons = document.querySelectorAll('.btn-view-details');
        const emptyState = document.getElementById('sd-empty-state');
        const contentPanel = document.getElementById('sd-content');
        const settleForm = document.getElementById('sd-settle-form');
        const viewTxnBtn = document.getElementById('sd-view-txn-btn');

        viewButtons.forEach(function(btn) {

            btn.addEventListener('click', function() {

                // Hide empty state
                if (emptyState) {
                    emptyState.style.display = 'none';
                }

                // Show details
                if (contentPanel) {
                    contentPanel.style.display = 'block';
                }

                // Get data from button
                const status = this.dataset.status || '';
                const statusLower = this.dataset.statuslower || '';
                const partner = this.dataset.partner || '';
                const initial = this.dataset.initial || '';
                const type = this.dataset.type || '';
                const id = this.dataset.id || '';
                const rawId = this.dataset.rawid || '';
                const amount = this.dataset.amount || '0';
                const commission = this.dataset.commission || '0';
                const commissionPercent = this.dataset.commissionPercent || '0';
                const gst = this.dataset.gst || '0';
                const gstPercent = this.dataset.gstPercent || '0';
                const platformFee = this.dataset.platformfee || '0';
                const net = this.dataset.net || '0';
                const date = this.dataset.date || '';
                const bankName = this.dataset.bankname || '';
                const accountNo = this.dataset.accno || '';
                const ifsc = this.dataset.ifsc || '';
                const branch = this.dataset.branch || '';
                const referenceId = this.dataset.ref || '';

                // Status
                const statusBadge = document.getElementById('sd-status-badge');

                if (statusBadge) {
                    statusBadge.textContent = status;
                    statusBadge.className = 'badge-status ' + statusLower;
                }

                // Partner details
                document.getElementById('sd-avatar').textContent = initial;
                document.getElementById('sd-partner-name').textContent = partner;
                document.getElementById('sd-partner-type').textContent = type;

                // Settlement details
                document.getElementById('sd-id').textContent = id;

                document.getElementById('sd-amount').textContent =
                    '₹' + amount;

                document.getElementById('sd-commission').textContent =
                    '- ₹' + commission + ' (' + commissionPercent + '%)';

                document.getElementById('sd-gst').textContent =
                    '- ₹' + gst + ' (' + gstPercent + '%)';

                document.getElementById('sd-platform-fee').textContent =
                    '- ₹' + platformFee;
                document.getElementById('sd-net').textContent = '₹' + net;
                document.getElementById('sd-date').textContent = date;

                // Bank details
                document.getElementById('sd-bank').textContent =
                    bankName !== '' && bankName !== 'null' ?
                    bankName :
                    'Not Provided';

                document.getElementById('sd-acc').textContent =
                    accountNo !== '' && accountNo !== 'null' ?
                    accountNo :
                    'Not Provided';

                document.getElementById('sd-ifsc').textContent =
                    ifsc !== '' && ifsc !== 'null' ?
                    ifsc :
                    'Not Provided';

                document.getElementById('sd-branch').textContent =
                    branch !== '' && branch !== 'null' ?
                    branch :
                    'Not Provided';

                // Reference ID
                const refRow = document.getElementById('sd-ref-row');
                const refValue = document.getElementById('sd-ref');

                if (
                    referenceId !== '' &&
                    referenceId !== 'null' &&
                    referenceId !== 'undefined'
                ) {
                    refValue.textContent = referenceId;
                    refRow.style.display = 'flex';
                } else {
                    refValue.textContent = '';
                    refRow.style.display = 'none';
                }

                // Settlement form
                if (settleForm) {

                    if (status === 'Pending') {

                        settleForm.style.display = 'block';

                        settleForm.action =
                            "{{ url('/admin/finance/settlements') }}" +
                            "/" +
                            rawId +
                            "/settle";

                    } else {

                        settleForm.style.display = 'none';
                        settleForm.action = '';
                    }
                }

                // Transaction link
                if (viewTxnBtn) {

                    const txId =
                        'TRX' + String(rawId).padStart(4, '0');

                    viewTxnBtn.href =
                        "{{ url('/admin/finance/transactions') }}" +
                        "?search=" +
                        encodeURIComponent(txId);
                }

            });

        });

    });
</script>
@endsection
