@extends('layouts.admin')

@section('title', 'Drivers')

@section('content')
<style>
    .page-header { margin-bottom: 25px; display: flex; flex-wrap: wrap; gap: 16px; justify-content: space-between; align-items: center; }
    .page-title { font-size: 1.5rem; font-weight: 700; color: #1e293b; margin: 0 0 5px 0; }
    .breadcrumb { font-size: 0.85rem; color: #64748b; margin: 0; }
    .breadcrumb a { color: #64748b; text-decoration: none; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-bottom: 25px; }
    @media (max-width: 1200px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) { .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; } .stat-card { padding: 12px; gap: 10px; flex-direction: column; text-align: center; } .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; } .stat-info h3 { font-size: 1.2rem; } .stat-info p { font-size: 0.75rem; } }
    .stat-card { background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 15px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid var(--border-color); }
    .stat-icon { width: 48px; height: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .stat-icon.total { background: #f3e8ff; color: #a855f7; }
    .stat-icon.active { background: #f0fdf4; color: #22c55e; }
    .stat-icon.inactive { background: #fffbeb; color: #f59e0b; }
    .stat-icon.trip { background: #eff6ff; color: #3b82f6; }
    .stat-info h3 { margin: 0; font-size: 1.25rem; font-weight: 700; color: #1e293b; }
    .stat-info p { margin: 0; font-size: 0.8rem; color: #64748b; font-weight: 500; }

    .filter-bar { display: flex; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; background: white; padding: 15px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid var(--border-color); align-items: center; }
    .search-input { flex: 1 1 200px; position: relative; }
    .search-input input { width: 100%; padding: 10px 15px 10px 40px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem; outline: none; }
    .search-input i { position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #94a3b8; }
    .filter-select { flex: 1 1 140px; min-width: 140px; }
    .filter-select select { width: 100%; padding: 10px 35px 10px 15px; border-radius: 8px; border: 1px solid var(--border-color); font-size: 0.85rem; outline: none; appearance: none; color: #475569; font-weight: 500; background: white url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center; }
    
    .btn-export { flex: 1 1 100px; justify-content: center; background: white; border: 1px solid var(--border-color); padding: 10px 15px; border-radius: 8px; color: #475569; font-size: 0.85rem; cursor: pointer; font-weight: 500; display: flex; align-items: center; gap: 8px; text-decoration: none; }
    .btn-add { flex: 1 1 100px; justify-content: center; background: #6366f1; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-size: 0.85rem; cursor: pointer; font-weight: 600; display: flex; align-items: center; gap: 8px; text-decoration: none; }

    .table-container { background: white; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); border: 1px solid var(--border-color); overflow: hidden; }
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom th { font-size: 0.75rem; font-weight: 600; color: #64748b; padding: 15px 20px; background: #f8fafc; border-bottom: 1px solid var(--border-color); text-align: left; }
    .table-custom td { padding: 15px 20px; vertical-align: middle; border-bottom: 1px solid var(--border-color); color: #334155; font-size: 0.85rem; }
    
    .driver-details { display: flex; align-items: center; gap: 12px; }
    .driver-avatar { width: 40px; height: 40px; border-radius: 50%; background: #e2e8f0; display: flex; align-items: center; justify-content: center; font-weight: 600; color: #475569; overflow: hidden; }
    .driver-avatar img { width: 100%; height: 100%; object-fit: cover; }
    
    .cell-title { font-weight: 600; color: #1e293b; margin-bottom: 2px; }
    .cell-subtitle { font-size: 0.75rem; color: #64748b; }
    
    .status-badge { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
    .status-active { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .status-inactive { background: #fef2f2; color: #ef4444; border: 1px solid #fecaca; }
    .status-trip { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    
    .action-btn { background: white; border: 1px solid var(--border-color); width: 32px; height: 32px; border-radius: 6px; display: inline-flex; align-items: center; justify-content: center; color: #64748b; text-decoration: none; transition: 0.2s; }
    .action-btn:hover { border-color: #6366f1; color: #6366f1; }
    .action-btn.delete { color: #ef4444; border-color: #fecaca; }
    .action-btn.delete:hover { background: #fef2f2; }
</style>

<div class="page-header">
    <h1 class="page-title">Drivers</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a> &gt; Drivers
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total"><i class="fa-solid fa-id-card"></i></div>
        <div class="stat-info">
            <p>Total Drivers</p>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon active"><i class="fa-solid fa-link"></i></div>
        <div class="stat-info">
            <p>Active Drivers</p>
            <h3>{{ $stats['active'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon inactive"><i class="fa-solid fa-box"></i></div>
        <div class="stat-info">
            <p>Inactive Drivers</p>
            <h3>{{ $stats['inactive'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon trip"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <p>On Trip</p>
            <h3>{{ $stats['on_trip'] }}</h3>
        </div>
    </div>
</div>

<form action="{{ route('admin.drivers.index') }}" method="GET" id="filterForm">
    <div class="filter-bar">
        <div class="search-input">
            <i class="fa-solid fa-search"></i>
            <input type="text" name="search" placeholder="Search by name, phone or email..." value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="filter-select">
            <select name="status" onchange="document.getElementById('filterForm').submit()">
                <option value="all">All Status</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="blocked" {{ request('status') == 'blocked' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="filter-select">
            <select name="bus" onchange="document.getElementById('filterForm').submit()">
                <option value="all">All Buses</option>
                {{-- To be populated if needed --}}
            </select>
        </div>
        @if(request()->has('search') || request()->has('status') || request()->has('bus'))
            <a href="{{ route('admin.drivers.index') }}" class="btn-export ms-auto" style="color: #ef4444; border-color: #fecaca; background: #fef2f2;">
                <i class="fa-solid fa-rotate-left"></i> Reset
            </a>
            <a href="{{ route('admin.drivers.index', array_merge(request()->all(), ['export' => 1])) }}" class="btn-export ms-2">
                <i class="fa-solid fa-download"></i> Export
            </a>
        @else
            <a href="{{ route('admin.drivers.index', array_merge(request()->all(), ['export' => 1])) }}" class="btn-export ms-auto">
                <i class="fa-solid fa-download"></i> Export
            </a>
        @endif
        <button type="button" class="btn-add ms-2" data-bs-toggle="modal" data-bs-target="#addDriverModal">
            <i class="fa-solid fa-plus"></i> Add Driver
        </button>
    </div>
</form>

<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Driver ID</th>
                <th>Driver Details</th>
                <th>Phone / Email</th>
                <th>Assigned Bus</th>
                <th>Status</th>
                <th>Wallet & Bank</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($drivers as $driver)
            <tr>
                <td style="font-weight: 600; color: #475569;">DRV100{{ $driver->id }}</td>
                <td>
                    <div class="driver-details">
                        <div class="driver-avatar">
                            @if($driver->profile_image)
                                <img src="{{ asset('storage/' . $driver->profile_image) }}" alt="Avatar">
                            @else
                                {{ substr($driver->name, 0, 1) }}
                            @endif
                        </div>
                        <div>
                            <div class="cell-title">{{ $driver->name }}</div>
                            @if($driver->email)
                                <div class="cell-subtitle">{{ $driver->email }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="cell-title" style="font-weight: 500;">{{ $driver->mobile }}</div>
                </td>
                <td>
                    @if($driver->driver && $driver->driver->bus)
                        <div class="cell-title">{{ $driver->driver->bus->bus_number }}</div>
                        <div class="cell-subtitle">
                            @if($driver->driver->bus->busRoute)
                                {{ $driver->driver->bus->busRoute->source }} - {{ $driver->driver->bus->busRoute->destination }}
                            @else
                                {{ $driver->driver->bus->route_from }} - {{ $driver->driver->bus->route_to }}
                            @endif
                        </div>
                    @else
                        <div class="cell-title" style="color: #64748b; font-style: italic;">Not Assigned</div>
                        <a href="{{ route('admin.buses.index') }}" class="btn btn-sm btn-outline-primary mt-1" style="font-size: 0.75rem; padding: 2px 8px; border-radius: 4px;">Assign from Buses</a>
                    @endif
                </td>
                <td>
                    @if($driver->status == 'active')
                        <span class="status-badge status-active">Active</span>
                    @else
                        <span class="status-badge status-inactive">Inactive</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 700; color: #16a34a; font-size: 15px;">₹{{ number_format(optional($driver->driver)->wallet_balance ?? 0, 2) }}</div>
                    @if(optional($driver->driver)->bank_name || optional($driver->driver)->account_number)
                        <div style="font-size: 11px; color: #64748b; margin-top: 4px; line-height: 1.4;">
                            <strong style="color: #1e293b;">{{ $driver->driver->bank_name }}</strong><br>
                            Acct: {{ $driver->driver->account_number }}<br>
                            IFSC: {{ $driver->driver->ifsc_code }}<br>
                            Branch: {{ $driver->driver->branch_name }}
                        </div>
                    @else
                        <div style="font-size: 11px; color: #ef4444; margin-top: 4px; font-style: italic;">
                            No Bank Details
                        </div>
                    @endif
                </td>
                <td>
                    <div style="display:flex; gap:8px;">
                        <button type="button" class="action-btn" data-bs-toggle="modal" data-bs-target="#editDriverModal{{ $driver->id }}">
                            <i class="fa-solid fa-pen"></i>
                        </button>
                        <form action="{{ route('admin.drivers.destroy', $driver->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Delete driver?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="action-btn delete"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>

            <!-- Edit Modal -->
            <div class="modal fade" id="editDriverModal{{ $driver->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.drivers.update', $driver->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Driver</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ $driver->name }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Mobile Number</label>
                                    <input type="text" name="mobile" class="form-control" value="{{ $driver->mobile }}" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address (Optional)</label>
                                    <input type="email" name="email" class="form-control" value="{{ $driver->email }}">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-control">
                                        <option value="active" {{ $driver->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="blocked" {{ $driver->status == 'blocked' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Reset Password (Leave blank to keep current)</label>
                                    <input type="password" name="password" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary" style="background:#6366f1; border:none;">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fa-solid fa-id-card" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                    <p>No drivers found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-3" style="border-top: 1px solid var(--border-color);">
        {{ $drivers->links('pagination::bootstrap-4') }}
    </div>
</div>

<!-- Add Driver Modal -->
<div class="modal fade" id="addDriverModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.drivers.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Driver</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile Number</label>
                        <input type="text" name="mobile" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email Address (Optional)</label>
                        <input type="email" name="email" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="background:#6366f1; border:none;">Add Driver</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
