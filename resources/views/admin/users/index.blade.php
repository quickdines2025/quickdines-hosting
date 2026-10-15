@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
<style>
    .page-header {
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: center;
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
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 25px;
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
    .stat-icon.active-users { background: #f0fdf4; color: #22c55e; }
    .stat-icon.new-users { background: #f0f9ff; color: #0ea5e9; }
    .stat-icon.blocked { background: #fef2f2; color: #ef4444; }

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
        border: 1px solid var(--border-color);
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
    .filter-select { flex: 1 1 140px; min-width: 140px; }
    .filter-select select {
        width: 100%;
        padding: 10px 35px 10px 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.85rem;
        outline: none;
        appearance: none;
        color: #475569;
        font-weight: 500;
        background: white url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
    }
    
    .btn-export, .btn-reset {
        flex: 1 1 100px; justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        padding: 10px 15px;
        border-radius: 8px;
        color: #475569;
        font-size: 0.85rem;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-delete {
        background: white;
        border: 1px solid #fecaca;
        padding: 8px 12px;
        border-radius: 6px;
        color: #ef4444;
        font-size: 0.85rem;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-delete:hover {
        background: #fef2f2;
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

    .customer-name {
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

    .status-badge {
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }
    .status-active { background: #dcfce7; color: #166534; }
    .status-blocked { background: #fee2e2; color: #991b1b; }

    .btn-view {
        background: white;
        color: #6366f1;
        border: 1px solid #c7d2fe;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-view:hover {
        background: #e0e7ff;
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
    <h1 class="page-title">Customers</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a> &gt; Customers
    </div>
</div>

@if(session('success'))
    <div style="background: #10b981; color: white; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <p>Total Customers</p>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon active-users"><i class="fa-solid fa-user-check"></i></div>
        <div class="stat-info">
            <p>Active Customers</p>
            <h3>{{ $stats['active'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon new-users"><i class="fa-solid fa-user-plus"></i></div>
        <div class="stat-info">
            <p>New This Week</p>
            <h3>{{ $stats['new_this_week'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon blocked"><i class="fa-solid fa-user-slash"></i></div>
        <div class="stat-info">
            <p>Blocked Customers</p>
            <h3>{{ $stats['blocked'] }}</h3>
        </div>
    </div>
</div>

<form class="filter-bar" method="GET" action="{{ route('admin.users.index') }}">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, phone or email..." onkeydown="if(event.key === 'Enter'){event.preventDefault(); this.form.submit();}">
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
        <select name="status" onchange="this.form.submit()">
            <option value="">All Status</option>
            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
            <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Blocked</option>
        </select>
    </div>
    <a href="{{ route('admin.users.index') }}" class="btn-export" style="text-decoration:none;">
        <i class="fa-solid fa-arrows-rotate"></i> Reset
    </a>
    <button type="submit" name="export" value="csv" class="btn-export ms-auto">
        <i class="fa-solid fa-download"></i> Export
    </button>
</form>

    <div class="table-container">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Bus Details</th>
                    <th>Total Orders</th>
                    <th>Joined On</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="customer-name" style="font-weight:600; color:#1e293b;">{{ $user->name }}</td>
                    <td>{{ $user->mobile ?? 'N/A' }}</td>
                    <td>{{ $user->email ?? 'N/A' }}</td>
                    <td>
                        @if($user->orders->count() > 0 && $user->orders->first()->bus)
                            @php $latestBus = $user->orders->first()->bus; @endphp
                            <div class="cell-title">{{ $latestBus->bus_number }}</div>
                            <div class="cell-subtitle">
                                @if($latestBus->busRoute)
                                    {{ $latestBus->busRoute->source }} &rarr; {{ $latestBus->busRoute->destination }}
                                @else
                                    {{ $latestBus->route_from ?? 'Unknown' }} &rarr; {{ $latestBus->route_to ?? 'Unknown' }}
                                @endif
                            </div>
                        @elseif($user->bus)
                            <div class="cell-title">{{ $user->bus->bus_number }} <span style="font-size:0.75rem; color:#6366f1; background:#e0e7ff; padding:2px 6px; border-radius:4px; margin-left:4px;">Scanned</span></div>
                            <div class="cell-subtitle">
                                @if($user->bus->busRoute)
                                    {{ $user->bus->busRoute->source }} &rarr; {{ $user->bus->busRoute->destination }}
                                @else
                                    {{ $user->bus->route_from ?? 'Unknown' }} &rarr; {{ $user->bus->route_to ?? 'Unknown' }}
                                @endif
                            </div>
                        @else
                            <span style="color:#94a3b8; font-size:0.85rem;">No bus scanned</span>
                        @endif
                    </td>
                    <td class="total-orders">{{ $user->orders_count ?? 0 }}</td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        @if($user->status == 'active')
                            <span class="status-badge status-active">Active</span>
                        @else
                            <span class="status-badge status-blocked" style="background:#fef2f2; color:#ef4444;">Blocked</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex; gap:8px;">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn-view">View</a>
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this customer? This action cannot be undone.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-delete" title="Delete Customer"><i class="fa-solid fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px; color: #64748b;">No customers found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="p-3" style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted); border-top: 1px solid var(--border-color);">
            <div>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} customers</div>
            <div>
                {{ $users->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
    
    <!-- Password Reset Modal -->
    <div id="resetModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
        <div style="background:white; padding:24px; border-radius:12px; width:400px; max-width:90%; box-shadow:0 4px 20px rgba(0,0,0,0.15);">
            <h3 style="margin-top:0; color:#1e293b;">Reset Password</h3>
            <p style="font-size:0.85rem; color:#64748b; margin-bottom:20px;">Enter a new password for <strong id="resetUserName"></strong>.</p>
            
            <form id="resetForm" method="POST" action="">
                @csrf
                <div style="margin-bottom:15px;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:5px;">New Password</label>
                    <input type="password" name="password" required minlength="8" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; outline:none;" placeholder="Enter new password (min 8 chars)">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:5px;">Confirm Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; outline:none;" placeholder="Confirm new password">
                </div>
                
                <div style="display:flex; justify-content:flex-end; gap:10px;">
                    <button type="button" onclick="closeResetModal()" style="background:white; border:1px solid #cbd5e1; padding:8px 16px; border-radius:6px; cursor:pointer;">Cancel</button>
                    <button type="submit" style="background:#6366f1; color:white; border:none; padding:8px 16px; border-radius:6px; cursor:pointer; font-weight:600;">Save Password</button>
                </div>
            </form>
        </div>
    </div>
    
    <script>
        function openResetModal(userId, userName) {
            document.getElementById('resetUserName').innerText = userName;
            document.getElementById('resetForm').action = "/quick/admin/users/" + userId + "/reset-password";
            document.getElementById('resetModal').style.display = 'flex';
        }
        function closeResetModal() {
            document.getElementById('resetModal').style.display = 'none';
        }
    </script>


@endsection
