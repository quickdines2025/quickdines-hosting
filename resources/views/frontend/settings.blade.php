@extends('layouts.customer')

@section('title', 'Settings - QuickDines')

@section('styles')
<style>
    .page-header { margin-bottom: 25px; }
    .page-header h2 { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
    .page-header p { font-size: 14px; color: var(--text-muted); }

    .settings-container { display: grid; gap: 20px; max-width: 800px; }
    
    .setting-group { margin-bottom: 10px; }
    .setting-group h3 { font-size: 16px; font-weight: 700; color: var(--text-main); margin-bottom: 15px; display: flex; align-items: center; gap: 10px; }
    .setting-group h3 i { color: var(--primary); }
    
    .setting-item { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; border-bottom: 1px solid var(--border-color); }
    .setting-item:last-child { border-bottom: none; }
    
    .setting-info h4 { font-size: 14px; font-weight: 600; color: var(--text-main); margin-bottom: 4px; }
    .setting-info p { font-size: 12px; color: var(--text-muted); }
    
    /* Toggle Switch */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; transition: .4s; border-radius: 24px; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; border-radius: 50%; }
    input:checked + .slider { background-color: var(--primary); }
    input:checked + .slider:before { transform: translateX(20px); }

    .btn-outline { display: inline-block; padding: 8px 16px; border: 1px solid #e2e8f0; border-radius: 6px; color: var(--text-main); font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.3s; background: transparent; cursor: pointer; }
    .btn-outline:hover { background: #f8fafc; border-color: #cbd5e1; }
    .btn-danger { border-color: #fca5a5; color: #ef4444; }
    .btn-danger:hover { background: #fef2f2; border-color: #ef4444; }
    .btn-save { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
    
    /* Modal Styles */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); display: none; align-items: center; justify-content: center; z-index: 1000; }
    .modal-overlay.active { display: flex; }
    .modal-content { background: white; padding: 30px; border-radius: 12px; width: 100%; max-width: 400px; position: relative; }
    .modal-close { position: absolute; top: 15px; right: 15px; background: none; border: none; font-size: 20px; cursor: pointer; color: #64748b; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; margin-bottom: 5px; font-size: 14px; font-weight: 600; }
    .form-control { width: 100%; padding: 10px; border: 1px solid #e2e8f0; border-radius: 6px; }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>Settings</h2>
    <p>Manage your account preferences</p>
</div>

@if(session('success'))
<div style="background: #dcfce7; color: #16a34a; padding: 15px; border-radius: 8px; font-weight: 600; margin-bottom: 20px;">
    {{ session('success') }}
</div>
@endif

@if($errors->any())
<div style="background: #fee2e2; color: #dc2626; padding: 15px; border-radius: 8px; font-weight: 600; margin-bottom: 20px;">
    @foreach ($errors->all() as $error)
        <div>{{ $error }}</div>
    @endforeach
</div>
@endif

<div class="settings-container">
    <!-- Notifications Settings -->
    <div class="dash-card setting-group">
        <h3><i class="fa-regular fa-bell"></i> Notifications</h3>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Order Updates</h4>
                <p>Receive push notifications when your order status changes</p>
            </div>
            <label class="switch">
                <input type="checkbox" onchange="updateNotification('notify_order_updates', this.checked)" {{ auth()->user()->notify_order_updates ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Promotions & Offers</h4>
                <p>Get notified about new restaurants and special discounts</p>
            </div>
            <label class="switch">
                <input type="checkbox" onchange="updateNotification('notify_promotions', this.checked)" {{ auth()->user()->notify_promotions ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Email Newsletter</h4>
                <p>Receive weekly updates on your registered email address</p>
            </div>
            <label class="switch">
                <input type="checkbox" onchange="updateNotification('notify_email_newsletter', this.checked)" {{ auth()->user()->notify_email_newsletter ? 'checked' : '' }}>
                <span class="slider"></span>
            </label>
        </div>
    </div>

    <!-- Security Settings -->
    <div class="dash-card setting-group">
        <h3><i class="fa-solid fa-shield-halved"></i> Security & Privacy</h3>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Change Password</h4>
                <p>Update your password to keep your account secure</p>
            </div>
            <button class="btn-outline" onclick="openModal('passwordModal')">Update</button>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Two-Factor Authentication</h4>
                <p>Add an extra layer of security to your account</p>
            </div>
            <form action="{{ route('settings.2fa') }}" method="POST">
                @csrf
                <button type="submit" class="btn-outline {{ auth()->user()->two_factor_enabled ? 'btn-danger' : '' }}">
                    {{ auth()->user()->two_factor_enabled ? 'Disable' : 'Enable' }}
                </button>
            </form>
        </div>
    </div>
    
    <!-- Account Actions -->
    <div class="dash-card setting-group">
        <h3><i class="fa-solid fa-user-gear"></i> Account Actions</h3>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Log Out All Devices</h4>
                <p>Sign out from all other active sessions</p>
            </div>
            <button class="btn-outline" onclick="openModal('logoutModal')">Log Out</button>
        </div>
        
        <div class="setting-item">
            <div class="setting-info">
                <h4>Delete Account</h4>
                <p>Permanently remove your account and data</p>
            </div>
            <button class="btn-outline btn-danger" onclick="openModal('deleteModal')">Delete</button>
        </div>
    </div>
</div>

<!-- Password Modal -->
<div class="modal-overlay" id="passwordModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('passwordModal')"><i class="fa-solid fa-xmark"></i></button>
        <h3 style="margin-bottom: 20px;">Change Password</h3>
        <form action="{{ route('settings.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required minlength="8">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required minlength="8">
            </div>
            <button type="submit" class="btn-save" style="width: 100%;">Update Password</button>
        </form>
    </div>
</div>

<!-- Logout Modal -->
<div class="modal-overlay" id="logoutModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('logoutModal')"><i class="fa-solid fa-xmark"></i></button>
        <h3 style="margin-bottom: 20px;">Log Out</h3>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 20px;">Are you sure you want to log out?</p>
        <form action="{{ route('settings.logout-devices') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-save" style="flex: 1; background: #e2e8f0; color: #475569;" onclick="closeModal('logoutModal')">No</button>
                <button type="submit" class="btn-save" style="flex: 1;">Yes, Log Out</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('deleteModal')"><i class="fa-solid fa-xmark"></i></button>
        <h3 style="margin-bottom: 20px; color: #ef4444;">Delete Account</h3>
        <p style="font-size: 14px; color: #64748b; margin-bottom: 20px;">Are you sure you want to permanently delete your account? This action cannot be undone.</p>
        <form action="{{ route('settings.delete-account') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 10px;">
                <button type="button" class="btn-save" style="flex: 1; background: #e2e8f0; color: #475569;" onclick="closeModal('deleteModal')">No</button>
                <button type="submit" class="btn-save" style="flex: 1; background: #ef4444;">Yes, Delete</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(id) {
    document.getElementById(id).classList.add('active');
}
function closeModal(id) {
    document.getElementById(id).classList.remove('active');
}

function updateNotification(type, status) {
    fetch('{{ route("settings.notifications") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type, status: status })
    })
    .then(response => response.json())
    .then(data => {
        if(!data.success) {
            alert('Failed to update setting.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred.');
    });
}
</script>
@endsection
