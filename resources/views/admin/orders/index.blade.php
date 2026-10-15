@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<style>
    .page-header {
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: flex-start;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 5px 0;
    }
    .breadcrumb {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }
    .breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        color: #f26a21;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
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
        .stat-info h3 { font-size: 1.2rem; }
        .stat-info p { font-size: 0.75rem; }
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
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .stat-icon.total { background: #eff6ff; color: #3b82f6; }
    .stat-icon.pending { background: #fffbeb; color: #f59e0b; }
    .stat-icon.preparing { background: #f0f9ff; color: #0ea5e9; }
    .stat-icon.completed { background: #f0fdf4; color: #22c55e; }
    .stat-icon.cancelled { background: #fef2f2; color: #ef4444; }

    .stat-info h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }
    .stat-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Filters */
    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        align-items: center;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1 1 200px;
        position: relative;
    }
    .search-input input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 8px;
        border: 1px solid black;
        font-size: 0.85rem;
        outline: none;
    }
    .search-input i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .filter-select, .filter-date { flex: 1 1 140px; }
    .filter-select select, .filter-date input {
        width: 100%;
        padding: 10px 35px 10px 15px;
        border-radius: 8px;
        border: 1px solid black;
        font-size: 0.85rem;
        outline: none;
        appearance: none;
        color: #475569;
        font-weight: 500;
        background: white url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
    }
    .filter-date {
        position: relative;
    }
    .filter-date input {
        padding-left: 35px;
        background: white;
    }
    .filter-date i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .btn-export {
        background: white;
        border: 1px solid black;
        padding: 10px 15px;
        border-radius: 8px;
        color: black;
        font-size: 0.85rem;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
    }
    .btn-export:hover {
        background: #f8fafc;
    }
    
    .btn-reset {
        background: white;
        border: 1px solid black;
        padding: 10px 15px;
        border-radius: 8px;
        color: black;
        font-size: 0.85rem;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-reset:hover {
        background: #f8fafc;
    }

    /* Table */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom th {
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        padding: 15px 20px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }
    .table-custom td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
        font-size: 0.85rem;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }

    .order-id {
        font-weight: 600;
        color: #1e293b;
    }
    .cell-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }
    .cell-subtitle {
        font-size: 0.75rem;
        color: #64748b;
    }
    .amount {
        font-weight: 600;
        color: #1e293b;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-pending { background: #fffbeb; color: #d97706; }
    .status-preparing { background: #eff6ff; color: #2563eb; }
    .status-completed { background: #f0fdf4; color: #16a34a; }
    .status-cancelled { background: #fef2f2; color: #dc2626; }

    .btn-delete {
        background: white;
        color: #dc2626;
        border: 1px solid #fecaca;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-delete:hover {
        background: #fef2f2;
    }
    
    .pagination-custom {
        display: flex;
        justify-content: flex-end;
        gap: 5px;
    }
    .pagination-custom .page-link {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748b;
        background: white;
        border: 1px solid var(--border-color);
        text-decoration: none;
    }
    .pagination-custom .page-link.active {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Passenger Orders</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a> &gt; Passenger Orders
        </div>
    </div>
    <button type="submit" form="orders-filter-form" name="export" value="csv" class="btn-export">
        <i class="fa-solid fa-download"></i> Export
    </button>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total"><i class="fa-solid fa-receipt"></i></div>
        <div class="stat-info">
            <p>Total Passenger Orders</p>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon pending"><i class="fa-solid fa-hourglass-half"></i></div>
        <div class="stat-info">
            <p>Pending</p>
            <h3>{{ $stats['pending'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon preparing"><i class="fa-solid fa-fire-burner"></i></div>
        <div class="stat-info">
            <p>Preparing</p>
            <h3>{{ $stats['preparing'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon completed"><i class="fa-solid fa-check-double"></i></div>
        <div class="stat-info">
            <p>Completed</p>
            <h3>{{ $stats['completed'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon cancelled"><i class="fa-solid fa-circle-exclamation"></i></div>
        <div class="stat-info">
            <p>Cancelled</p>
            <h3>{{ $stats['cancelled'] }}</h3>
        </div>
    </div>
</div>

<form id="orders-filter-form" class="filter-bar" method="GET" action="{{ route('admin.orders.index') }}">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by order ID, customer, restaurant, or bus..." onkeydown="if(event.key === 'Enter'){event.preventDefault(); this.form.submit();}">
    </div>
    <div class="filter-select">
        <select name="bus_id" onchange="this.form.submit()">
            <option value="">All Buses</option>
            @foreach($buses as $bus)
                <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>{{ $bus->bus_number }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="restaurant_id" onchange="this.form.submit()">
            <option value="">All Restaurants</option>
            @foreach($restaurants as $restaurant)
                <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="status" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed</option>
            <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>Preparing</option>
            <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Ready</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
    </div>
    <div class="filter-date" style="display:flex; gap:5px; align-items:center;">
        <input type="date" name="from_date" value="{{ request('from_date') }}" onchange="this.form.submit()" style="padding: 10px 15px; border-radius: 8px; border: 1px solid black; font-size: 0.85rem; outline: none; color: #475569;">
        <span style="color:#64748b; font-size:0.85rem;">to</span>
        <input type="date" name="to_date" value="{{ request('to_date') }}" onchange="this.form.submit()" style="padding: 10px 15px; border-radius: 8px; border: 1px solid black; font-size: 0.85rem; outline: none; color: #475569;">
    </div>
    <a href="{{ route('admin.orders.index') }}" class="btn-reset ms-auto">
        <i class="fa-solid fa-arrows-rotate"></i> Reset
    </a>
</form>

<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Bus Details</th>
                <th>Items Ordered</th>
                <th>Restaurant</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Order Time</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td class="order-id">#ORD{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="cell-title">{{ $order->passenger_name ?? optional($order->user)->name ?? 'Guest' }}</div>
                    <div class="cell-subtitle">{{ $order->phone_number ?? optional($order->user)->mobile ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="cell-title">{{ optional($order->bus)->bus_number ?? 'N/A' }}</div>
                    <div class="cell-subtitle">
                        @if(optional($order->bus)->busRoute)
                            {{ $order->bus->busRoute->source }} &rarr; {{ $order->bus->busRoute->destination }}
                        @else
                            {{ optional($order->bus)->route_from ?? 'Unknown' }} &rarr; {{ optional($order->bus)->route_to ?? 'Unknown' }}
                        @endif
                    </div>
                    <div class="cell-subtitle" style="margin-top: 4px; font-weight: 600; color: #4f46e5;">
                        Seat: {{ $order->seat_number ?? 'N/A' }}
                    </div>
                </td>
                <td>
                    @foreach($order->items as $item)
                        <div style="font-size: 0.8rem; margin-bottom: 2px;">
                            <span style="font-weight: 600; color: #4f46e5;">{{ $item->quantity }}x</span> 
                            <span style="color: #475569;">{{ $item->menuItem->item_name ?? 'Unknown' }}</span>
                        </div>
                    @endforeach
                </td>
                <td>
                    <div class="cell-title">{{ optional($order->restaurant)->restaurant_name ?? 'N/A' }}</div>
                    <div class="cell-subtitle">{{ optional($order->restaurant)->cuisines ?? 'N/A' }}</div>
                </td>
                <td class="amount">₹{{ number_format($order->total_amount, 2) }}</td>
                <td>
                    @if(in_array($order->order_status, ['placed', 'pending']))
                        <span class="status-badge status-pending">{{ ucfirst($order->order_status) }}</span>
                    @elseif($order->order_status == 'preparing')
                        <span class="status-badge status-preparing">Preparing</span>
                    @elseif(in_array($order->order_status, ['ready', 'out_for_delivery', 'delivered', 'completed']))
                        <span class="status-badge status-completed">{{ ucfirst($order->order_status) }}</span>
                    @else
                        <span class="status-badge status-cancelled">{{ ucfirst($order->order_status) }}</span>
                    @endif
                </td>
                <td>
                    <div class="cell-title" style="font-weight: 500;">{{ $order->created_at->format('M d, Y') }}</div>
                    <div class="cell-subtitle">{{ $order->created_at->format('h:i A') }}</div>
                </td>
                <td>
                    <form action="{{ route('admin.orders.destroy', $order->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this order?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 30px; color: #64748b;">No orders found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-3" style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted); border-top: 1px solid var(--border-color);">
        <div>Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders</div>
        <div>
            {{ $orders->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>

@endsection
