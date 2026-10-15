@extends('layouts.vendor')

@section('title', 'Restaurant Profile')

@push('styles')
<style>
    .p-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .cover-photo {
        width: 100%;
        height: 150px;
        background-image: url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=1200&q=80');
        background-size: cover;
        background-position: center;
        border-radius: 8px;
        margin-bottom: -50px;
    }
    .profile-photo {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid white;
        background: white;
        margin-left: 20px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        object-fit: cover;
    }
    .k-label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .k-input { width: 100%; border-radius: 8px; padding: 10px; border: 1px solid #cbd5e1; }
</style>
@endpush

@section('content')
<form method="POST" action="{{ route('vendor.profile.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h2 class="page-title" style="font-size: 24px; font-weight: 800; margin: 0;">Restaurant Profile <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block;">Manage your restaurant's public information</span></h2>
        <button type="submit" class="btn" style="background: #2563eb; color: white; border-radius: 8px; font-weight: 600;">Save Changes</button>
    </div>

    @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 24px; font-weight: 600;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

<div class="p-card" style="padding-top: 0; padding-left: 0; padding-right: 0; overflow: hidden;">
    <div class="cover-photo" id="bannerImagePreview" style="{{ $restaurant && $restaurant->banner ? 'background-image: url(' . asset('storage/' . $restaurant->banner) . ');' : '' }} position: relative;">
        <input type="file" id="bannerUpload" name="banner" accept="image/*" style="display: none;" onchange="previewBanner(event)">
        <button type="button" class="btn btn-sm" onclick="document.getElementById('bannerUpload').click()" style="position: absolute; top: 20px; right: 20px; background: white; border: 1px solid #e2e8f0; font-weight: 600; font-size: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);"><i class="fa-solid fa-camera me-1"></i> Edit Banner</button>
    </div>
    <div class="d-flex align-items-end mb-4">
        <img src="{{ $restaurant && $restaurant->logo ? asset('storage/' . $restaurant->logo) : 'https://images.unsplash.com/photo-1514933651103-005eec06c04b?w=150&q=80' }}" id="profileImagePreview" class="profile-photo" alt="Restaurant Logo">
        <div class="ms-3 mb-2">
            <input type="file" id="logoUpload" name="logo" accept="image/*" style="display: none;" onchange="previewLogo(event)">
            <button type="button" class="btn btn-sm" onclick="document.getElementById('logoUpload').click()" style="background: white; border: 1px solid #e2e8f0; font-weight: 600; font-size: 12px;"><i class="fa-solid fa-camera me-1"></i> Edit Logo</button>
        </div>
    </div>
    
    <div style="padding: 0 24px;">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="k-label">Restaurant Name</label>
                <input type="text" name="restaurant_name" class="form-control k-input" value="{{ old('restaurant_name', $restaurant->restaurant_name ?? Auth::user()->name) }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="k-label">Contact Number</label>
                <input type="text" name="mobile" class="form-control k-input" value="{{ old('mobile', $restaurant->mobile ?? Auth::user()->mobile) }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="k-label">Full Address</label>
                <textarea name="address" class="form-control k-input" rows="3" placeholder="123 Food Street, Near RTC Bus Stand, Vijayawada, Andhra Pradesh">{{ old('address', $restaurant->address ?? '') }}</textarea>
            </div>

            <div class="col-md-6 mb-3">
                <label class="k-label">Latitude *</label>
                <input type="number" name="latitude" class="form-control k-input"
                       step="any" min="-90" max="90"
                       value="{{ old('latitude', $restaurant->latitude ?? '') }}"
                       placeholder="e.g. 17.3850" required>
            </div>

            <div class="col-md-6 mb-3">
                <label class="k-label">Longitude *</label>
                <input type="number" name="longitude" class="form-control k-input"
                       step="any" min="-180" max="180"
                       value="{{ old('longitude', $restaurant->longitude ?? '') }}"
                       placeholder="e.g. 78.4867" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="k-label">Opening Time</label>
                <input type="time" name="opening_time" class="form-control k-input" value="{{ old('opening_time', $restaurant->opening_time ? \Carbon\Carbon::parse($restaurant->opening_time)->format('H:i') : '') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="k-label">Closing Time</label>
                <input type="time" name="closing_time" class="form-control k-input" value="{{ old('closing_time', $restaurant->closing_time ? \Carbon\Carbon::parse($restaurant->closing_time)->format('H:i') : '') }}">
            </div>
            <div class="col-md-12 mb-3">
                <label class="k-label">Cuisines (Comma separated)</label>
                <input type="text" name="cuisines" class="form-control k-input" value="{{ old('cuisines', $restaurant->cuisines ?? '') }}" placeholder="North Indian, South Indian, Biryani, Chinese">
            </div>
        </div>
    </div>
</div>

</form>

<form method="POST" action="{{ route('vendor.profile.password') }}">
    @csrf
    <div class="p-card" style="margin-top: 24px;">
        <h3 style="font-size: 1.125rem; font-weight: 700; color: #1e293b; margin-bottom: 20px;">Change Password <span style="font-size: 0.875rem; color: #64748b; font-weight: 400; margin-left: 8px;">Update your login credentials</span></h3>
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="k-label">Current Password</label>
                <div class="position-relative">
                    <input type="password" name="current_password" class="form-control k-input" placeholder="Enter current password" required style="padding-right: 40px;">
                    <i class="fa-regular fa-eye position-absolute" style="top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="k-label">New Password</label>
                <div class="position-relative">
                    <input type="password" name="new_password" class="form-control k-input" placeholder="Enter new password (min. 8 characters)" required style="padding-right: 40px;">
                    <i class="fa-regular fa-eye position-absolute" style="top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="k-label">Confirm New Password</label>
                <div class="position-relative">
                    <input type="password" name="new_password_confirmation" class="form-control k-input" placeholder="Confirm new password" required style="padding-right: 40px;">
                    <i class="fa-regular fa-eye position-absolute" style="top: 50%; right: 12px; transform: translateY(-50%); cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
                </div>
            </div>
        </div>
        <div class="mt-3">
            <button type="submit" class="btn" style="background: #0f172a; color: white; font-weight: 700; padding: 10px 24px; border-radius: 8px;">Update Password</button>
        </div>
    </div>
</form>

<script>
    function previewLogo(event) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('profileImagePreview').src = e.target.result;
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    }
    
    function previewBanner(event) {
        if (event.target.files && event.target.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('bannerImagePreview').style.backgroundImage = 'url(' + e.target.result + ')';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
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
