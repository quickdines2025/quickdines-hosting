@extends('layouts.admin')

@section('title', 'Restaurants')

@section('content')
<style>
    /* Styling for the Restaurants Dashboard */
    .page-header {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }
    .breadcrumb-custom {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .breadcrumb-custom a {
        color: var(--text-muted);
        text-decoration: none;
    }
    .breadcrumb-custom a:hover {
        color: var(--blue);
    }
    .breadcrumb-custom span {
        margin: 0 8px;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 20px;
        margin-bottom: 30px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; margin-bottom: 20px;}
        .stat-card {
            padding: 12px;
            gap: 10px;
            flex-direction: column;
            text-align: center;
            align-items: center;
        }
        .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }
        .stat-value { font-size: 1.2rem; margin-bottom: 2px;}
        .stat-label { font-size: 0.7rem; margin-bottom: 2px;}
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: flex-start;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }
    .stat-icon.purple { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }
    .stat-icon.green { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.orange { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.red { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-icon.blue { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    
    .stat-content {
        flex: 1;
    }
    .stat-label {
        font-size: 0.8rem;
        color: var(--text-muted);
        font-weight: 500;
        margin-bottom: 5px;
    }
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 5px;
    }
    .stat-trend {
        font-size: 0.75rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .stat-trend.up { color: #10b981; }
    .stat-trend.down { color: #ef4444; }
    .stat-trend.neutral { color: #f59e0b; }
    .stat-trend-text { color: var(--text-muted); font-weight: 400; margin-left: 4px; }

    /* Filters */
    .filter-bar {
        background: white;
        border-radius: 12px;
        padding: 15px 20px;
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
    }
    .search-input {
        flex: 1 1 200px;
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
        padding: 10px 10px 10px 40px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .search-input input:focus { border-color: var(--blue); }
    
    .filter-select { flex: 1 1 140px; min-width: 140px; }
    .filter-select select {
        width: 100%;
        padding: 10px 15px;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        font-size: 0.9rem;
        color: var(--text-main);
        outline: none;
        appearance: none;
        background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="%236b7280" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>') no-repeat right 15px center;
        cursor: pointer;
    }
    .filter-action-btn {
        flex: 1 1 100px;
        justify-content: center;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
    }
    .filter-action-btn[type="submit"] {
        background: white;
        border: 1px solid var(--border-color);
        color: var(--text-main);
    }
    .filter-action-btn[type="submit"]:hover { background: #f9fafb; }
    .filter-action-btn.reset {
        background: transparent;
        border: none;
        color: var(--text-muted);
    }

    /* Main Table Container */
    .restaurants-container {
        display: flex;
        gap: 20px;
    }
    .main-table-wrapper {
        flex: 1;
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        overflow-x: auto;
    }
    .sidebar-wrapper {
        width: 320px;
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    /* Table Styles */
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom th {
        text-align: left;
        padding: 15px 20px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--text-muted);
        font-weight: 600;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    .table-custom td {
        padding: 15px 20px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
    }
    .table-custom tr:last-child td { border-bottom: none; }
    .table-custom tbody tr:hover { background: #fcfcfc; }
    
    .restaurant-detail-col {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .restaurant-logo {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        object-fit: cover;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--text-muted);
    }
    .restaurant-name { font-weight: 600; color: var(--text-main); font-size: 0.95rem; margin-bottom: 2px;}
    .restaurant-rating { font-size: 0.75rem; color: var(--text-muted); display: flex; align-items: center; gap: 4px; }
    .restaurant-rating i { color: #f59e0b; }
    
    .owner-name { font-weight: 500; color: var(--text-main); font-size: 0.85rem; margin-bottom: 2px; }
    .owner-mobile { font-size: 0.8rem; color: var(--text-muted); }

    .city-text { font-size: 0.85rem; font-weight: 500; color: var(--text-main); }
    
    .category-tags { display: flex; gap: 6px; flex-wrap: wrap; }
    .category-tag {
        font-size: 0.7rem;
        padding: 2px 8px;
        border-radius: 12px;
        background: #f3f4f6;
        color: var(--text-muted);
        font-weight: 500;
    }
    .category-tag.more { background: white; border: 1px solid var(--border-color); }

    .assigned-buses { font-weight: 600; color: var(--text-main); font-size: 0.95rem; margin-bottom: 2px;}
    .view-buses-link { font-size: 0.75rem; color: var(--blue); text-decoration: none; font-weight: 500;}
    .view-buses-link:hover { text-decoration: underline; }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-suspended { background: rgba(239, 68, 68, 0.1); color: #ef4444; }

    .action-icons { display: flex; gap: 10px; align-items: center; }
    .action-icons a {
        width: 30px; height: 30px;
        display: flex; align-items: center; justify-content: center;
        border-radius: 6px;
        color: var(--text-muted);
        transition: all 0.2s;
        text-decoration: none;
    }
    .action-icons a.view { color: var(--blue); background: rgba(59,130,246,0.1); }
    .action-icons a.edit { color: var(--blue); border: 1px solid var(--border-color); }
    .action-icons a.more { color: #ef4444; }
    .action-icons a:hover { opacity: 0.8; }

    /* Sidebar Panels */
    .sidebar-panel {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
    }
    .sidebar-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 20px;
    }
    
    /* Donut Chart Legend */
    .donut-container { display: flex; align-items: center; gap: 20px; }
    .donut-chart {
        width: 100px; height: 100px;
        border-radius: 50%;
        background: conic-gradient(
            #10b981 0% 77%, 
            #f59e0b 77% 88%, 
            #ef4444 88% 94%, 
            #9ca3af 94% 100%
        );
        position: relative;
        display: flex; align-items: center; justify-content: center;
    }
    .donut-inner {
        width: 75px; height: 75px;
        background: white;
        border-radius: 50%;
        display: flex; flex-direction: column; align-items: center; justify-content: center;
    }
    .donut-inner-val { font-size: 1.2rem; font-weight: 700; color: var(--text-main); line-height: 1; margin-bottom: 2px;}
    .donut-inner-lbl { font-size: 0.7rem; color: var(--text-muted); }
    .donut-legend { flex: 1; }
    .legend-item { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; font-size: 0.75rem; }
    .legend-label { display: flex; align-items: center; gap: 6px; color: var(--text-main); font-weight: 500; }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; }
    .legend-val { color: var(--text-muted); }

    /* City Progress Bars */
    .city-item { margin-bottom: 12px; }
    .city-header { display: flex; justify-content: space-between; font-size: 0.8rem; margin-bottom: 4px; }
    .city-name { font-weight: 500; color: var(--text-main); }
    .city-val { color: var(--text-muted); }
    .progress-bar-bg { height: 6px; background: #f3f4f6; border-radius: 3px; overflow: hidden; }
    .progress-bar-fill { height: 100%; background: var(--blue); border-radius: 3px; }
    .view-all-link { font-size: 0.8rem; color: var(--blue); text-decoration: none; font-weight: 500; margin-top: 10px; display: inline-block; }

    /* Recent Registrations */
    .recent-list { display: flex; flex-direction: column; gap: 15px; }
    .recent-item { display: flex; align-items: center; justify-content: space-between; }
    .recent-info { display: flex; align-items: center; gap: 10px; }
    .recent-logo { width: 32px; height: 32px; border-radius: 50%; object-fit: cover; background: #f3f4f6; display: flex; align-items: center; justify-content: center; font-size: 0.8rem; color: var(--text-muted); }
    .recent-name { font-weight: 600; color: var(--text-main); font-size: 0.85rem; margin-bottom: 2px;}
    .recent-city { font-size: 0.7rem; color: var(--text-muted); }
    .recent-meta { text-align: right; }
    .recent-date { font-size: 0.7rem; color: var(--text-muted); margin-bottom: 4px;}

</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Restaurants</h1>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">Home</a> <span>&gt;</span> Restaurants
        </div>
    </div>
    <a href="{{ route('admin.vendors.create') }}" class="btn btn-primary" style="background: #4f46e5; border: none; font-weight: 500; padding: 10px 20px; border-radius: 8px; text-decoration: none;">
        <i class="fa-solid fa-plus me-2"></i> Add New Restaurant
    </a>
</div>

<!-- Stat Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon purple"><i class="fa-solid fa-store"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total Restaurants</div>
            <div class="stat-value">{{ number_format($totalRestaurants) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 12.6% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-solid fa-check-circle"></i></div>
        <div class="stat-content">
            <div class="stat-label">Active Restaurants</div>
            <div class="stat-value">{{ number_format($activeCount) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 15.3% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-clock"></i></div>
        <div class="stat-content">
            <div class="stat-label">Pending Approval</div>
            <div class="stat-value">{{ number_format($pendingCount) }}</div>
            <div class="stat-trend neutral"><i class="fa-solid fa-hourglass-half"></i> <span class="stat-trend-text">Pending</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-solid fa-ban"></i></div>
        <div class="stat-content">
            <div class="stat-label">Suspended</div>
            <div class="stat-value">{{ number_format($suspendedCount) }}</div>
            <div class="stat-trend down"><i class="fa-solid fa-arrow-down"></i> 7.1% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-wallet"></i></div>
        <div class="stat-content">
            <div class="stat-label">Total Payout (This Month)</div>
            <div class="stat-value">₹ {{ number_format($totalPayout) }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> 18.6% <span class="stat-trend-text">vs last month</span></div>
        </div>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.vendors.index') }}" class="filter-bar">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by restaurant name, owner, phone, city...">
    </div>
    <div class="filter-select">
        <select name="status">
            <option value="">All Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
            <option value="2" {{ request('status') === '2' ? 'selected' : '' }}>Suspended</option>
        </select>
    </div>
    <div class="filter-select">
        <select name="city" onchange="this.form.submit()">
            <option value="">All Cities</option>
            @foreach($topCities as $city)
                <option value="{{ $city['name'] }}" {{ request('city') === $city['name'] ? 'selected' : '' }}>{{ $city['name'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            @foreach($categoryList as $cat)
                <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="filter-action-btn"><i class="fa-solid fa-filter me-1"></i> Filter</button>
    <a href="{{ route('admin.vendors.index') }}" class="filter-action-btn reset" style="text-decoration:none; margin-left: 10px;">Reset</a>
</form>

<div class="restaurants-container">
    <!-- Main Table -->
    <div class="main-table-wrapper">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Restaurant Details</th>
                    <th>Owner & Contact</th>
                    <th>Location</th>
                    <th>Categories</th>
                    <th>Assigned Buses</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($vendors as $index => $vendor)
                    @php
                        // Parsing mock data
                        $rating = $vendor->rating ?: '4.5';
                        $reviews = rand(50, 500);
                        
                        // Parse categories
                        $categoriesStr = $vendor->cuisines ?: 'Biryani, North Indian, Chinese';
                        $allCategories = array_map('trim', explode(',', $categoriesStr));
                        $displayCategories = array_slice($allCategories, 0, 2);
                        $extraCategories = count($allCategories) > 2 ? count($allCategories) - 2 : 0;
                        
                        // Get city from database
                        $city = $vendor->city;
                        
                        $assignedBuses = $vendor->buses_count ?? 0;
                        
                        // Status styling
                        $statusClass = 'status-pending';
                        $statusText = 'Pending';
                        if ($vendor->status == 1) { $statusClass = 'status-active'; $statusText = 'Active'; }
                        if ($vendor->status == 2) { $statusClass = 'status-suspended'; $statusText = 'Suspended'; }
                    @endphp
                    <tr>
                        <td class="text-muted" style="font-size:0.85rem;">{{ $vendors->firstItem() + $index }}</td>
                        <td>
                            <div class="restaurant-detail-col">
                                @if($vendor->logo)
                                    <img src="{{ asset('storage/'.$vendor->logo) }}" class="restaurant-logo" alt="Logo">
                                @else
                                    <div class="restaurant-logo"><i class="fa-solid fa-store"></i></div>
                                @endif
                                <div>
                                    <div class="restaurant-name">{{ $vendor->restaurant_name }}</div>
                                    <div class="restaurant-rating">
                                        <i class="fa-solid fa-star"></i> {{ $rating }} ({{ $reviews }}) • {{ implode(', ', $displayCategories) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="owner-name">{{ $vendor->owner_name ?: 'Unknown' }}</div>
                            <div class="owner-mobile">{{ $vendor->mobile ?: 'N/A' }}</div>
                        </td>
                        <td>
                            @if($city)
                                <div class="city-text" style="font-weight:600;">{{ $city }}</div>
                            @endif
                            @if($vendor->address)
                                <div class="text-muted" style="font-size: 0.75rem; margin-top: {{ $city ? '3px' : '0' }};" title="{{ $vendor->address }}">{{ Str::limit($vendor->address, 35) }}</div>
                            @else
                                <div class="text-muted" style="font-size: 0.75rem; margin-top: {{ $city ? '3px' : '0' }}; font-style: italic;">Address not provided</div>
                            @endif
                        </td>
                        <td>
                            <div class="category-tags">
                                @foreach($displayCategories as $cat)
                                    <span class="category-tag">{{ $cat }}</span>
                                @endforeach
                                @if($extraCategories > 0)
                                    <span class="category-tag more">+{{ $extraCategories }}</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="assigned-buses">{{ $assignedBuses }}</div>
                            <a href="{{ route('admin.vendors.show', $vendor->id) }}#buses" class="view-buses-link">View Buses</a>
                        </td>
                        <td>
                            <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
                        </td>
                        <td>
                            <div class="action-icons">
                                <a href="{{ route('admin.vendors.show', $vendor->id) }}" class="view" title="View Details"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="edit" title="Edit Restaurant"><i class="fa-solid fa-pen-to-square"></i></a>
                                <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="more" style="background:none; border:none; padding:0; cursor:pointer;" onclick="return confirm('Delete this restaurant?')" title="Delete"><i class="fa-solid fa-trash"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No restaurants found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-3" style="border-top: 1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted);">
            <div>Showing {{ $vendors->firstItem() ?? 0 }} to {{ $vendors->lastItem() ?? 0 }} of {{ $vendors->total() }} restaurants</div>
            <div>{{ $vendors->links('pagination::bootstrap-4') }}</div>
        </div>
    </div>
    

</div>
@endsection
