@extends('layouts.admin')

@section('title', 'Restaurant Assignments')

@section('content')
<style>
    /* Top Cards */
    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
    }
    @media (max-width: 1200px) {
        .dashboard-cards { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 768px) {
        .dashboard-cards { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card {
            padding: 12px;
            gap: 10px;
            flex-direction: column;
            text-align: center;
        }
        .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }
        .stat-val { font-size: 1.2rem; }
        .stat-label { font-size: 0.75rem; margin-bottom: 2px; }
        .stat-desc { font-size: 0.65rem; }
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
    }
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .stat-icon.purple { background: rgba(99, 102, 241, 0.1); color: #6366f1; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.orange { background: rgba(249, 115, 22, 0.1); color: #f97316; }
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .stat-info { flex: 1; }
    .stat-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-bottom: 5px; }
    .stat-val { font-size: 1.5rem; font-weight: 700; color: var(--text-main); line-height: 1.2; }
    .stat-desc { font-size: 0.75rem; color: var(--text-muted); margin-top: 4px; }
    
    /* Layout */
    .assignments-container {
        display: flex;
        gap: 20px;
        position: relative;
        overflow-x: hidden;
    }
    .main-content-area {
        flex: 1;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        transition: all 0.3s ease;
    }
    
    /* Tabs */
    .custom-tabs {
        display: flex;
        padding: 0 20px;
        border-bottom: 1px solid var(--border-color);
    }
    .custom-tab {
        padding: 15px 20px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--text-muted);
        cursor: pointer;
        border-bottom: 2px solid transparent;
    }
    .custom-tab.active {
        color: #4f46e5;
        border-bottom-color: #4f46e5;
    }
    
    /* Filters */
    .filter-bar {
        padding: 15px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        border-bottom: 1px solid var(--border-color);
        align-items: center;
    }
    .search-input-wrap { flex: 1 1 200px; position: relative; }
    .search-input-wrap i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .search-input-wrap input { width: 100%; padding: 10px 15px 10px 40px; border: 1px solid var(--border-color); border-radius: 8px; font-size: 0.9rem; }
    
    .filter-select { flex: 1 1 140px; min-width: 140px; }
    .filter-select select {
        width: 100%;
        padding: 10px 35px 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        color: var(--text-main);
    }
    .btn-reset {
        flex: 1 1 100px; justify-content: center; display: flex;
        background: white; border: 1px solid var(--border-color); padding: 10px 15px; border-radius: 8px; color: var(--text-main); font-size: 0.9rem;
    }
    
    /* Table styling */
    .table-container { overflow-x: auto; flex: 1; }
    .custom-table { width: 100%; border-collapse: collapse; }
    .custom-table th {
        background: #f8fafc; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700; padding: 12px 20px; text-align: left;
    }
    .custom-table td { padding: 15px 20px; border-bottom: 1px solid var(--border-color); vertical-align: middle; }
    
    .bus-icon-box {
        width: 40px; height: 40px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); color: #6366f1;
        display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink: 0;
    }
    
    /* Overlapping Logos */
    .overlap-group { display: flex; align-items: center; }
    .overlap-logo {
        width: 35px; height: 35px; border-radius: 50%; border: 2px solid white; margin-left: -10px;
        object-fit: cover; background: #fff; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: bold; color: #fff;
    }
    .overlap-logo:first-child { margin-left: 0; }
    .overlap-more {
        width: 35px; height: 35px; border-radius: 50%; border: 2px solid white; margin-left: -10px;
        background: #f1f5f9; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; color: var(--text-muted); font-weight: 600; z-index: 10;
    }
    
    /* Side Panel */
    .side-panel {
        width: 400px;
        background: white;
        border-radius: 12px;
        box-shadow: -5px 0 25px rgba(0,0,0,0.05);
        border: 1px solid var(--border-color);
        display: flex;
        flex-direction: column;
        position: fixed;
        top: 90px;
        right: -420px;
        bottom: 20px;
        transition: right 0.3s ease;
        z-index: 1000;
    }
    .side-panel.open { right: 20px; }
    
    .panel-header {
        padding: 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;
    }
    .panel-title { font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0; }
    .btn-close-panel { cursor: pointer; color: var(--text-muted); font-size: 1.2rem; border: none; background: transparent; }
    
    .panel-body { padding: 20px; overflow-y: auto; flex: 1; }
    
    .selected-bus-card {
        background: #f8fafc; border-radius: 10px; padding: 15px; border: 1px solid #e2e8f0; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: flex-start;
    }
    .bus-info-grid {
        display: grid; grid-template-columns: 100px 1fr; gap: 10px 5px; font-size: 0.85rem; margin-bottom: 20px;
    }
    .info-lbl { color: var(--text-muted); }
    .info-val { font-weight: 500; color: var(--text-main); text-align: right; }
    
    .restaurant-list-item {
        display: flex; align-items: center; gap: 15px; padding: 12px 0; border-bottom: 1px solid #f1f5f9; cursor: pointer;
    }
    .restaurant-list-item:hover { background: #f8fafc; }
    
    /* Custom Checkbox */
    .custom-check {
        width: 20px; height: 20px; border-radius: 4px; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center;
        transition: all 0.2s; color: transparent;
    }
    .restaurant-list-item input[type="checkbox"] { display: none; }
    .restaurant-list-item input[type="checkbox"]:checked + .custom-check {
        background: #4f46e5; border-color: #4f46e5; color: white;
    }
    
    .panel-footer {
        padding: 15px 20px; border-top: 1px solid var(--border-color); display: flex; gap: 10px;
    }
    .btn-save-panel {
        flex: 1; background: #4f46e5; color: white; border: none; border-radius: 8px; padding: 12px; font-weight: 500; transition: background 0.2s;
    }
    .btn-save-panel:hover { background: #4338ca; }
    .btn-cancel-panel {
        flex: 1; background: white; color: var(--text-main); border: 1px solid var(--border-color); border-radius: 8px; padding: 12px; font-weight: 500; transition: background 0.2s;
    }
    .btn-cancel-panel:hover { background: #f8fafc; }
    
    .status-badge {
        padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: 600;
    }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-inactive { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .btn-action { width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; color: #64748b; background: #f1f5f9; transition: 0.2s; cursor: pointer; border: none; }
    .btn-action:hover { background: #e2e8f0; color: #4f46e5; }
    
    /* Overlay for mobile/desktop side panel */
    .panel-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.3); z-index: 999; display: none;
    }
    .panel-overlay.show { display: block; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title" style="font-size: 1.5rem; font-weight: 700; margin: 0;">Restaurant Assignments</h1>
        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 5px;">
            Home > Restaurant Assignments
        </div>
    </div>
</div>

<div class="dashboard-cards">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-bus"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Buses</div>
            <div class="stat-val">{{ number_format($totalBuses) }}</div>
            <div class="stat-desc">View all buses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-store"></i></div>
        <div class="stat-info">
            <div class="stat-label">Assigned Restaurants</div>
            <div class="stat-val">{{ number_format($assignedRestaurants) }}</div>
            <div class="stat-desc">Across all buses</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-link"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Assignments</div>
            <div class="stat-val">{{ number_format($totalAssignments) }}</div>
            <div class="stat-desc">View all assignments</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-chart-line"></i></div>
        <div class="stat-info">
            <div class="stat-label">Active Assignments</div>
            <div class="stat-val">{{ number_format($activeAssignments) }}</div>
            <div class="stat-desc">
                @if($totalAssignments > 0)
                    {{ round(($activeAssignments / $totalAssignments) * 100, 1) }}% of total
                @else
                    0% of total
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 8px;">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="assignments-container">
    <div class="main-content-area" id="mainArea">
        <div class="custom-tabs">
            <a href="?tab=bus" class="custom-tab {{ $tab == 'bus' ? 'active' : '' }}" style="text-decoration: none;">Bus-wise Assignments</a>
            <a href="?tab=restaurant" class="custom-tab {{ $tab == 'restaurant' ? 'active' : '' }}" style="text-decoration: none;">Restaurant-wise Assignments</a>
        </div>
        
        <form method="GET" action="{{ route('admin.restaurants.assign') }}" class="filter-bar">
            <input type="hidden" name="tab" value="{{ $tab }}">
            <div class="search-input-wrap">
                <i class="fa-solid fa-search"></i>
                <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}" onkeypress="if(event.keyCode==13) this.form.submit();">
            </div>
            
            @if($tab == 'bus')
            <div class="filter-select">
                <select name="route" onchange="this.form.submit()">
                    <option value="">All Routes</option>
                    @foreach($allRoutes as $routeOption)
                        <option value="{{ $routeOption->id }}" {{ request('route') == $routeOption->id ? 'selected' : '' }}>{{ $routeOption->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="filter-select">
                <select name="operator" onchange="this.form.submit()">
                    <option value="">All Operators</option>
                    @foreach($allOperators as $op)
                        <option value="{{ $op }}" {{ request('operator') == $op ? 'selected' : '' }}>{{ $op }}</option>
                    @endforeach
                </select>
            </div>
            @endif
            
            <div class="filter-select">
                <select name="status" onchange="this.form.submit()">
                    <option value="">All Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
            </div>
            <button type="button" class="btn-reset" onclick="window.location='{{ route('admin.restaurants.assign', ['tab' => $tab]) }}'"><i class="fa-solid fa-rotate-right me-1"></i> Reset</button>
        </form>
        
        <div class="table-container">
            <table class="custom-table">
                @if($tab == 'bus')
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Bus Details</th>
                        <th>Route</th>
                        <th>Operator</th>
                        <th>Driver</th>
                        <th>Assigned Restaurants</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($buses as $index => $bus)
                        @php
                            $assignedRests = $bus->restaurants;
                            $count = $assignedRests->count();
                            $displayLimit = 4;
                            
                            $bStatusClass = $bus->status == 1 ? 'status-active' : 'status-inactive';
                            $bStatusText = $bus->status == 1 ? 'Active' : 'Inactive';
                            
                            $routeName = '';
                            if($bus->busRoute) {
                                $routeName = $bus->busRoute->source . ' → ' . $bus->busRoute->destination;
                            } else {
                                $routeName = $bus->route_from . ' → ' . $bus->route_to;
                            }
                        @endphp
                        <tr>
                            <td>{{ $buses->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bus-icon-box"><i class="fa-solid fa-bus"></i></div>
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-main);">{{ $bus->bus_number }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ $bus->bus_name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.85rem; color: var(--text-main);">
                                    @if($bus->busRoute)
                                        {{ $bus->busRoute->source }} →<br>{{ $bus->busRoute->destination }}
                                    @else
                                        {{ $bus->route_from }} →<br>{{ $bus->route_to }}
                                    @endif
                                </div>
                            </td>
                            <td style="font-size: 0.85rem;">{{ $bus->operator }}</td>
                            <td>
                                @if($bus->drivers->count() > 0)
                                    <div style="display: flex; flex-direction: column; gap: 4px;">
                                        @foreach($bus->drivers as $driver)
                                            @if($driver->user)
                                                <div>
                                                    <div style="font-weight: 600; color: var(--text-main); font-size: 0.85rem;">{{ $driver->user->name }}</div>
                                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $driver->user->mobile }}</div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Unassigned</span>
                                @endif
                            </td>
                            <td>
                                <div class="overlap-group">
                                    @foreach($assignedRests->take($displayLimit) as $i => $r)
                                        @php
                                            $colors = ['#1e293b', '#b91c1c', '#b45309', '#15803d'];
                                            $bg = $colors[$i % count($colors)];
                                        @endphp
                                        @if($r->logo)
                                            <img src="{{ asset('storage/'.$r->logo) }}" class="overlap-logo" title="{{ $r->restaurant_name }}" style="z-index: {{ 10 - $i }};">
                                        @else
                                            <div class="overlap-logo" style="background: {{ $bg }}; z-index: {{ 10 - $i }};" title="{{ $r->restaurant_name }}">
                                                {{ substr($r->restaurant_name, 0, 1) }}
                                            </div>
                                        @endif
                                    @endforeach
                                    @if($count > $displayLimit)
                                        <div class="overlap-more">+{{ $count - $displayLimit }}</div>
                                    @endif
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">{{ $count }} Restaurants</div>
                            </td>
                            <td><span class="status-badge {{ $bStatusClass }}">{{ $bStatusText }}</span></td>
                            <td>
                                <button class="btn-action" onclick="openAssignPanel({{ $bus->id }}, '{{ $bus->bus_number }}', '{{ addslashes($bus->bus_name) }}', '{{ addslashes($bus->operator) }}', '{{ addslashes($routeName) }}', '{{ $bStatusText }}', '{{ $count }}', {{ $assignedRests->pluck('id') }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">No buses found.</td>
                        </tr>
                    @endforelse
                </tbody>
                @else
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Restaurant Details</th>
                        <th>Location</th>
                        <th>Assigned Buses</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($restaurantsList as $index => $restaurant)
                        @php
                            $assignedBuses = $restaurant->buses;
                            $count = $assignedBuses->count();
                            $displayLimit = 4;
                            
                            $rStatusClass = $restaurant->status == 1 ? 'status-active' : 'status-inactive';
                            $rStatusText = $restaurant->status == 1 ? 'Active' : 'Inactive';
                        @endphp
                        <tr>
                            <td>{{ $restaurantsList->firstItem() + $index }}</td>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($restaurant->logo)
                                        <img src="{{ asset('storage/'.$restaurant->logo) }}" style="width: 40px; height: 40px; border-radius: 8px; object-fit: cover;">
                                    @else
                                        <div class="bus-icon-box" style="background: rgba(249, 115, 22, 0.1); color: #f97316;">
                                            {{ substr($restaurant->restaurant_name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: var(--text-main);">{{ $restaurant->restaurant_name }}</div>
                                        <div style="font-size: 0.8rem; color: var(--text-muted);">{{ Str::limit($restaurant->cuisines, 30) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td style="font-size: 0.85rem;" title="{{ $restaurant->address }}">{{ $restaurant->address ? Str::limit($restaurant->address, 40) : 'N/A' }}</td>
                            <td>
                                <div class="overlap-group">
                                    @foreach($assignedBuses->take($displayLimit) as $i => $b)
                                        <div class="overlap-logo" style="background: #1e293b; z-index: {{ 10 - $i }}; font-size: 0.6rem;" title="{{ $b->bus_number }}">
                                            <i class="fa-solid fa-bus"></i>
                                        </div>
                                    @endforeach
                                    @if($count > $displayLimit)
                                        <div class="overlap-more">+{{ $count - $displayLimit }}</div>
                                    @endif
                                </div>
                                <div style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">{{ $count }} Buses</div>
                            </td>
                            <td><span class="status-badge {{ $rStatusClass }}">{{ $rStatusText }}</span></td>
                            <td>
                                <button class="btn-action" onclick="openRestAssignPanel({{ $restaurant->id }}, '{{ addslashes($restaurant->restaurant_name) }}', '{{ $rStatusText }}', '{{ $count }}', {{ $assignedBuses->pluck('id') }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No restaurants found.</td>
                        </tr>
                    @endforelse
                </tbody>
                @endif
            </table>
            
            <div class="p-3" style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted);">
                @if($tab == 'bus')
                    <div>Showing {{ $buses->firstItem() ?? 0 }} to {{ $buses->lastItem() ?? 0 }} of {{ $buses->total() }} buses</div>
                    <div>{{ $buses->appends(['tab' => 'bus', 'search' => request('search'), 'route' => request('route'), 'operator' => request('operator'), 'status' => request('status')])->links('pagination::bootstrap-4') }}</div>
                @else
                    <div>Showing {{ $restaurantsList->firstItem() ?? 0 }} to {{ $restaurantsList->lastItem() ?? 0 }} of {{ $restaurantsList->total() }} restaurants</div>
                    <div>{{ $restaurantsList->appends(['tab' => 'restaurant', 'search' => request('search'), 'status' => request('status')])->links('pagination::bootstrap-4') }}</div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="panel-overlay" id="panelOverlay" onclick="closeAllPanels()"></div>

<!-- Slide-out Side Panel for Bus -->
<div class="side-panel" id="assignPanel">
    <div class="panel-header">
        <h3 class="panel-title">Restaurant Assignment</h3>
        <button class="btn-close-panel" onclick="closeAllPanels()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="panel-body">
        <div class="selected-bus-card">
            <div class="d-flex gap-3 align-items-center">
                <div class="bus-icon-box"><i class="fa-solid fa-bus"></i></div>
                <div>
                    <div style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;" id="panelBusNumber">TS09AB1234</div>
                    <div style="font-size: 0.8rem; color: var(--text-muted);" id="panelBusName">Volvo 9600</div>
                </div>
            </div>
            <span class="status-badge status-active" id="panelBusStatus">Active</span>
        </div>
        
        <div class="bus-info-grid">
            <div class="info-lbl">Route</div>
            <div class="info-val" id="panelBusRoute">Hyd → Vizag</div>
            
            <div class="info-lbl">Operator</div>
            <div class="info-val" id="panelBusOperator">VRL Travels</div>
            
            <div class="info-lbl">Total Restaurants Assigned</div>
            <div class="info-val" id="panelBusCount">4</div>
        </div>
        
        <h6 style="font-weight: 600; font-size: 0.9rem; margin-bottom: 15px; margin-top: 10px;">Assign Restaurants</h6>
        
        <div style="position: relative; margin-bottom: 15px;">
            <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
            <input type="text" id="restaurantSearch" placeholder="Search restaurant by name..." style="width: 100%; padding: 8px 12px 8px 35px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; outline: none;" onkeyup="filterRestaurants()">
        </div>
        
        <form id="assignmentForm" action="{{ route('admin.restaurants.assign.sync') }}" method="POST">
            @csrf
            <input type="hidden" name="bus_id" id="formBusId">
            
            <div id="restaurantListContainer">
                @foreach($allRestaurants as $r)
                    <label class="restaurant-list-item r-item" data-name="{{ strtolower($r->restaurant_name) }}">
                        <input type="checkbox" name="restaurant_ids[]" value="{{ $r->id }}" class="rest-checkbox">
                        <div class="custom-check"><i class="fa-solid fa-check" style="font-size: 0.7rem;"></i></div>
                        
                        @if($r->logo)
                            <img src="{{ asset('storage/'.$r->logo) }}" style="width: 35px; height: 35px; border-radius: 50%; object-fit: cover;">
                        @else
                            <div style="width: 35px; height: 35px; border-radius: 50%; background: #1e293b; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: bold;">
                                {{ substr($r->restaurant_name, 0, 1) }}
                            </div>
                        @endif
                        
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-main);">{{ $r->restaurant_name }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">{{ Str::limit($r->cuisines, 30) }}</div>
                        </div>
                        
                        <span class="status-badge status-active" style="font-size: 0.65rem;">Active</span>
                    </label>
                @endforeach
            </div>
    </div>
    <div class="panel-footer">
        <button type="button" class="btn-cancel-panel" onclick="closeAllPanels()">Cancel</button>
        <button type="submit" class="btn-save-panel">Save Assignment</button>
    </div>
    </form>
</div>

<!-- Slide-out Side Panel for Restaurant -->
<div class="side-panel" id="restAssignPanel">
    <div class="panel-header">
        <h3 class="panel-title">Bus Assignment</h3>
        <button class="btn-close-panel" onclick="closeAllPanels()"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <div class="panel-body">
        <div class="selected-bus-card">
            <div class="d-flex gap-3 align-items-center">
                <div class="bus-icon-box" style="background: rgba(16, 185, 129, 0.1); color: #10b981;"><i class="fa-solid fa-store"></i></div>
                <div>
                    <div style="font-weight: 700; color: var(--text-main); font-size: 1.1rem;" id="panelRestName">Restaurant Name</div>
                </div>
            </div>
            <span class="status-badge status-active" id="panelRestStatus">Active</span>
        </div>
        
        <div class="bus-info-grid">
            <div class="info-lbl">Total Buses Assigned</div>
            <div class="info-val" id="panelRestCount">0</div>
        </div>
        
        <h6 style="font-weight: 600; font-size: 0.9rem; margin-bottom: 15px; margin-top: 10px;">Assign Buses</h6>
        
        <div style="position: relative; margin-bottom: 15px;">
            <i class="fa-solid fa-search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #94a3b8; font-size: 0.8rem;"></i>
            <input type="text" id="busSearch" placeholder="Search bus by number or name..." style="width: 100%; padding: 8px 12px 8px 35px; border: 1px solid var(--border-color); border-radius: 6px; font-size: 0.85rem; outline: none;" onkeyup="filterBuses()">
        </div>
        
        <form id="restAssignmentForm" action="{{ route('admin.restaurants.assign.syncRestaurant') }}" method="POST">
            @csrf
            <input type="hidden" name="restaurant_id" id="formRestId">
            
            <div id="busListContainer">
                @if(isset($allBuses))
                @foreach($allBuses as $b)
                    <label class="restaurant-list-item b-item" data-name="{{ strtolower($b->bus_number . ' ' . $b->bus_name) }}">
                        <input type="checkbox" name="bus_ids[]" value="{{ $b->id }}" class="bus-checkbox">
                        <div class="custom-check"><i class="fa-solid fa-check" style="font-size: 0.7rem;"></i></div>
                        
                        <div style="width: 35px; height: 35px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 0.9rem;">
                            <i class="fa-solid fa-bus"></i>
                        </div>
                        
                        <div style="flex: 1;">
                            <div style="font-weight: 600; font-size: 0.85rem; color: var(--text-main);">{{ $b->bus_number }}</div>
                            <div style="font-size: 0.7rem; color: var(--text-muted);">{{ Str::limit($b->bus_name, 30) }}</div>
                        </div>
                    </label>
                @endforeach
                @endif
            </div>
    </div>
    <div class="panel-footer">
        <button type="button" class="btn-cancel-panel" onclick="closeAllPanels()">Cancel</button>
        <button type="submit" class="btn-save-panel">Save Assignment</button>
    </div>
    </form>
</div>

<script>
    function openAssignPanel(id, number, name, operator, route, status, count, assignedIds) {
        document.getElementById('formBusId').value = id;
        document.getElementById('panelBusNumber').innerText = number;
        document.getElementById('panelBusName').innerText = name;
        document.getElementById('panelBusOperator').innerText = operator;
        document.getElementById('panelBusRoute').innerText = route;
        document.getElementById('panelBusCount').innerText = count;
        
        let statusBadge = document.getElementById('panelBusStatus');
        statusBadge.innerText = status;
        statusBadge.className = status === 'Active' ? 'status-badge status-active' : 'status-badge status-inactive';
        
        document.querySelectorAll('.rest-checkbox').forEach(cb => cb.checked = false);
        if (assignedIds && assignedIds.length > 0) {
            assignedIds.forEach(rId => {
                let cb = document.querySelector(`.rest-checkbox[value="${rId}"]`);
                if(cb) cb.checked = true;
            });
        }
        
        document.getElementById('assignPanel').classList.add('open');
        document.getElementById('panelOverlay').classList.add('show');
        document.getElementById('mainArea').style.marginRight = "10px";
    }

    function openRestAssignPanel(id, name, status, count, assignedIds) {
        document.getElementById('formRestId').value = id;
        document.getElementById('panelRestName').innerText = name;
        document.getElementById('panelRestCount').innerText = count;
        
        let statusBadge = document.getElementById('panelRestStatus');
        statusBadge.innerText = status;
        statusBadge.className = status === 'Active' ? 'status-badge status-active' : 'status-badge status-inactive';
        
        document.querySelectorAll('.bus-checkbox').forEach(cb => cb.checked = false);
        if (assignedIds && assignedIds.length > 0) {
            assignedIds.forEach(bId => {
                let cb = document.querySelector(`.bus-checkbox[value="${bId}"]`);
                if(cb) cb.checked = true;
            });
        }
        
        document.getElementById('restAssignPanel').classList.add('open');
        document.getElementById('panelOverlay').classList.add('show');
        document.getElementById('mainArea').style.marginRight = "10px";
    }
    
    function closeAllPanels() {
        document.getElementById('assignPanel').classList.remove('open');
        document.getElementById('restAssignPanel').classList.remove('open');
        document.getElementById('panelOverlay').classList.remove('show');
        document.getElementById('mainArea').style.marginRight = "0";
    }
    
    function filterRestaurants() {
        let input = document.getElementById('restaurantSearch').value.toLowerCase();
        document.querySelectorAll('.r-item').forEach(item => {
            item.style.display = item.getAttribute('data-name').includes(input) ? 'flex' : 'none';
        });
    }

    function filterBuses() {
        let input = document.getElementById('busSearch').value.toLowerCase();
        document.querySelectorAll('.b-item').forEach(item => {
            item.style.display = item.getAttribute('data-name').includes(input) ? 'flex' : 'none';
        });
    }
</script>
@endsection
