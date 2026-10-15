@extends('layouts.customer')

@section('title', 'My Profile - QuickDines')

@section('styles')
<style>
    .page-header { margin-bottom: 25px; }
    .page-header h2 { font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 5px; }
    .page-header p { font-size: 14px; color: var(--text-muted); }

    .profile-container { display: grid; grid-template-columns: 1fr 2fr; gap: 25px; max-width: 1000px; }
    
    .profile-card { text-align: center; }
    .profile-avatar-wrap { position: relative; width: 120px; height: 120px; margin: 0 auto 15px; }
    .profile-avatar { width: 100%; height: 100%; border-radius: 50%; object-fit: cover; border: 4px solid white; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .avatar-edit { position: absolute; bottom: 5px; right: 5px; width: 32px; height: 32px; background: var(--primary); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; border: 2px solid white; font-size: 14px; }
    
    .profile-card h3 { font-size: 20px; font-weight: 700; color: var(--text-main); margin-bottom: 5px; }
    .profile-card p { font-size: 14px; color: var(--text-muted); margin-bottom: 15px; }
    
    .stat-box { display: flex; justify-content: space-around; padding-top: 15px; border-top: 1px solid var(--border-color); }
    .stat-item h4 { font-size: 18px; font-weight: 800; color: var(--text-main); margin-bottom: 3px; }
    .stat-item span { font-size: 12px; color: var(--text-muted); font-weight: 500; }

    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 13px; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #e2e8f0; border-radius: 8px; font-size: 14px; outline: none; transition: border-color 0.3s; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1); }
    
    .btn-save { background: var(--primary); color: white; border: none; padding: 12px 25px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.3s; width: 100%; }
    .btn-save:hover { background: #1d4ed8; }

    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    
    @media (max-width: 768px) {
        .profile-container { grid-template-columns: 1fr; }
        .form-row { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <h2>My Profile</h2>
    <p>Manage your personal information</p>
</div>

<form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="profile-container">
    @if(session('success'))
        <div style="grid-column: 1 / -1; background: #dcfce7; color: #16a34a; padding: 15px; border-radius: 8px; font-weight: 600; margin-bottom: 10px;">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Left Column: Avatar & Stats -->
    <div class="dash-card profile-card">
        <div class="profile-avatar-wrap">
            @php
                $avatarUrl = auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name ?? 'User').'&background=ff5722&color=fff&size=200';
            @endphp
            <img src="{{ $avatarUrl }}" class="profile-avatar" id="avatar_preview" alt="Avatar">
            <label for="avatar_upload" class="avatar-edit" style="margin: 0; cursor: pointer;">
                <i class="fa-solid fa-camera"></i>
            </label>
            <input type="file" id="avatar_upload" name="avatar" accept="image/*" style="display: none;" onchange="previewAvatar(this)">
        </div>
        <h3>{{ auth()->user()->name ?? 'Guest User' }}</h3>
        <p>{{ auth()->user()->email ?? 'No email provided' }}</p>
        @php
            $orderCount = \App\Models\Order::where('user_id', auth()->id())->count();
            $favCount = \App\Models\Favorite::where('user_id', auth()->id())->count();
        @endphp
        <div class="stat-box">
            <div class="stat-item">
                <h4>{{ $orderCount }}</h4>
                <span>Orders</span>
            </div>
            <div class="stat-item">
                <h4>{{ $favCount }}</h4>
                <span>Favourites</span>
            </div>
        </div>
    </div>

    <!-- Right Column: Edit Form -->
    <div class="dash-card">
        <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px;">Personal Details</h3>
        
        <div class="form-row">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" class="form-control" name="name" value="{{ auth()->user()->name ?? '' }}" placeholder="Enter your full name">
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" class="form-control" name="mobile" value="{{ auth()->user()->mobile ?? '' }}" placeholder="Enter phone number">
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" class="form-control" name="email" value="{{ auth()->user()->email ?? '' }}" placeholder="Enter email address">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Date of Birth</label>
                    <input type="date" class="form-control" name="dob" value="{{ auth()->user()->dob ?? '' }}">
                </div>
                
                <div class="form-group">
                    <label>Gender</label>
                    <select class="form-control" name="gender">
                        <option value="">Select Gender</option>
                        <option value="Male" {{ (auth()->user()->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                        <option value="Female" {{ (auth()->user()->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        <option value="Other" {{ (auth()->user()->gender ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>
            </div>
            
        <div class="form-group" style="margin-top: 30px;">
            <button type="submit" class="btn-save">Save Changes</button>
        </div>
    </div>
</div>
</form>

<script>
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatar_preview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
