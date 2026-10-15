@extends(Auth::user()->role === 'support' ? 'layouts.support' : 'layouts.admin')

@php
    $rPrefix = Auth::user()->role === 'support' ? 'support.tickets' : 'admin.support.tickets';
@endphp

@section('content')
<style>
    .page-header {
        display: flex;
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
    .stat-icon.emerald { background: #ecfdf5; color: #10b981; }
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
        gap: 12px;
        align-items: center;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
    }
    .filter-input {
        flex: 2;
        position: relative;
    }
    .filter-input i {
        position: absolute;
        left: 12px;
        top: 12px;
        color: #94a3b8;
    }
    .filter-input input {
        width: 100%;
        padding: 10px 12px 10px 36px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
    }
    .filter-select {
        flex: 1;
    }
    .filter-select select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: white;
    }
    .btn-filter-solid {
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

    /* Table Styles */
    .tickets-card {
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

    /* Priority Badges */
    .priority-badge {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .priority-high { background: #fef2f2; color: #dc2626; }
    .priority-medium { background: #fff7ed; color: #1d4ed8; }
    .priority-low { background: #f0fdf4; color: #16a34a; }

    /* Status Text */
    .status-text {
        font-weight: 600;
        font-size: 0.875rem;
    }
    .status-open { color: #3b82f6; }
    .status-progress { color: #f97316; }
    .status-resolved { color: #10b981; }

    .btn-icon {
        background: #f1f5f9;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        color: #64748b;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }
    .btn-icon:hover {
        background: #e2e8f0;
        color: #6366f1;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Support Tickets</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Support Tickets
        </div>
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon blue"><i class="fa-solid fa-ticket"></i></div>
        <div class="stat-info">
            <p>Total Tickets</p>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon green"><i class="fa-regular fa-folder-open"></i></div>
        <div class="stat-info">
            <p>Open</p>
            <h3>{{ $stats['open'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon orange"><i class="fa-solid fa-spinner"></i></div>
        <div class="stat-info">
            <p>In Progress</p>
            <h3>{{ $stats['in_progress'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon emerald"><i class="fa-solid fa-check-circle"></i></div>
        <div class="stat-info">
            <p>Resolved</p>
            <h3>{{ $stats['resolved'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon red"><i class="fa-regular fa-circle-xmark"></i></div>
        <div class="stat-info">
            <p>Closed</p>
            <h3>{{ $stats['closed'] }}</h3>
        </div>
    </div>
</div>

<form method="GET" action="{{ route($rPrefix . '.index') }}" class="filter-bar">
    <div class="filter-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by Ticket ID, Subject or Customer...">
    </div>
    <div class="filter-select">
        <select name="status">
            <option>All Status</option>
            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
        </select>
    </div>
    <div class="filter-select">
        <select name="priority">
            <option>All Priority</option>
            <option value="High" {{ request('priority') == 'High' ? 'selected' : '' }}>High</option>
            <option value="Medium" {{ request('priority') == 'Medium' ? 'selected' : '' }}>Medium</option>
            <option value="Low" {{ request('priority') == 'Low' ? 'selected' : '' }}>Low</option>
        </select>
    </div>
    <div class="filter-select">
        <select name="category">
            <option>All Categories</option>
            <option value="Order & Delivery" {{ request('category') == 'Order & Delivery' ? 'selected' : '' }}>Order & Delivery</option>
            <option value="Payment" {{ request('category') == 'Payment' ? 'selected' : '' }}>Payment</option>
            <option value="Account" {{ request('category') == 'Account' ? 'selected' : '' }}>Account</option>
            <option value="Restaurant" {{ request('category') == 'Restaurant' ? 'selected' : '' }}>Restaurant</option>
            <option value="Driver" {{ request('category') == 'Driver' ? 'selected' : '' }}>Driver</option>
        </select>
    </div>
    <button type="submit" class="btn-filter-solid"><i class="fa-solid fa-filter"></i> Filter</button>
    <a href="{{ route($rPrefix . '.index') }}" class="btn-reset" style="text-decoration:none;"><i class="fa-solid fa-rotate-left"></i> Reset</a>
</form>

<div class="tickets-card">
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
        <table class="table" style="width: 100%; min-width: 800px; border-collapse: collapse;">
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>User</th>
                <th>Subject</th>
                <th>Category</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Created On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
            <tr>
                <td><strong>{{ $ticket->ticket_number ?? 'TKT'.$ticket->id }}</strong></td>
                <td>
                    <div style="font-weight: 600; color: #1e293b;">
                        {{ $ticket->customer ? $ticket->customer->name : 'Guest' }}
                        @if($ticket->customer)
                            <span class="badge" style="font-size: 0.65rem; margin-left: 5px; background: {{ $ticket->customer->role == 'vendor' ? '#8b5cf6' : '#3b82f6' }}; color: white;">{{ ucfirst($ticket->customer->role ?? 'customer') }}</span>
                        @endif
                    </div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ $ticket->customer ? $ticket->customer->email : '' }}</div>
                    @if($ticket->customer && $ticket->customer->mobile)
                        <div style="font-size: 0.75rem; color: #64748b;">{{ $ticket->customer->mobile }}</div>
                    @endif
                </td>
                <td style="max-width: 400px; white-space: pre-wrap; word-wrap: break-word;">{{ $ticket->issue }}</td>
                <td>{{ $ticket->category ?? 'General' }}</td>
                <td>
                    @if(strtolower($ticket->priority) == 'high')
                        <span class="priority-badge priority-high">High</span>
                    @elseif(strtolower($ticket->priority) == 'medium')
                        <span class="priority-badge priority-medium">Medium</span>
                    @else
                        <span class="priority-badge priority-low">Low</span>
                    @endif
                </td>
                <td>
                    @if(strtolower($ticket->status) == 'open')
                        <span class="status-text status-open">Open</span>
                    @elseif(strtolower($ticket->status) == 'in progress')
                        <span class="status-text status-progress">In Progress</span>
                    @else
                        <span class="status-text status-resolved">{{ ucfirst($ticket->status) }}</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $ticket->created_at->format('M d, Y') }}</div>
                    <div style="font-size: 0.75rem; color: #64748b;">{{ $ticket->created_at->format('h:i A') }}</div>
                </td>
                <td style="display: flex; gap: 8px;">
                    <a href="{{ route($rPrefix . '.show', $ticket->id) }}" class="btn-icon"><i class="fa-regular fa-eye"></i></a>
                    <form action="{{ route($rPrefix . '.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-icon" style="color: #ef4444;"><i class="fa-regular fa-trash-can"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; padding:32px; color:#64748b;">No support tickets found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
    
    <div style="padding: 16px; border-top: 1px solid #e2e8f0;">
        {{ $tickets->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
