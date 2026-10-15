@extends('layouts.admin')

@section('title', 'Password Management')

@section('content')
<style>
    .page-header {
        margin-bottom: 25px;
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

    /* Table */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        overflow-x: auto;
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
</style>

<div class="page-header">
    <h1 class="page-title">Password Management</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a> &gt; Password Management
    </div>
</div>

@if(session('success'))
    <div style="background: #10b981; color: white; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>User Name</th>
                <th>Role</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Joined On</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
            <tr>
                <td class="customer-name">{{ $user->name }}</td>
                <td><span class="status-badge" style="background:#f1f5f9; color:#475569; text-transform:uppercase;">{{ $user->role }}</span></td>
                <td>{{ $user->mobile ?? 'N/A' }}</td>
                <td>{{ $user->email ?? 'N/A' }}</td>
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
                        @if($user->role !== 'customer')
                        <button type="button" class="btn-view" onclick="openResetModal(@js($user->id), @js($user->name))" title="Reset Password"><i class="fa-solid fa-key"></i> Reset Password</button>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 30px; color: #64748b;">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="p-3" style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem; color:var(--text-muted); border-top: 1px solid var(--border-color);">
        <div>Showing {{ $users->firstItem() ?? 0 }} to {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} users</div>
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
            <div style="margin-bottom:15px; position:relative;">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:5px;">New Password</label>
                <input type="password" name="password" required minlength="8" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; outline:none; padding-right: 40px;" placeholder="Enter new password (min 8 chars)">
                <i class="fa-regular fa-eye" style="position: absolute; right: 15px; bottom: 12px; cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
            </div>
            <div style="margin-bottom:20px; position:relative;">
                <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:5px;">Confirm Password</label>
                <input type="password" name="password_confirmation" required minlength="8" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:6px; outline:none; padding-right: 40px;" placeholder="Confirm new password">
                <i class="fa-regular fa-eye" style="position: absolute; right: 15px; bottom: 12px; cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
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
        document.getElementById('resetForm').action = "/admin/system-users/" + userId + "/reset-password";
        document.getElementById('resetModal').style.display = 'flex';
    }
    function closeResetModal() {
        document.getElementById('resetModal').style.display = 'none';
    }
    function togglePassword(icon) {
        var input = icon.previousElementSibling;
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@endsection
