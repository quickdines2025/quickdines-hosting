@extends('layouts.admin')

@section('title', 'Route Management')

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
        text-transform: uppercase;
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
        color: var(--green);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .stat-trend span { color: var(--text-muted); }

    /* Layout Grid */
    .layout-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    @media (max-width: 1200px) {
        .layout-grid { grid-template-columns: 1fr; }
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: 1fr 1fr; }
    }

    /* Panels */
    .panel {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        margin-bottom: 24px;
        overflow: hidden;
    }
    .panel-header {
        padding: 16px 20px;
        border-bottom: 1px solid var(--border-color);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: rgba(0,0,0,0.01);
    }
    .panel-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }
    .panel-body { padding: 20px; }

    /* Table Styles */
    .table-responsive { overflow-x: auto; }
    .custom-table {
        width: 100%;
        border-collapse: collapse;
    }
    .custom-table th {
        text-align: left;
        padding: 12px 16px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    .custom-table td {
        padding: 16px;
        font-size: 0.85rem;
        color: var(--text-main);
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .custom-table tr:hover { background-color: rgba(0,0,0,0.01); }

    /* Route Name Cell */
    .route-name { font-weight: 700; display: block; margin-bottom: 2px; }
    .route-id { font-size: 0.75rem; color: var(--text-muted); }

    /* City Cell */
    .city-name { font-weight: 600; display: block; margin-bottom: 2px; }
    .state-name { font-size: 0.75rem; color: var(--text-muted); }

    /* Bus Count */
    .bus-count-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 8px;
        background: var(--green-light);
        color: var(--green);
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.75rem;
    }
    .bus-count-badge i { font-size: 0.9rem; }

    /* Status Badge */
    .status-badge {
        display: inline-block;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-active { background: rgba(34, 197, 94, 0.1); color: #22c55e; }
    .status-inactive { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    /* Action Buttons */
    .action-btn {
        width: 28px;
        height: 28px;
        border-radius: 6px;
        border: 1px solid var(--border-color);
        background: white;
        color: var(--text-muted);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        margin-right: 4px;
    }
    .action-btn:hover { background: var(--bg-body); color: var(--primary-color, #4f46e5); }
    .action-btn.delete:hover { color: #ef4444; border-color: #fca5a5; }

    /* Filters */
    .filters-bar {
        display: flex;
        gap: 12px;
        padding: 16px;
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        margin-bottom: 16px;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1;
        min-width: 250px;
        position: relative;
    }
    .search-input i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
    }
    .search-input input {
        width: 100%;
        padding: 8px 12px 8px 36px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.85rem;
        background: var(--bg-body);
        color: var(--text-main);
    }
    .filter-select {
        padding: 8px 32px 8px 12px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.85rem;
        background: var(--bg-body);
        color: var(--text-main);
        min-width: 150px;
    }

    /* Charts & Lists (Right Sidebar) */
    .donut-container {
        position: relative;
        width: 150px;
        height: 150px;
        margin: 0 auto 20px;
    }
    .donut-inner {
        position: absolute;
        top: 50%; left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }
    .donut-inner h3 { font-size: 1.5rem; font-weight: 700; margin: 0; color: var(--text-main); }
    .donut-inner p { font-size: 0.65rem; color: var(--text-muted); margin: 0; text-transform: uppercase; font-weight: 600; }

    .legend-item { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 8px; align-items: center; }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 8px; }

    /* Progress List */
    .progress-list-item { margin-bottom: 16px; }
    .progress-header { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 6px; font-weight: 600; }
    .progress-header .orders { color: var(--text-muted); font-weight: 400; }
    .progress-bar-bg { height: 6px; background: var(--border-color); border-radius: 3px; overflow: hidden; }
    .progress-bar-fill { height: 100%; border-radius: 3px; }

    /* Tags */
    .city-tag {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px dashed var(--border-color);
        font-size: 0.85rem;
        color: var(--text-main);
    }
    .city-tag:last-child { border-bottom: none; }
    .city-routes {
        font-size: 0.75rem;
        color: var(--blue);
        background: var(--blue-light);
        padding: 2px 8px;
        border-radius: 12px;
        font-weight: 600;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Route Management</h1>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">Home</a> <span>&gt;</span> Route Management
        </div>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRouteModal" style="background: var(--primary-color, #4f46e5); border: none; font-weight: 500; padding: 8px 16px; border-radius: 8px;">
        <i class="fa-solid fa-plus me-1"></i> Add New Route
    </button>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px; border: none; background: rgba(34, 197, 94, 0.1); color: #22c55e;">
    <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-route"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total Routes</div>
            <div class="stat-value">{{ $totalRoutes }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-bus"></i></div>
        <div class="stat-content">
            <div class="stat-label">Active Routes</div>
            <div class="stat-value">{{ $activeRoutes }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-store"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total Buses</div>
            <div class="stat-value">{{ $totalBuses }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-city"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total Cities</div>
            <div class="stat-value">{{ $totalCities }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pink"><i class="fa-solid fa-map-location-dot"></i></div>
        <div class="stat-content">
            <div class="stat-label">Popular Route</div>
            <div class="stat-value" style="font-size: 0.95rem; line-height: 1.2;">{{ $popularRoute ? $popularRoute->name : 'N/A' }}</div>
            <div class="stat-trend" style="color: var(--text-muted);">Most buses this week</div>
        </div>
    </div>
</div>

<div class="layout-grid">
    <!-- Left Column: Table -->
    <div>
        <form method="GET" action="{{ route('admin.bus-routes.index') }}" class="filters-bar">
            <div class="search-input">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" placeholder="Search by route, source, destination..." value="{{ request('search') }}">
            </div>
            <select name="status" class="filter-select">
                <option value="">All Status</option>
                <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
                <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
            </select>
            <select name="source" class="filter-select">
                <option value="">All Source Cities</option>
                @foreach($sources as $src)
                    <option value="{{ $src }}" {{ request('source') == $src ? 'selected' : '' }}>{{ $src }}</option>
                @endforeach
            </select>
            <select name="destination" class="filter-select">
                <option value="">All Destination Cities</option>
                @foreach($destinations as $dst)
                    <option value="{{ $dst }}" {{ request('destination') == $dst ? 'selected' : '' }}>{{ $dst }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-light" style="border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem;"><i class="fa-solid fa-filter me-1"></i> Filter</button>
            <a href="{{ route('admin.bus-routes.index') }}" class="btn btn-light" style="border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem;">Reset</a>
        </form>

        <div class="panel">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Route Name</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Distance</th>
                            <th>Buses</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($routes as $index => $route)
                            <tr>
                                <td>{{ $routes->firstItem() + $index }}</td>
                                <td>
                                    <span class="route-name">{{ $route->name }}</span>
                                    <span class="route-id">RT-{{ str_pad($route->id, 3, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>
                                    <span class="city-name">{{ $route->source }}</span>
                                </td>
                                <td>
                                    <span class="city-name">{{ $route->destination }}</span>
                                </td>
                                <td>{{ $route->distance ?: '-' }}</td>
                                <td>
                                    <div class="bus-count-badge">
                                        <i class="fa-solid fa-bus"></i> {{ $route->buses_count }} Buses
                                    </div>
                                </td>
                                <td>
                                    @if($route->status)
                                        <span class="status-badge status-active">Active</span>
                                    @else
                                        <span class="status-badge status-inactive">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.bus-routes.show', $route->id) }}" class="action-btn" title="Manage Stops">
                                        <i class="fa-solid fa-map-location-dot"></i>
                                    </a>
                                    <button class="action-btn" data-bs-toggle="modal" data-bs-target="#editRouteModal" onclick="populateEditModal({{ $route }})" title="Edit">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <form action="{{ route('admin.bus-routes.destroy', $route->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-btn delete" title="Delete">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">No routes found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3" style="border-top: 1px solid var(--border-color);">
                {{ $routes->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>

    <!-- Right Column: Sidebar Panels -->
    <div>
        <!-- Donut Chart Panel -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Route Overview</h3>
            </div>
            <div class="panel-body">
                <div class="donut-container">
                    <canvas id="routeOverviewChart"></canvas>
                    <div class="donut-inner">
                        <h3>{{ $totalRoutes }}</h3>
                        <p>Total Routes</p>
                    </div>
                </div>
                <div style="padding: 0 10px;">
                    <div class="legend-item">
                        <div><span class="legend-dot" style="background: var(--green);"></span> Active Routes</div>
                        <div style="font-weight: 700;">{{ $activeRoutes }} <span style="color: var(--text-muted); font-weight: 400; font-size: 0.75rem;">({{ $totalRoutes > 0 ? round(($activeRoutes/$totalRoutes)*100, 1) : 0 }}%)</span></div>
                    </div>
                    <div class="legend-item">
                        <div><span class="legend-dot" style="background: var(--pink);"></span> Inactive Routes</div>
                        <div style="font-weight: 700;">{{ $inactiveRoutes }} <span style="color: var(--text-muted); font-weight: 400; font-size: 0.75rem;">({{ $totalRoutes > 0 ? round(($inactiveRoutes/$totalRoutes)*100, 1) : 0 }}%)</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Busiest Routes Panel -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Top Busiest Routes</h3>
            </div>
            <div class="panel-body">
                @php
                    $colors = ['#4f46e5', '#3b82f6', '#f59e0b', '#22c55e', '#ec4899'];
                    $maxBuses = $busiestRoutes->max('buses_count') ?: 1;
                @endphp
                @forelse($busiestRoutes as $idx => $rt)
                <div class="progress-list-item">
                    <div class="progress-header">
                        <div>{{ $idx + 1 }}. {{ $rt->name }}</div>
                        <div class="orders">{{ $rt->buses_count }} Buses</div>
                    </div>
                    <div class="progress-bar-bg">
                        <div class="progress-bar-fill" style="width: {{ ($rt->buses_count / $maxBuses) * 100 }}%; background: {{ $colors[$idx % 5] }};"></div>
                    </div>
                </div>
                @empty
                    <div class="text-muted text-center" style="font-size: 0.85rem;">No routes data available.</div>
                @endforelse
            </div>
        </div>

        <!-- Top Cities Panel -->
        <div class="panel">
            <div class="panel-header">
                <h3 class="panel-title">Top Cities Connected</h3>
            </div>
            <div class="panel-body py-2">
                @forelse($topCities as $city)
                    <div class="city-tag">
                        <span>{{ $city['name'] }}</span>
                        <span class="city-routes">{{ $city['count'] }} Routes</span>
                    </div>
                @empty
                    <div class="text-muted text-center py-2" style="font-size: 0.85rem;">No cities connected yet.</div>
                @endforelse
                <div class="text-center mt-3 mb-2">
                    <a href="#" style="font-size: 0.8rem; color: var(--primary-color, #4f46e5); text-decoration: none; font-weight: 600;">View all cities &rarr;</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Route Modal -->
<div class="modal fade" id="addRouteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" style="font-weight: 600;">Add New Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.bus-routes.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Source City *</label>
                        <input type="text" name="source" class="form-control" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Destination City *</label>
                        <input type="text" name="destination" class="form-control" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Distance (e.g. 628 km)</label>
                        <input type="text" name="distance" class="form-control" style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Assign Bus (Optional)</label>
                        <select name="bus_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- No Bus (Unassigned) --</option>
                            @foreach($allBuses as $bus)
                                @php
                                    $driverName = $bus->drivers->count() > 0
                                        ? implode(', ', $bus->drivers->map(function($d) { return $d->user ? $d->user->name : null; })->filter()->toArray())
                                        : 'No Driver';
                                    if(empty($driverName)) $driverName = 'No Driver';
                                @endphp
                                <option value="{{ $bus->id }}" {{ $bus->bus_route_id ? 'disabled' : '' }}>
                                    {{ $bus->bus_number }} ({{ $driverName }}) {{ $bus->bus_route_id ? '- Already Assigned' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Status *</label>
                        <select name="status" class="form-select" required style="border-radius: 8px;">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary-color, #4f46e5); border: none;">Save Route</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Route Modal -->
<div class="modal fade" id="editRouteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" style="border-radius: 12px; border: none;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color);">
                <h5 class="modal-title" style="font-weight: 600;">Edit Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editRouteForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Source City *</label>
                        <input type="text" name="source" id="edit_source" class="form-control" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Destination City *</label>
                        <input type="text" name="destination" id="edit_destination" class="form-control" required style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Distance</label>
                        <input type="text" name="distance" id="edit_distance" class="form-control" style="border-radius: 8px;">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Assign Bus (Optional)</label>
                        <select name="bus_id" id="edit_bus_id" class="form-select" style="border-radius: 8px;">
                            <option value="">-- No Bus (Unassigned) --</option>
                            @foreach($allBuses as $bus)
                                @php
                                    $driverName = $bus->drivers->count() > 0
                                        ? implode(', ', $bus->drivers->map(function($d) { return $d->user ? $d->user->name : null; })->filter()->toArray())
                                        : 'No Driver';
                                    if(empty($driverName)) $driverName = 'No Driver';
                                @endphp
                                <option value="{{ $bus->id }}" {{ $bus->bus_route_id ? 'data-assigned="true"' : '' }}>
                                    {{ $bus->bus_number }} ({{ $driverName }}) {{ $bus->bus_route_id ? '- Already Assigned' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" style="font-size: 0.85rem; font-weight: 500;">Status *</label>
                        <select name="status" id="edit_status" class="form-select" required style="border-radius: 8px;">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--border-color);">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background: var(--primary-color, #4f46e5); border: none;">Update Route</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function populateEditModal(route) {
        document.getElementById('editRouteForm').action = `/admin/bus-routes/${route.id}`;
        document.getElementById('edit_source').value = route.source;
        document.getElementById('edit_destination').value = route.destination;
        document.getElementById('edit_distance').value = route.distance || '';
        document.getElementById('edit_status').value = route.status;

        let busId = '';
        if (route.buses && route.buses.length > 0) {
            busId = route.buses[0].id;
        }
        document.getElementById('edit_bus_id').value = busId;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('routeOverviewChart').getContext('2d');
        const active = {{ $activeRoutes }};
        const inactive = {{ $inactiveRoutes }};

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Active', 'Inactive'],
                datasets: [{
                    data: [active, inactive],
                    backgroundColor: ['#22c55e', '#ec4899'],
                    borderWidth: 0,
                    cutout: '80%'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { display: false },
                    tooltip: { enabled: true }
                }
            }
        });
    });
</script>
@endsection
