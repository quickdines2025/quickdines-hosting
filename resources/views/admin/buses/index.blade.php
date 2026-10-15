@extends('layouts.admin')

@section('content')
<style>
    /* Premium Page Header */
    .page-header {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 1.5rem;
    }

    .page-title {
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }

    .breadcrumb-custom {
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .breadcrumb-custom a {
        color: var(--primary-color);
        text-decoration: none;
        font-weight: 500;
    }

    /* Stat Cards */
    .stat-card {
        background: var(--topbar-bg);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        height: 100%;
    }

    body.dark-theme .stat-card {
        border-color: rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.05);
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

    .icon-purple {
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
    }

    .icon-green {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .icon-pink {
        background: rgba(236, 72, 153, 0.1);
        color: #ec4899;
    }

    .icon-orange {
        background: rgba(245, 158, 11, 0.1);
        color: #f59e0b;
    }

    .icon-blue {
        background: rgba(59, 130, 246, 0.1);
        color: #3b82f6;
    }

    .stat-content {
        flex-grow: 1;
    }

    .stat-label {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        line-height: 1.2;
    }

    .stat-trend {
        font-size: 0.7rem;
        font-weight: 500;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .trend-up {
        color: #22c55e;
    }

    .trend-down {
        color: #ef4444;
    }

    @media (max-width: 768px) {
        .stat-card {
            padding: 10px;
            gap: 8px;
            flex-direction: column;
            text-align: center;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }

        .stat-value {
            font-size: 1.2rem;
        }

        .stat-label {
            font-size: 0.65rem;
            margin-bottom: 2px;
        }
    }

    .filter-bar {
        background: var(--topbar-bg);
        border-radius: 12px;
        padding: 15px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    body.dark-theme .filter-bar {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .search-input {
        flex: 1 1 250px;
        position: relative;
    }

    .search-input i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }

    .search-input input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        background: var(--bg-body);
        color: var(--text-main);
        outline: none;
    }

    body.dark-theme .search-input input {
        border-color: #334155;
    }

    .filter-select {
        flex: 1 1 140px;
        min-width: 140px;
    }

    .filter-select select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.85rem;
        background: var(--bg-body);
        color: var(--text-main);
        outline: none;
        appearance: none;
        cursor: pointer;
    }

    body.dark-theme .filter-select select {
        border-color: #334155;
    }

    .filter-action-btn {
        flex: 1 1 100px;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.85rem;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        border: 1px solid #e2e8f0;
        background: transparent;
        color: var(--text-main);
        transition: all 0.2s ease;
    }

    body.dark-theme .filter-action-btn {
        border-color: #334155;
    }

    .filter-action-btn:hover {
        background: var(--bg-body);
    }

    /* Custom Table */
    .table-card {
        background: var(--topbar-bg);
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }

    body.dark-theme .table-card {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .custom-table {
        width: 100%;
        margin-bottom: 0;
        color: var(--text-main);
        font-size: 0.85rem;
    }

    .custom-table th {
        background: transparent;
        padding: 12px 10px;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 1px solid #e2e8f0;
    }

    body.dark-theme .custom-table th {
        border-color: #334155;
    }

    .custom-table td {
        padding: 12px 10px;
        vertical-align: middle;
        border-bottom: 1px solid #f1f5f9;
    }

    body.dark-theme .custom-table td {
        border-color: #1e293b;
    }

    .custom-table tbody tr:hover {
        background: var(--bg-body);
    }

    .custom-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* Table Specific Elements */
    .bus-id-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 8px;
        background: rgba(99, 102, 241, 0.1);
        color: #6366f1;
        font-size: 0.9rem;
    }

    .bus-name {
        font-weight: 600;
        color: var(--text-main);
        display: block;
        margin-bottom: 2px;
    }

    .bus-operator {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .route-text {
        font-weight: 500;
        color: var(--text-main);
        display: block;
        margin-bottom: 2px;
    }

    .route-sub {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .driver-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .driver-avatar {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        object-fit: cover;
    }

    .driver-name {
        font-weight: 500;
        color: var(--text-main);
        display: block;
        margin-bottom: 2px;
    }

    .driver-phone {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .status-badge {
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-active {
        background: rgba(34, 197, 94, 0.1);
        color: #22c55e;
    }

    .status-inactive {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
    }

    .qr-preview {
        font-size: 1.5rem;
        color: var(--text-muted);
        cursor: pointer;
    }

    .qr-preview:hover {
        color: var(--primary-color);
    }

    .action-icons {
        display: flex;
        gap: 10px;
    }

    .action-icons a {
        color: var(--text-muted);
        font-size: 0.9rem;
        transition: color 0.2s ease;
    }

    .action-icons a.edit:hover {
        color: #3b82f6;
    }

    .action-icons a.view:hover {
        color: #6366f1;
    }

    .action-icons a.delete:hover {
        color: #ef4444;
    }

    /* Right Panel (Bus Details) */
    .details-panel {
        background: var(--topbar-bg);
        border-radius: 12px;
        border: 1px solid rgba(0, 0, 0, 0.05);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        padding: 20px;
        height: 100%;
    }

    body.dark-theme .details-panel {
        border-color: rgba(255, 255, 255, 0.05);
    }

    .details-header {
        font-size: 1rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 20px;
    }

    .bus-image-wrapper {
        width: 100%;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 20px;
        background: #f8fafc;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 10px;
    }

    body.dark-theme .bus-image-wrapper {
        background: #0f172a;
    }

    .bus-image {
        max-width: 100%;
        height: auto;
    }

    .details-list {
        list-style: none;
        padding: 0;
        margin: 0 0 20px 0;
    }

    .details-list li {
        display: flex;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
        font-size: 0.8rem;
    }

    body.dark-theme .details-list li {
        border-color: #1e293b;
    }

    .details-list li:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: var(--text-muted);
        font-weight: 500;
    }

    .detail-value {
        color: var(--text-main);
        font-weight: 600;
        text-align: right;
    }

    .detail-value.link {
        color: var(--primary-color);
        cursor: pointer;
    }

    /* Pagination */
    .pagination-wrapper {
        padding: 15px 20px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    body.dark-theme .pagination-wrapper {
        border-color: #334155;
    }

    .pagination-custom {
        display: flex;
        gap: 5px;
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .pagination-custom li a,
    .pagination-custom li span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 4px;
        color: var(--text-main);
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination-custom li a:hover {
        background: var(--bg-body);
    }

    .pagination-custom li.active span {
        background: var(--primary-color);
        color: white;
    }

    .btn-edit-bus {
        width: 100%;
        background: var(--primary-color);
        color: white;
        border: none;
        padding: 12px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.85rem;
        transition: opacity 0.2s;
    }

    .btn-edit-bus:hover {
        opacity: 0.9;
    }

    .btn-qr-action {
        font-size: 0.75rem;
        border-color: var(--primary-color);
        color: var(--primary-color);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .btn-qr-action:hover {
        background-color: var(--primary-color);
        color: white;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Bus Management</h1>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">Home</a> <span>&gt;</span> Bus Management
        </div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBusModal" style="background: var(--primary-color); border: none; font-weight: 500; padding: 8px 16px; border-radius: 8px;">
        <i class="fa-solid fa-plus me-1"></i> Add New Bus
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; border: none; background: rgba(34, 197, 94, 0.1); color: #22c55e;">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-radius: 8px; border: none; background: rgba(239, 68, 68, 0.1); color: #ef4444;">
    <ul class="mb-0 ps-3">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Stat Cards Row -->
<div class="row g-3 mb-4">
    <!-- Total Buses -->
    <div class="col-6 col-md-4 col-xl-auto flex-grow-1">
        <div class="stat-card">
            <div class="stat-icon icon-purple"><i class="fa-solid fa-bus"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Buses</div>
                <div class="stat-value">{{ number_format($totalBuses) }}</div>
            </div>
        </div>
    </div>
    <!-- Active Buses -->
    <div class="col-6 col-md-4 col-xl-auto flex-grow-1">
        <div class="stat-card">
            <div class="stat-icon icon-green"><i class="fa-solid fa-bus-simple"></i></div>
            <div class="stat-content">
                <div class="stat-label">Active Buses</div>
                <div class="stat-value">{{ number_format($activeBuses) }}</div>
            </div>
        </div>
    </div>
    <!-- Inactive Buses -->
    <div class="col-6 col-md-4 col-xl-auto flex-grow-1">
        <div class="stat-card">
            <div class="stat-icon icon-pink"><i class="fa-solid fa-store-slash"></i></div>
            <div class="stat-content">
                <div class="stat-label">Inactive Buses</div>
                <div class="stat-value">{{ number_format($inactiveBuses) }}</div>
            </div>
        </div>
    </div>
    <!-- Total Routes -->
    <div class="col-6 col-md-4 col-xl-auto flex-grow-1">
        <div class="stat-card">
            <div class="stat-icon icon-orange"><i class="fa-solid fa-route"></i></div>
            <div class="stat-content">
                <div class="stat-label">Total Routes</div>
                <div class="stat-value">{{ number_format($totalRoutes) }}</div>
            </div>
        </div>
    </div>
    <!-- QR Scans -->
    <div class="col-6 col-md-4 col-xl-auto flex-grow-1">
        <div class="stat-card">
            <div class="stat-icon icon-blue"><i class="fa-solid fa-qrcode"></i></div>
            <div class="stat-content">
                <div class="stat-label">QR Scans (This Week)</div>
                <div class="stat-value">{{ number_format($qrScans) }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.buses.index') }}" class="filter-bar">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by bus number, name, operator...">
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
    <a href="{{ route('admin.buses.index') }}" class="filter-action-btn" style="text-decoration: none;">Reset</a>
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
                            <th>Departure &rarr; Arrival</th>
                            <th>Driver</th>
                            <th>Status</th>
                            <th>QR Code</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($buses as $index => $bus)
                        @php
                        // Color specific badge classes
                        $iconClasses = ['icon-purple', 'icon-green', 'icon-orange', 'icon-pink', 'icon-blue'];
                        $iconClass = $iconClasses[$index % count($iconClasses)];

                        // Formatting times
                        $departure = $bus->departure_time ? \Carbon\Carbon::parse($bus->departure_time)->format('h:i A') : 'N/A';
                        $arrival = $bus->arrival_time ? \Carbon\Carbon::parse($bus->arrival_time)->format('h:i A') : 'N/A';

                        // Calculating duration
                        $duration = 'N/A';
                        if($bus->departure_time && $bus->arrival_time) {
                        $dep = \Carbon\Carbon::parse($bus->departure_time);
                        $arr = \Carbon\Carbon::parse($bus->arrival_time);
                        if($arr->lessThan($dep)) {
                        $arr->addDay(); // Handles overnight trips
                        }
                        $duration = $dep->diff($arr)->format('%hh %Im');
                        }
                        @endphp
                        <tr class="action-view" data-bus='{{ json_encode($bus) }}' style="cursor: pointer;">
                            <td class="text-muted">{{ $buses->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bus-id-badge {{ $iconClass }}">
                                        <i class="fa-solid fa-bus"></i>
                                    </div>

                                    <div>
                                        {{-- Bus Name --}}
                                        <span class="bus-name">
                                            {{ $bus->bus_name ?: 'Bus' }}
                                        </span>

                                        {{-- Bus Number / Number Plate --}}
                                        <span style="
                display: inline-flex;
                align-items: center;
                gap: 4px;
                margin-top: 4px;
                padding: 3px 7px;
                border-radius: 4px;
                background: rgba(99, 102, 241, 0.1);
                color: #6366f1;
                font-size: 0.68rem;
                font-weight: 600;
            ">
                                            <i class="fa-solid fa-id-card"></i>
                                            {{ $bus->bus_number ?: 'N/A' }}
                                        </span><br>

                                        {{-- Operator --}}
                                        <span class="bus-operator">
                                            {{ $bus->operator ?: 'No Operator' }}
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="route-text">{{ $bus->route_from ?: 'N/A' }} &rarr;</span>
                                <span class="route-sub">{{ $bus->route_to ?: 'N/A' }}</span>
                                @if($bus->distance)
                                <div style="font-size: 0.75rem; color: #8b5cf6; margin-top: 2px;"><i class="fa-solid fa-road me-1"></i>{{ $bus->distance }}</div>
                                @endif
                            </td>
                            <td>
                                <span class="route-text">{{ $departure }} &rarr; {{ $arrival }}</span>
                                <span class="route-sub">{{ $duration }}</span>
                            </td>
                            <td style="min-width: 200px;">
                                @if($bus->drivers->count() > 0)
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    @foreach($bus->drivers as $driver)
                                    @if($driver->user)
                                    <div class="driver-info">
                                        @if($driver->user->profile_image)
                                        <img src="{{ asset('storage/' . $driver->user->profile_image) }}" class="driver-avatar" alt="Driver">
                                        @else
                                        <div class="driver-avatar" style="background:#e2e8f0; display:flex; align-items:center; justify-content:center; color:#64748b; font-weight:600; font-size:0.8rem;">
                                            {{ substr($driver->user->name, 0, 1) }}
                                        </div>
                                        @endif
                                        <div>
                                            <span class="driver-name">{{ $driver->user->name }}</span>
                                            <span class="driver-phone">{{ $driver->user->mobile }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                                @else
                                <div class="driver-info">
                                    <div class="driver-avatar" style="background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;"><i class="fa-solid fa-user-slash"></i></div>
                                    <div>
                                        <span class="driver-name" style="color: #94a3b8; font-style: italic;">Unassigned</span>
                                        <span class="driver-phone" style="color: #cbd5e1;">-</span>
                                    </div>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($bus->status)
                                <span class="status-badge status-active">Active</span>
                                @else
                                <span class="status-badge status-inactive">Inactive</span>
                                @endif

                                @if($bus->journey_status)
                                <div style="margin-top: 8px; font-size: 0.75rem; font-weight: 600; color: #4338ca; background: #e0e7ff; padding: 3px 8px; border-radius: 10px; display: inline-block;">
                                    <i class="fa-solid fa-location-arrow me-1"></i> {{ $bus->journey_status }}
                                </div>
                                @endif
                            </td>
                            <td>
                                <i class="fa-solid fa-qrcode qr-preview"></i>
                            </td>
                            <td>
                                <div class="action-icons">
                                    <a href="{{ route('admin.buses.manage-route', $bus->id) }}" class="manage-route" title="Manage Route & ETA" style="color: #4f46e5;"><i class="fa-solid fa-map-location-dot"></i></a>
                                    <a href="#" class="assign-driver" data-bus='{{ json_encode($bus) }}' title="Assign Drivers"><i class="fa-solid fa-users-gear"></i></a>
                                    <a href="#" class="edit action-edit" data-bus='{{ json_encode($bus) }}'><i class="fa-solid fa-pen"></i></a>
                                    <a href="#" class="delete action-delete" data-id="{{ $bus->id }}"><i class="fa-solid fa-trash-can"></i></a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">No buses registered yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination to match design -->
            <div class="pagination-wrapper">
                <div>Showing {{ $buses->firstItem() ?? 0 }} to {{ $buses->lastItem() ?? 0 }} of {{ $buses->total() }} buses</div>
                <div>
                    {{ $buses->links('pagination::bootstrap-4') }}
                </div>
            </div>

        </div>
    </div>

    <!-- Right Panel Details -->
    <div class="col-xl-3">
        <div class="details-panel">
            <h4 class="details-header">Bus Details</h4>

            <div class="bus-image-wrapper" id="busImageWrapper" style="display: none;">
                <!-- Using a placeholder bus image that matches the requested design style -->
                <img src="https://purepng.com/public/uploads/large/purepng.com-busbusvehiclecaravanvolkswagon-170152740706240zhq.png" alt="Bus" class="bus-image" style="max-height: 150px; object-fit: contain;">
            </div>

            <ul class="details-list" id="busDetailsList">
                <li class="text-center py-5 text-muted" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-bus" style="font-size: 2rem; color: #e2e8f0;"></i>
                    <span>Select a bus from the list to view its details.</span>
                </li>
            </ul>

            <button class="btn-edit-bus" id="btnEditBus" style="display: none;">Edit Bus</button>
        </div>
    </div>
</div>

<style>
    /* Pagination override to match UI perfectly */
    .pagination {
        margin: 0;
    }

    .page-item .page-link {
        width: 28px;
        height: 28px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        border: none;
        color: var(--text-main);
        font-weight: 500;
        background: transparent;
        margin: 0 2px;
    }

    .page-item.active .page-link {
        background: var(--primary-color);
        color: white;
    }

    .page-item.disabled .page-link {
        background: transparent;
        color: var(--text-muted);
    }

    body.dark-theme .page-item .page-link:hover:not(.active) {
        background: var(--bg-body);
    }
</style>

<!-- Add Bus Modal -->
<div class="modal fade" id="addBusModal" tabindex="-1" aria-labelledby="addBusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: var(--topbar-bg);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="modal-title" id="addBusModalLabel" style="font-weight: 600; color: var(--text-main);">Add New Bus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.buses.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="bus_number" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Bus Number *</label>
                            <input type="text" class="form-control" id="bus_number" name="bus_number" placeholder="e.g. MH12RX6789" required style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="bus_name" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Bus Name</label>
                            <input type="text" class="form-control" id="bus_name" name="bus_name" placeholder="e.g. Volvo 9600" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="operator" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Operator</label>
                            <input type="text" class="form-control" id="operator" name="operator" placeholder="e.g. VRL Travels" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Status *</label>
                            <select class="form-select" id="status" name="status" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="route_from" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Route From</label>
                            <input type="text" class="form-control" id="route_from" name="route_from" placeholder="e.g. Hyderabad" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-4">
                            <label for="route_to" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Route To</label>
                            <input type="text" class="form-control" id="route_to" name="route_to" placeholder="e.g. Visakhapatnam" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-4">
                            <label for="distance" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Distance</label>
                            <input type="text" class="form-control" id="distance" name="distance" placeholder="e.g. 628 km" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>

                        <div class="col-md-6">
                            <label for="departure_time" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Departure Time</label>
                            <input type="time" class="form-control" id="departure_time" name="departure_time" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Arrival Time</label>
                            <input type="time" name="arrival_time" class="form-control">
                        </div>
                    </div>

                    <div class="alert alert-info py-2 mt-4 mb-0" style="font-size: 0.8rem; border-radius: 8px; border: none; background: rgba(59, 130, 246, 0.1); color: #3b82f6;">
                        <i class="fa-solid fa-circle-info me-2"></i> QR code will be generated automatically upon saving.
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: var(--primary-color); border: none; font-weight: 500;">Save Bus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Bus Modal -->
<div class="modal fade" id="editBusModal" tabindex="-1" aria-labelledby="editBusModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: var(--topbar-bg);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="modal-title" id="editBusModalLabel" style="font-weight: 600; color: var(--text-main);">Edit Bus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editBusForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="edit_bus_number" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Bus Number *</label>
                            <input type="text" class="form-control" id="edit_bus_number" name="bus_number" required style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_bus_name" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Bus Name</label>
                            <input type="text" class="form-control" id="edit_bus_name" name="bus_name" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_operator" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Operator</label>
                            <input type="text" class="form-control" id="edit_operator" name="operator" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_status" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Status *</label>
                            <select class="form-select" id="edit_status" name="status" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label for="edit_route_from" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Route From</label>
                            <input type="text" class="form-control" id="edit_route_from" name="route_from" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_route_to" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Route To</label>
                            <input type="text" class="form-control" id="edit_route_to" name="route_to" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-4">
                            <label for="edit_distance" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Distance</label>
                            <input type="text" class="form-control" id="edit_distance" name="distance" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>

                        <div class="col-md-6">
                            <label for="edit_departure_time" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Departure Time</label>
                            <input type="time" class="form-control" id="edit_departure_time" name="departure_time" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-6">
                            <label for="edit_arrival_time" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Arrival Time</label>
                            <input type="time" class="form-control" id="edit_arrival_time" name="arrival_time" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                        <div class="col-md-12">
                            <label for="edit_image" class="form-label" style="font-size: 0.85rem; font-weight: 500; color: var(--text-muted);">Update Bus Image (Leave empty to keep current)</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*" style="border-radius: 8px; border: 1px solid #e2e8f0; padding: 10px 15px; font-size: 0.9rem; background: var(--bg-body); color: var(--text-main);">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: var(--primary-color); border: none; font-weight: 500;">Update Bus</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View QR Modal -->
<div class="modal fade" id="viewQrModal" tabindex="-1" aria-labelledby="viewQrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: var(--topbar-bg);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                <h5 class="modal-title" id="viewQrModalLabel" style="font-weight: 600; color: var(--text-main);">View QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img id="qrModalImage" src="" alt="QR Code" class="img-fluid" style="max-width: 300px; border-radius: 8px;">
            </div>
        </div>
    </div>
</div>

<!-- Assign Drivers Modal -->
<div class="modal fade" id="assignDriversModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 12px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1); background: var(--topbar-bg);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(0,0,0,0.05); padding: 20px;">
                <h5 class="modal-title" style="font-weight: 600; color: var(--text-main);">Assign Drivers to Bus: <span id="assignDriversBusName" style="color: var(--primary-color);"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="assignDriversForm" action="{{ route('admin.buses.assign-drivers') }}" method="POST">
                @csrf
                <input type="hidden" name="bus_id" id="assignDriversBusId">
                <div class="modal-body" style="padding: 20px;">

                    <div style="position: relative; margin-bottom: 15px;">
                        <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
                        <input type="text" id="driverSearch" placeholder="Search driver by name..." style="width: 100%; padding: 8px 12px 8px 35px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 0.85rem; outline: none;" onkeyup="filterDrivers()">
                    </div>

                    <div id="driverListContainer" style="max-height: 400px; overflow-y: auto; display: flex; flex-direction: column; gap: 10px;">
                        @if(isset($allDrivers))
                        @foreach($allDrivers as $driver)
                        @if($driver->user)
                        <label class="driver-list-item d-flex align-items-center gap-3" data-name="{{ strtolower($driver->user->name) }}" style="padding: 12px 15px; border-radius: 10px; border: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s ease;">
                            <input type="checkbox" name="driver_ids[]" value="{{ $driver->id }}" class="driver-checkbox" style="width: 18px; height: 18px; accent-color: var(--primary-color);">

                            @if($driver->user->profile_image)
                            <img src="{{ asset('storage/' . $driver->user->profile_image) }}" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            @else
                            <div style="width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; color: #64748b; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; font-weight: bold;">
                                {{ substr($driver->user->name, 0, 1) }}
                            </div>
                            @endif

                            <div style="flex: 1;">
                                <div style="font-weight: 600; font-size: 0.9rem; color: var(--text-main);">{{ $driver->user->name }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $driver->user->mobile }} | Exp: {{ $driver->experience ?? 'N/A' }} Yrs</div>
                            </div>

                            @if($driver->status)
                            <span class="status-badge status-active" style="font-size: 0.65rem;">Active</span>
                            @else
                            <span class="status-badge status-inactive" style="font-size: 0.65rem;">Inactive</span>
                            @endif
                        </label>
                        @endif
                        @endforeach
                        @endif
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.05); padding: 15px 20px;">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal" style="border-radius: 8px; font-weight: 500;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="border-radius: 8px; background: var(--primary-color); border: none; font-weight: 500;">Save Assignments</button>
                </div>
            </form>
        </div>
    </div>
</div>

<form id="deleteBusForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Assign Driver action
        document.querySelectorAll('.assign-driver').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const bus = JSON.parse(this.getAttribute('data-bus'));

                document.getElementById('assignDriversBusId').value = bus.id;
                document.getElementById('assignDriversBusName').innerText = bus.bus_number + (bus.bus_name ? ' (' + bus.bus_name + ')' : '');

                // Check the checkboxes for drivers already assigned to this bus
                const checkboxes = document.querySelectorAll('.driver-checkbox');
                checkboxes.forEach(cb => {
                    cb.checked = false; // Reset first
                    if (bus.drivers && bus.drivers.length > 0) {
                        const isAssigned = bus.drivers.some(d => d.id == cb.value);
                        if (isAssigned) {
                            cb.checked = true;
                        }
                    }
                });

                // Show modal
                new bootstrap.Modal(document.getElementById('assignDriversModal')).show();
            });
        });

        // Edit action
        document.querySelectorAll('.action-edit').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const bus = JSON.parse(this.getAttribute('data-bus'));

                document.getElementById('editBusForm').action = `{{ url('/admin/buses') }}/${bus.id}`;
                document.getElementById('edit_bus_number').value = bus.bus_number;
                document.getElementById('edit_bus_name').value = bus.bus_name || '';
                document.getElementById('edit_operator').value = bus.operator || '';
                document.getElementById('edit_route_from').value = bus.route_from || '';
                document.getElementById('edit_route_to').value = bus.route_to || '';
                document.getElementById('edit_distance').value = bus.distance || '';
                document.getElementById('edit_departure_time').value = bus.departure_time ? bus.departure_time.substring(0, 5) : '';
                document.getElementById('edit_arrival_time').value = bus.arrival_time ? bus.arrival_time.substring(0, 5) : '';
                document.getElementById('edit_status').value = bus.status;

                new bootstrap.Modal(document.getElementById('editBusModal')).show();
            });
        });

        // View action (Update right panel)
        document.querySelectorAll('.action-view').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const bus = JSON.parse(this.getAttribute('data-bus'));

                // Format time helper
                const formatTime = (timeStr) => {
                    if (!timeStr) return '-';
                    const [h, m] = timeStr.split(':');
                    const date = new Date(2000, 0, 1, h, m);
                    return date.toLocaleTimeString('en-US', {
                        hour: 'numeric',
                        minute: '2-digit',
                        hour12: true
                    });
                };

                // Format date helper
                const formatDate = (dateStr) => {
                    if (!dateStr) return '-';
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                };

                // Set image
                const imgEl = document.querySelector('#busImageWrapper .bus-image');
                if (bus.image) {
                    imgEl.src = `/quick/storage/${bus.image}`;
                } else {
                    imgEl.src = 'https://purepng.com/public/uploads/large/purepng.com-busbusvehiclecaravanvolkswagon-170152740706240zhq.png';
                }

                // Show the hidden elements
                document.getElementById('busImageWrapper').style.display = 'block';
                const btnEdit = document.getElementById('btnEditBus');
                btnEdit.style.display = 'block';

                // Setup edit button to open the modal for this bus
                btnEdit.onclick = () => {
                    document.getElementById('editBusForm').action = `{{ url('/admin/buses') }}/${bus.id}`;
                    document.getElementById('edit_bus_number').value = bus.bus_number;
                    document.getElementById('edit_bus_name').value = bus.bus_name || '';
                    document.getElementById('edit_operator').value = bus.operator || '';
                    document.getElementById('edit_route_from').value = bus.route_from || '';
                    document.getElementById('edit_route_to').value = bus.route_to || '';
                    document.getElementById('edit_distance').value = bus.distance || '';
                    document.getElementById('edit_departure_time').value = bus.departure_time ? bus.departure_time.substring(0, 5) : '';
                    document.getElementById('edit_arrival_time').value = bus.arrival_time ? bus.arrival_time.substring(0, 5) : '';
                    document.getElementById('edit_status').value = bus.status;

                    new bootstrap.Modal(document.getElementById('editBusModal')).show();
                };

                const list = document.getElementById('busDetailsList');
                list.innerHTML = `
                <li><span class="detail-label">Bus Number</span><span class="detail-value">${bus.bus_number}</span></li>
                <li><span class="detail-label">Bus Name</span><span class="detail-value">${bus.bus_name || '-'}</span></li>
                <li><span class="detail-label">Operator</span><span class="detail-value">${bus.operator || '-'}</span></li>
                <li><span class="detail-label">Route</span><span class="detail-value">${bus.route_from || 'N/A'} &rarr; ${bus.route_to || 'N/A'} ${bus.distance ? '(' + bus.distance + ')' : ''}</span></li>
                <li><span class="detail-label">Departure Time</span><span class="detail-value">${formatTime(bus.departure_time)}</span></li>
                <li><span class="detail-label">Arrival Time</span><span class="detail-value">${formatTime(bus.arrival_time)}</span></li>
                <li><span class="detail-label">Status</span>
                    ${bus.status
                        ? '<span class="detail-value text-success"><i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> Active</span>'
                        : '<span class="detail-value text-danger"><i class="fa-solid fa-circle me-1" style="font-size: 0.5rem;"></i> Inactive</span>'}
                </li>
                <li style="align-items: center;"><span class="detail-label">QR Code</span>
                    <div class="d-flex align-items-center gap-2">
                        <i class="fa-solid fa-qrcode" style="font-size: 2rem;"></i>
                        <button type="button" class="btn btn-sm btn-outline-primary btn-qr-action" onclick="viewQrCode('${bus.qr_code || 'BUS-'+bus.id}')"><i class="fa-solid fa-eye me-1"></i> View</button>
                        <a href="{{ url('/admin/buses') }}/${bus.id}/qr" class="btn btn-sm btn-outline-primary btn-qr-action"><i class="fa-solid fa-download me-1"></i> Download</a>
                    </div>
                </li>
                <li><span class="detail-label">Created On</span><span class="detail-value">${formatDate(bus.created_at)}</span></li>
            `;
            });
        });

        // Delete action
        document.querySelectorAll('.action-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (confirm('Are you sure you want to delete this bus?')) {
                    const id = this.getAttribute('data-id');
                    const form = document.getElementById('deleteBusForm');
                    form.action = `{{ url('/admin/buses') }}/${id}`;
                    form.submit();
                }
            });
        });
    });

    function viewQrCode(qrData) {
        const baseUrl = "{{ url('/scan') }}";
        const fullUrl = baseUrl + "/" + qrData;
        const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(fullUrl);
        document.getElementById('qrModalImage').src = qrUrl;

        // Show modal using Bootstrap API
        const viewQrModal = new bootstrap.Modal(document.getElementById('viewQrModal'));
        viewQrModal.show();
    }

    function filterDrivers() {
        const input = document.getElementById('driverSearch').value.toLowerCase();
        const items = document.querySelectorAll('.driver-list-item');

        items.forEach(item => {
            const name = item.getAttribute('data-name');
            if (name.includes(input)) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }
</script>

@endsection
