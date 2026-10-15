@extends('layouts.admin')

@section('title', 'QR Code Management')

@section('content')
<style>
    :root {
        --purple-light: #f3e8ff; --purple: #a855f7;
        --green-light: #dcfce3; --green: #22c55e;
        --orange-light: #ffedd5; --orange: #f97316;
        --blue-light: #e0f2fe; --blue: #3b82f6;
        --pink-light: #fce7f3; --pink: #ec4899;

        --card-bg: #ffffff;
        --border-color: #e2e8f0;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --bg-body: #f8fafc;
    }

    body.dark-theme {
        --card-bg: #1e293b;
        --border-color: #334155;
        --text-main: #f8fafc;
        --text-muted: #94a3b8;
        --bg-body: #0f172a;
    }

    /* Page Header */
    .page-header {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 4px;
    }
    .breadcrumb-custom {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .breadcrumb-custom a {
        color: var(--primary-color, #4f46e5);
        text-decoration: none;
    }

    /* Stat Cards */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); }
        .stat-card {
            padding: 12px;
            gap: 12px;
            flex-direction: column;
            text-align: center;
        }
        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        .stat-value { font-size: 1.2rem; }
        .stat-label { font-size: 0.7rem; }
    }
    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
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
    .stat-icon.purple { background: var(--purple-light); color: var(--purple); }
    .stat-icon.green { background: var(--green-light); color: var(--green); }
    .stat-icon.orange { background: var(--orange-light); color: var(--orange); }
    .stat-icon.blue { background: var(--blue-light); color: var(--blue); }
    .stat-icon.pink { background: var(--pink-light); color: var(--pink); }

    .stat-content { flex: 1; }
    .stat-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        margin-bottom: 4px;
    }
    .stat-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-main);
    }
    .stat-trend {
        font-size: 0.75rem;
        margin-top: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .stat-trend.up { color: var(--green); }
    .stat-trend.down { color: #ef4444; }
    .stat-trend-text { color: var(--text-muted); font-size: 0.7rem; }

    /* Filters */
    .filter-bar {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 16px;
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .search-input {
        flex: 1 1 200px;
        position: relative;
    }
    .search-input i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }
    .search-input input {
        width: 100%;
        padding: 10px 16px 10px 40px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .search-input input:focus {
        border-color: var(--primary-color, #4f46e5);
        outline: none;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
    }
    .filter-select select {
        padding: 10px 36px 10px 16px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        font-size: 0.9rem;
        appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 16px;
        min-width: 140px;
        flex: 1 1 120px;
    }
    .filter-action-btn {
        flex: 1 1 100px;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        background: var(--bg-body);
        color: var(--text-main);
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }
    .filter-action-btn:hover {
        background: var(--border-color);
    }

    /* Data Table */
    .table-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        overflow: hidden;
    }
    .custom-table th {
        background: var(--card-bg);
        color: var(--text-main);
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
    }
    .custom-table td {
        padding: 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: var(--text-main);
        font-size: 0.9rem;
    }
    .custom-table tr:hover {
        background: rgba(0,0,0,0.01);
    }
    body.dark-theme .custom-table tr:hover { background: rgba(255,255,255,0.02); }

    .bus-id-badge {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }
    .status-active { background: var(--green-light); color: var(--green); }
    .status-inactive { background: var(--orange-light); color: var(--orange); }

    .action-icons {
        display: flex;
        gap: 12px;
    }
    .action-icons a {
        color: var(--text-muted);
        transition: all 0.2s;
        font-size: 1rem;
    }
    .action-icons a:hover.view { color: var(--blue); }
    .action-icons a:hover.download { color: var(--green); }
    .action-icons a:hover.print { color: var(--purple); }

    /* Right Sidebar */
    .qr-sidebar {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
    }
    .qr-sidebar-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 24px;
    }

    .qr-display-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 24px;
    }
    .qr-frame {
        position: relative;
        padding: 24px;
        border: 2px solid var(--green);
        border-radius: 16px;
        margin-bottom: 16px;
        background: white;
    }
    .qr-frame::before, .qr-frame::after {
        content: '';
        position: absolute;
        width: 20px;
        height: 20px;
        border: 4px solid var(--green);
    }
    .qr-frame-tl { position: absolute; top: -2px; left: -2px; width: 30px; height: 30px; border-top: 4px solid var(--green); border-left: 4px solid var(--green); border-radius: 16px 0 0 0; }
    .qr-frame-tr { position: absolute; top: -2px; right: -2px; width: 30px; height: 30px; border-top: 4px solid var(--green); border-right: 4px solid var(--green); border-radius: 0 16px 0 0; }
    .qr-frame-bl { position: absolute; bottom: -2px; left: -2px; width: 30px; height: 30px; border-bottom: 4px solid var(--green); border-left: 4px solid var(--green); border-radius: 0 0 0 16px; }
    .qr-frame-br { position: absolute; bottom: -2px; right: -2px; width: 30px; height: 30px; border-bottom: 4px solid var(--green); border-right: 4px solid var(--green); border-radius: 0 0 16px 0; }

    .qr-frame img {
        width: 180px;
        height: 180px;
        display: block;
    }

    .qr-details-list {
        list-style: none;
        padding: 0;
        margin: 0 0 24px 0;
    }
    .qr-details-list li {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px dashed var(--border-color);
        font-size: 0.9rem;
    }
    .qr-details-list li:last-child {
        border-bottom: none;
    }
    .qr-details-label {
        color: var(--text-muted);
    }
    .qr-details-value {
        color: var(--text-main);
        font-weight: 600;
        text-align: right;
    }

    .btn-qr-action {
        width: 100%;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        text-align: center;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
        border: 1px solid transparent;
        background: transparent;
    }

    .btn-download-split {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
    }
    .btn-download-split button {
        flex: 1;
        padding: 10px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        border: 1px solid var(--blue);
        color: var(--blue);
        background: transparent;
        transition: all 0.2s;
    }
    .btn-download-split button.pdf {
        border-color: var(--orange);
        color: var(--orange);
    }
    .btn-download-split button:hover, .btn-download-split button.pdf:hover {
        opacity: 0.8;
    }

    .btn-print { border-color: var(--blue); color: var(--blue); }
    .btn-regenerate { border-color: var(--orange); color: var(--orange); }
    .btn-disable { background: #ef4444; color: white; }

</style>

<div class="page-header">
    <div>
        <h1 class="page-title">QR Code Management</h1>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">Home</a> <span>&gt;</span> QR Code Management
        </div>
    </div>
    <a href="{{ route('admin.qr.export') }}" class="btn btn-primary" style="background: #4f46e5; border: none; font-weight: 500; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
        <i class="fa-solid fa-download me-2"></i> Export QR Report
    </a>
</div>

<!-- Stat Cards Row -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-qrcode"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total QR Codes</div>
            <div class="stat-value">{{ number_format($totalQRCodes) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 12.5% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-qrcode"></i></div>
        <div class="stat-content">
            <div class="stat-label">Active QR Codes</div>
            <div class="stat-value">{{ number_format($activeQRCodes) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 10.8% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-qrcode"></i></div>
        <div class="stat-content">
            <div class="stat-label">Inactive QR Codes</div>
            <div class="stat-value">{{ number_format($inactiveQRCodes) }}</div>
            <div class="stat-trend down"><i class="fa-solid fa-arrow-down"></i> 9.1% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fa-solid fa-briefcase"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total Scans (This Week)</div>
            <div class="stat-value">{{ number_format($totalScans) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 18.6% <span class="stat-trend-text">vs last week</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-content">
            <div class="stat-label">Avg Scans / QR</div>
            <div class="stat-value">{{ number_format($avgScans, 2) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 8.3% <span class="stat-trend-text">vs last week</span></div>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.qr.index') }}" class="filter-bar">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by bus number, route, operator...">
    </div>
    <div class="filter-select">
        <select name="route">
            <option value="">All Routes</option>
            @foreach($routes as $route)
                <option value="{{ $route }}" {{ request('route') == $route ? 'selected' : '' }}>{{ $route }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="status">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
    </div>
    <div class="filter-select">
        <select name="operator">
            <option value="">All Operators</option>
            @foreach($operators as $operator)
                <option value="{{ $operator }}" {{ request('operator') == $operator ? 'selected' : '' }}>{{ $operator }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="filter-action-btn"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route('admin.qr.index') }}" class="filter-action-btn" style="text-decoration: none;">Reset</a>
</form>

<!-- Split Layout -->
<div class="row g-4">
    <!-- Table Left Side -->
    <div class="col-xl-9">
        <div class="table-card">
            <div class="table-responsive">
                <table class="table custom-table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bus Details</th>
                            <th>Route</th>
                            <th>QR Code</th>
                            <th>Status</th>
                            <th>Total Scans</th>
                            <th>Last Scanned</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buses as $index => $bus)
                        @php
                            $iconClasses = ['purple', 'green', 'orange', 'pink', 'blue'];
                            $iconClass = $iconClasses[$index % count($iconClasses)];

                            $qrCodeId = $bus->qr_code ?: 'QD-'.strtoupper(substr(uniqid(), -8));
                            $createdAt = \Carbon\Carbon::parse($bus->created_at)->format('M d, Y');

                            $lastScanned = $bus->last_scanned_at ? \Carbon\Carbon::parse($bus->last_scanned_at)->format('M d, Y h:i A') : 'Never Scanned';

                            // Ensure the URL is fully qualified with http/https so mobile phones recognize it as a clickable web link
                            $trackingUrl = route('qr.scan', $qrCodeId);
                            if (!str_starts_with($trackingUrl, 'http://') && !str_starts_with($trackingUrl, 'https://')) {
                                $trackingUrl = 'http://' . ltrim($trackingUrl, '/');
                            }
                        @endphp
                        <tr class="action-view" data-bus="{{ json_encode([
                            'bus_number' => $bus->bus_number,
                            'bus_name' => $bus->bus_name,
                            'operator' => $bus->operator,
                            'route_from' => $bus->route_from,
                            'route_to' => $bus->route_to,
                            'created_at' => \Carbon\Carbon::parse($bus->created_at)->format('M d, Y h:i A'),
                            'status' => $bus->status,
                            'total_scans' => number_format($bus->total_scans),
                            'last_scanned_at' => $lastScanned,
                            'qr_code' => $qrCodeId,
                            'qr_url' => 'https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($trackingUrl),
                            'id' => $bus->id
                        ]) }}" style="cursor: pointer;">
                            <td class="text-muted">{{ $buses->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bus-id-badge stat-icon {{ $iconClass }}" style="width:36px; height:36px; border-radius:8px;"><i class="fa-solid fa-bus"></i></div>
                                    <div>
                                        <div style="font-weight: 600;">{{ $bus->bus_number }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $bus->operator ?: 'No Operator' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem;">{{ $bus->route_from ?: 'N/A' }} &rarr;</div>
                                <div style="font-size: 0.85rem;">{{ $bus->route_to ?: 'N/A' }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=40x40&data={{ urlencode($trackingUrl) }}" style="border-radius:4px; width:30px; height:30px;" alt="QR">
                                    <div>
                                        <div style="font-weight: 600; font-size: 0.85rem;">{{ $qrCodeId }}</div>
                                        <div style="font-size: 0.75rem; color: var(--text-muted);">Created: {{ $createdAt }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($bus->status)
                                    <span class="status-badge status-active">Active</span>
                                @else
                                    <span class="status-badge status-inactive">Inactive</span>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ number_format($bus->total_scans) }}</td>
                            <td>
                                <div style="font-size: 0.85rem;">{{ $bus->last_scanned_at ? \Carbon\Carbon::parse($bus->last_scanned_at)->format('M d, Y') : 'Never Scanned' }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $bus->last_scanned_at ? \Carbon\Carbon::parse($bus->last_scanned_at)->format('h:i A') : '' }}</div>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($trackingUrl) }}" download="QR_{{ $bus->bus_number }}.png" target="_blank" class="download"><i class="fa-solid fa-download"></i></a>
                                    <a href="#" class="print" onclick="printSingleQR('https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode($trackingUrl) }}'); return false;"><i class="fa-solid fa-print"></i></a>
                                    <form action="{{ route('admin.buses.destroy', $bus->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-danger" style="background:none; border:none; padding:0; cursor:pointer;" onclick="return confirm('Are you sure you want to delete this bus and QR code?');" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No QR codes found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3" style="border-top: 1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted);">
                <div>Showing {{ $buses->firstItem() ?? 0 }} to {{ $buses->lastItem() ?? 0 }} of {{ $buses->total() }} QR Codes</div>
                <div>{{ $buses->links('pagination::bootstrap-4') }}</div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar -->
    <div class="col-xl-3">
        <div class="qr-sidebar" id="qrSidebar">
            <div class="qr-sidebar-title">QR Code Details</div>

            @if($buses->count() > 0)
                <div id="qrPlaceholder" class="text-center text-muted py-5">
                    Select a QR code from the list to view its details.
                </div>

                <div id="qrDetailsContent" style="display: none;">
                    <div class="qr-display-container">
                        <div class="qr-frame">
                            <div class="qr-frame-tl"></div>
                            <div class="qr-frame-tr"></div>
                            <div class="qr-frame-bl"></div>
                            <div class="qr-frame-br"></div>
                            <img id="detail-qr-img" src="" alt="QR Code">
                        </div>
                        <span id="detail-status" class="status-badge"><i class="fa-solid fa-circle" style="font-size:8px;"></i> Status</span>
                    </div>

                    <ul class="qr-details-list">
                        <li><span class="qr-details-label">Bus Number</span><span class="qr-details-value" id="detail-bus-num"></span></li>
                        <li><span class="qr-details-label">Bus Name</span><span class="qr-details-value" id="detail-bus-name"></span></li>
                        <li><span class="qr-details-label">Operator</span><span class="qr-details-value" id="detail-operator"></span></li>
                        <li><span class="qr-details-label">Route</span><span class="qr-details-value" id="detail-route"></span></li>
                        <li><span class="qr-details-label">Created On</span><span class="qr-details-value" id="detail-created"></span></li>
                        <li><span class="qr-details-label">Status</span><span class="qr-details-value" id="detail-status-text"><i class="fa-solid fa-circle" style="font-size:8px;"></i> Status</span></li>
                        <li><span class="qr-details-label">Total Scans</span><span class="qr-details-value" id="detail-scans"></span></li>
                        <li><span class="qr-details-label">Last Scanned</span><span class="qr-details-value" id="detail-last"></span></li>
                        <li><span class="qr-details-label">QR Code ID</span><span class="qr-details-value" id="detail-qr-id"></span></li>
                    </ul>

                    <button type="button" class="btn-qr-action" style="border-color: var(--blue); color: var(--blue);" onclick="downloadPNG()"><i class="fa-solid fa-download me-2"></i> Download PNG</button>
                    <button type="button" class="btn-qr-action btn-print" onclick="printQR()"><i class="fa-solid fa-print me-2"></i> Print QR Code</button>


                    <form id="formDelete" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-qr-action" style="background: #ef4444; color: white;" onclick="return confirm('Are you sure you want to completely delete this bus and its QR code? This action cannot be undone.')"><i class="fa-solid fa-trash me-2"></i> Delete Bus</button>
                    </form>
                </div>
            @else
                <div class="text-center text-muted py-5">
                    No QR codes available yet.
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Row click functionality
    document.querySelectorAll('.action-view').forEach(row => {
        row.addEventListener('click', function(e) {
            // Prevent if clicked on action icons
            if(e.target.closest('.action-icons')) return;

            const data = JSON.parse(this.getAttribute('data-bus'));

            document.getElementById('detail-qr-img').src = data.qr_url;

            const statusBadge = document.getElementById('detail-status');
            const statusText = document.getElementById('detail-status-text');
            if (data.status) {
                statusBadge.className = 'status-badge status-active';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle" style="font-size:8px;"></i> Active';
                statusText.style.color = 'var(--green)';
                statusText.innerHTML = '<i class="fa-solid fa-circle" style="font-size:8px;"></i> Active';
            } else {
                statusBadge.className = 'status-badge status-inactive';
                statusBadge.innerHTML = '<i class="fa-solid fa-circle" style="font-size:8px;"></i> Inactive';
                statusText.style.color = 'var(--orange)';
                statusText.innerHTML = '<i class="fa-solid fa-circle" style="font-size:8px;"></i> Inactive';
            }

            document.getElementById('detail-bus-num').textContent = data.bus_number;
            document.getElementById('detail-bus-name').textContent = data.bus_name || '-';
            document.getElementById('detail-operator').textContent = data.operator || '-';
            document.getElementById('detail-route').innerHTML = `${data.route_from || 'N/A'} &rarr; ${data.route_to || 'N/A'}`;
            document.getElementById('detail-created').textContent = data.created_at;
            document.getElementById('detail-scans').textContent = data.total_scans;
            document.getElementById('detail-last').textContent = data.last_scanned_at;
            document.getElementById('detail-qr-id').textContent = data.qr_code;

            // Update forms
            document.getElementById('formDelete').action = `{{ url('/admin/buses') }}/${data.id}`;

            // Show content, hide placeholder
            document.getElementById('qrPlaceholder').style.display = 'none';
            document.getElementById('qrDetailsContent').style.display = 'block';
        });
    });
});

function downloadPNG() {
    const imgUrl = document.getElementById('detail-qr-img').src;
    const busNum = document.getElementById('detail-bus-num').textContent;
    const link = document.createElement('a');
    link.href = imgUrl;
    link.download = `QR_${busNum}.png`;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function printQR() {
    const imgUrl = document.getElementById('detail-qr-img').src;
    const busNum = document.getElementById('detail-bus-num').textContent;
    const qrId = document.getElementById('detail-qr-id').textContent;
    printSingleQR(imgUrl, busNum, qrId);
}

function printSingleQR(url, busNum = '', qrId = '') {
    const win = window.open('', '_blank');
    win.document.write(`
        <html>
        <head>
            <title>Print QR Code</title>
            <style>
                body { font-family: sans-serif; text-align: center; padding: 40px; }
                img { max-width: 300px; margin-bottom: 20px; }
                h2 { margin: 0 0 10px 0; }
                p { color: #666; margin: 0; }
            </style>
        </head>
        <body>
            <h2>Bus ${busNum}</h2>
            <img src="${url}" onload="window.print(); window.close();" />
            <p>${qrId}</p>
        <\/body>
        <\/html>
    `);
    win.document.close();
}
</script>
@endsection
