@extends('layouts.support')

@section('title', 'Settings')

@section('content')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .settings-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 24px;
    }
    .card {
        background: white; border-radius: 12px; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); padding: 24px;
    }
    .card-title {
        font-size: 1.1rem; font-weight: 600; color: #0f172a; margin-bottom: 20px;
        padding-bottom: 12px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; gap: 8px;
    }
    .form-group { margin-bottom: 16px; }
    .form-group label { display: block; font-size: 13px; font-weight: 500; color: #475569; margin-bottom: 8px; }
    .form-control {
        width: 100%; padding: 10px 14px; border: 1px solid #cbd5e1; border-radius: 8px;
        font-size: 14px; color: #1e293b; transition: all 0.2s; outline: none;
    }
    .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 3px rgba(59,130,246,0.1); }
    .btn-submit {
        background: #2563eb; color: white; border: none; padding: 10px 20px;
        border-radius: 8px; font-weight: 500; font-size: 14px; cursor: pointer; transition: background 0.2s;
    }
    .btn-submit:hover { background: #1d4ed8; }
</style>

<div class="page-header">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0;">Account Settings</h1>
        <p style="color: #64748b; margin-top: 4px; font-size: 0.875rem;">Manage your support team profile and security</p>
    </div>
</div>

@if(session('success'))
    <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px; display: flex; align-items: center; gap: 8px;">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-size: 14px;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="settings-grid">
    <!-- Profile Settings -->
    <div class="card">
        <h3 class="card-title"><i class="fa-solid fa-user" style="color: #3b82f6;"></i> Profile Information</h3>
        <form action="{{ route('support.settings.profile') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label>Mobile Number</label>
                <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $user->mobile) }}" placeholder="e.g. 1234567890">
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-submit">Save Profile</button>
            </div>
        </form>
    </div>

    <!-- Password Settings -->
    <div class="card">
        <h3 class="card-title"><i class="fa-solid fa-lock" style="color: #10b981;"></i> Change Password</h3>
        <form action="{{ route('support.settings.password') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Current Password</label>
                <input type="password" name="current_password" class="form-control" required placeholder="Enter current password">
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="new_password" class="form-control" required placeholder="Enter new password (min. 8 characters)">
            </div>
            <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required placeholder="Confirm your new password">
            </div>
            <div style="margin-top: 24px;">
                <button type="submit" class="btn-submit" style="background: #10b981;">Update Password</button>
            </div>
        </form>
    </div>
</div>

@endsection
