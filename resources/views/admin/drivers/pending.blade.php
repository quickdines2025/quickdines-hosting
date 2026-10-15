@extends('layouts.admin')

@section('title', 'Pending Drivers')

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

    .driver-info {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .driver-logo {
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
    
    .driver-name {
        font-weight: 600;
        color: var(--text-main);
        font-size: 0.95rem;
        margin-bottom: 2px;
    }
    .driver-meta {
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
        <h1 class="page-title">Pending Drivers</h1>
        <div class="page-subtitle">Drivers waiting for admin approval</div>
    </div>
</div>

<form class="filter-bar" method="GET" action="{{ route('admin.drivers.pending') }}">
    <div class="search-input">
        <i class="fa-solid fa-search"></i>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search driver name or mobile...">
    </div>
    <button type="submit" class="btn-refresh" style="display: none;">Submit</button>
    <a href="{{ route('admin.drivers.pending') }}" class="btn-refresh" style="text-decoration: none;">
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
                <th>Driver Details</th>
                <th>Contact</th>
                <th>Documents</th>
                <th>Submitted On</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pendingDrivers as $index => $driver)
                <tr>
                    <td style="font-weight: 600;">{{ $pendingDrivers->firstItem() + $index }}</td>
                    <td>
                        <div class="driver-info">
                            <div class="driver-logo">{{ substr($driver->name, 0, 1) }}</div>
                            <div>
                                <div class="driver-name">{{ $driver->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500; color: var(--text-main); font-size: 0.9rem;">{{ $driver->mobile ?: 'N/A' }}</div>
                        <div class="driver-meta" style="margin-top: 2px;">{{ $driver->user ? $driver->user->email : 'N/A' }}</div>
                    </td>
                    <td>
                        <div class="doc-badges">
                            @if($driver->document_path)
                                <div class="doc-badge doc-btn" style="cursor: pointer; background: #e0e7ff; color: #4f46e5; border-color: #c7d2fe;" onclick="openDocModal('{{ asset('storage/' . $driver->document_path) }}')">
                                    <i class="fa-regular fa-file-pdf"></i>
                                    Doc
                                </div>
                            @else
                                <span class="text-muted" style="font-size:0.8rem;">No Documents</span>
                            @endif
                        </div>
                    </td>
                    <td>
                        <div style="font-weight: 500; font-size: 0.85rem;">{{ $driver->created_at->format('M d, Y') }}</div>
                        <div class="driver-meta" style="margin-top: 2px;">{{ $driver->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            @if($driver->status == 0)
                                <form action="{{ route('admin.drivers.approve', $driver->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-approve">
                                        <i class="fa-solid fa-check"></i> Approve
                                    </button>
                                </form>
                                
                                <form action="{{ route('admin.drivers.reject', $driver->id) }}" method="POST" style="margin: 0;">
                                    @csrf
                                    <button type="submit" class="btn-reject" onclick="return confirm('Are you sure you want to reject this driver?')">
                                        <i class="fa-solid fa-xmark"></i> Reject
                                    </button>
                                </form>
                            @endif

                            <form action="{{ route('admin.drivers.application.destroy', $driver->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-more" style="color: #ef4444;" title="Delete" onclick="return confirm('Are you sure you want to delete this driver?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5 text-muted">
                        No pending drivers found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-3" style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted); border-top: 1px solid var(--border-color);">
        <div>Showing {{ $pendingDrivers->firstItem() ?? 0 }} to {{ $pendingDrivers->lastItem() ?? 0 }} of {{ $pendingDrivers->total() }} pending drivers</div>
        <div>{{ $pendingDrivers->links('pagination::bootstrap-4') }}</div>
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

