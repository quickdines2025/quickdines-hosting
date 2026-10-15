@extends('layouts.admin')

@section('title', 'Pending Restaurants')

@section('content')
<style>
    .page-header {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 25px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0;
    }
    .page-subtitle {
        font-size: 0.9rem;
        color: var(--text-muted);
        margin-top: 5px;
    }

    .filter-bar {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        align-items: center;
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
        font-size: 0.9rem;
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
        font-size: 0.9rem;
        outline: none;
        appearance: none;
        background: white url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
    }

    .btn-refresh {
        flex: 1 1 100px; justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        padding: 10px 15px;
        border-radius: 8px;
        color: var(--text-main);
        font-size: 0.9rem;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

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
        text-transform: uppercase;
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
        color: var(--text-main);
        font-size: 0.9rem;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }

    .restaurant-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .restaurant-logo {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #64748b;
        font-weight: bold;
        font-size: 1.2rem;
    }
    
    .restaurant-name {
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.95rem;
        margin-bottom: 2px;
    }
    .restaurant-meta {
        font-size: 0.8rem;
        color: var(--text-muted);
    }
    
    .doc-badges {
        display: flex;
        gap: 10px;
    }
    .doc-badge {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 8px 12px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.7rem;
        font-weight: 600;
        background: #fafafa;
    }
    .doc-badge.fssai i { color: #ef4444; font-size: 1.1rem; margin-bottom: 4px; }
    .doc-badge.gst i { color: #10b981; font-size: 1.1rem; margin-bottom: 4px; }
    
    .action-buttons {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .btn-approve {
        background: white;
        color: #10b981;
        border: 1px solid #10b981;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-approve:hover { background: #f0fdf4; }
    
    .btn-reject {
        background: white;
        color: #ef4444;
        border: 1px solid #ef4444;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    .btn-reject:hover { background: #fef2f2; }
    
    .btn-more {
        background: none;
        border: none;
        color: #64748b;
        cursor: pointer;
        padding: 5px;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Pending Restaurants</h1>
        <div class="page-subtitle">Restaurants waiting for admin approval</div>
    </div>
</div>

<form class="filter-bar" method="GET" action="{{ route('admin.restaurants.pending') }}">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search restaurant or owner...">
    </div>
    <div class="filter-select">
        <select name="city" onchange="this.form.submit()">
            <option value="">All Cities</option>
            @foreach($cities as $c)
                <option value="{{ $c }}" {{ request('city') == $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="cuisine" onchange="this.form.submit()">
            <option value="">All Cuisines</option>
            @foreach($cuisinesList as $c)
                <option value="{{ $c }}" {{ request('cuisine') == $c ? 'selected' : '' }}>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-select">
        <select name="sort" onchange="this.form.submit()">
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Sort by: Newest</option>
            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Sort by: Oldest</option>
        </select>
    </div>
    <button type="submit" class="btn-refresh" style="display: none;">Submit</button>
    <a href="{{ route('admin.restaurants.pending') }}" class="btn-refresh" style="text-decoration: none;">
        <i class="fa-solid fa-arrows-rotate"></i> Reset
    </a>
</form>

@if(session('success'))
    <div class="alert alert-success" style="border-radius: 8px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>#</th>
                <th>Restaurant Details</th>
                <th>Owner Details</th>
                <th>Documents</th>
                <th>Submitted On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingRestaurants as $index => $vendor)
                <tr>
                    <td style="font-weight: 600;">{{ $pendingRestaurants->firstItem() + $index }}</td>
                    <td>
                        <div class="restaurant-info">
                            @if($vendor->logo)
                                <img src="{{ asset('storage/'.$vendor->logo) }}" class="restaurant-logo" alt="Logo">
                            @else
                                <div class="restaurant-logo">{{ substr($vendor->restaurant_name, 0, 1) }}</div>
                            @endif
                            <div>
                                <div class="restaurant-name">{{ $vendor->restaurant_name }}</div>
                                <div class="restaurant-meta">
                                    {{ Str::limit($vendor->cuisines, 20) }}<br>
                                    {{ $vendor->city ?: 'Location Not Set' }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500; color: var(--text-main); font-size: 0.9rem;">{{ $vendor->owner_name ?: 'Owner Not Set' }}</div>
                        <div class="restaurant-meta" style="margin-top: 2px;">{{ $vendor->mobile ?: 'Mobile Not Set' }}</div>
                    </td>
                    <td>
                        <div class="doc-badges">
                            @if($vendor->document_path)
                                <div class="doc-badge doc-btn" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; border-color: #c7d2fe;" onclick="openDocModal('{{ asset('storage/' . $vendor->document_path) }}')">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Doc
                                </div>
                            @endif
                            @if($vendor->image)
                                <div class="doc-badge doc-btn" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; border-color: #c7d2fe;" onclick="openDocModal('{{ asset('storage/' . $vendor->image) }}')">
                                    <i class="fa-regular fa-image"></i>
                                    Image
                                </div>
                            @endif
                            @if(!$vendor->document_path && !$vendor->image)
                                <span class="text-muted" style="font-size:0.8rem;">No Documents</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500; font-size: 0.85rem;">{{ $vendor->created_at->format('M d, Y') }}</div>
                        <div class="restaurant-meta" style="margin-top: 2px;">{{ $vendor->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($vendor->status == 0)
                                <form action="{{ route('admin.restaurants.approve', $vendor->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-approve">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.restaurants.reject', $vendor->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to reject this restaurant?')">
                                        <i class="fa-solid fa-xmark"></i> Reject
                                    </button>
                                </form>
                            @elseif($vendor->status == 1)
                                <span style="background: #dcfce7; color: #166534; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-check-circle"></i> Approved
                                </span>
                            @else
                                <span style="background: #fee2e2; color: #991b1b; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; display: inline-flex; align-items: center; gap: 5px;">
                                    <i class="fa-solid fa-times-circle"></i> Rejected
                                </span>
                            @endif

                            <form action="{{ route('admin.vendors.destroy', $vendor->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-more" style="color: #ef4444;" title="Delete" onclick="return confirm('Are you sure you want to delete this restaurant permanently?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        No pending restaurants found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-3" style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted); border-top: 1px solid var(--border-color);">
        <div>Showing {{ $pendingRestaurants->firstItem() ?? 0 }} to {{ $pendingRestaurants->lastItem() ?? 0 }} of {{ $pendingRestaurants->total() }} pending restaurants</div>
        <div>{{ $pendingRestaurants->links('pagination::bootstrap-4') }}</div>
    </div>
</div>

<!-- Document Modal -->
<div id="docModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 9999; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; border-radius: 12px; width: 90%; max-width: 800px; height: 80vh; display: flex; flex-direction: column; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
        <div style="padding: 15px 20px; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #f8fafc;">
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #1e293b;">Document Preview</h3>
            <button onclick="closeDocModal()" style="background: none; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; padding: 5px;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div style="flex: 1; padding: 0; background: #e2e8f0; position: relative; display: flex; align-items: center; justify-content: center; min-height: 0;">
            <iframe id="docIframe" src="" style="width: 100%; height: 100%; border: none; display: none;"></iframe>
            <img id="docImg" src="" style="width: 100%; height: 100%; object-fit: contain; display: none; padding: 20px; box-sizing: border-box;">
        </div>
    </div>
</div>

<script>
    function openDocModal(url) {
        let isImage = url.match(/\.(jpeg|jpg|gif|png|webp|svg|bmp)(?:\?.*)?$/i) != null;
        if(isImage) {
            document.getElementById('docIframe').style.display = 'none';
            document.getElementById('docImg').style.display = 'block';
            document.getElementById('docImg').src = url;
        } else {
            document.getElementById('docImg').style.display = 'none';
            document.getElementById('docIframe').style.display = 'block';
            document.getElementById('docIframe').src = url;
        }
        document.getElementById('docModal').style.display = 'flex';
    }

    function closeDocModal() {
        document.getElementById('docModal').style.display = 'none';
        document.getElementById('docIframe').src = '';
        document.getElementById('docImg').src = '';
    }
</script>

@endsection
