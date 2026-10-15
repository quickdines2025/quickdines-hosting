@extends('layouts.admin')

@section('title', 'Edit Restaurant')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }
    .breadcrumb-custom {
        font-size: 0.85rem;
        color: var(--text-muted);
    }
    .breadcrumb-custom a {
        color: var(--text-muted);
        text-decoration: none;
    }
    .breadcrumb-custom a:hover {
        color: var(--blue);
    }
    .breadcrumb-custom span {
        margin: 0 8px;
    }
    
    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        padding: 30px;
    }
    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid var(--border-color);
    }
    .form-group {
        margin-bottom: 20px;
    }
    .form-label {
        font-weight: 500;
        color: var(--text-main);
        margin-bottom: 8px;
        display: block;
        font-size: 0.9rem;
    }
    .form-control, .form-select {
        border-radius: 8px;
        border: 1px solid #9ca3af; /* Darker border for visibility */
        padding: 12px 15px;
        font-size: 0.95rem;
        color: #111827; /* Darker text */
        background-color: #ffffff;
    }
    .form-control::placeholder {
        color: #6b7280;
    }
    .form-control:focus, .form-select:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        outline: none;
    }
    .btn-submit {
        background: #4f46e5;
        border: none;
        font-weight: 500;
        padding: 12px 25px;
        border-radius: 8px;
        color: white;
        transition: all 0.2s;
    }
    .btn-submit:hover {
        background: #4338ca;
        color: white;
    }
    .btn-cancel {
        background: white;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        font-weight: 500;
        padding: 12px 25px;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-cancel:hover {
        background: #f9fafb;
        color: var(--text-main);
    }
    .current-img-preview {
        max-width: 100px;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
        border: 1px solid var(--border-color);
    }
    .current-banner-preview {
        max-width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        margin-top: 10px;
        display: block;
        border: 1px solid var(--border-color);
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Restaurant</h1>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">Home</a> <span>&gt;</span> 
            <a href="{{ route('admin.vendors.index') }}">Restaurants</a> <span>&gt;</span> 
            Edit Details
        </div>
    </div>
</div>

<div class="form-card">
    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius: 8px;">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.vendors.update', $vendor->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6">
                <div class="form-section-title">Basic Information</div>
                
                <div class="form-group">
                    <label class="form-label">Restaurant Name *</label>
                    <input type="text" name="restaurant_name" class="form-control" value="{{ old('restaurant_name', $vendor->restaurant_name) }}" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Owner Name</label>
                    <input type="text" name="owner_name" class="form-control" value="{{ old('owner_name', $vendor->owner_name) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Mobile Number</label>
                    <input type="text" name="mobile" class="form-control" value="{{ old('mobile', $vendor->mobile) }}">
                </div>
                
                <div class="form-group">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="{{ old('city', $vendor->city) }}" placeholder="e.g. Hyderabad">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Full Address</label>
                    <textarea name="address" class="form-control" rows="3">{{ old('address', $vendor->address) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Latitude *</label>
                            <input type="number" name="latitude" class="form-control" step="any" min="-90" max="90" value="{{ old('latitude', $vendor->latitude) }}" placeholder="e.g. 17.3850" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Longitude *</label>
                            <input type="number" name="longitude" class="form-control" step="any" min="-180" max="180" value="{{ old('longitude', $vendor->longitude) }}" placeholder="e.g. 78.4867" required>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-section-title">Details & Media</div>
                
                <div class="form-group">
                    <label class="form-label">Cuisines (comma separated)</label>
                    <input type="text" name="cuisines" class="form-control" placeholder="e.g. Biryani, North Indian, Chinese" value="{{ old('cuisines', $vendor->cuisines) }}">
                </div>
                
                <div class="row">
                    <div class="col-md-6 form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="1" {{ old('status', $vendor->status) == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('status', $vendor->status) == '0' ? 'selected' : '' }}>Pending</option>
                            <option value="2" {{ old('status', $vendor->status) == '2' ? 'selected' : '' }}>Suspended</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group">
                        <label class="form-label">Rating</label>
                        <input type="number" step="0.1" name="rating" class="form-control" placeholder="e.g. 4.5" value="{{ old('rating', $vendor->rating) }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Update Logo</label>
                    <input type="file" name="logo" class="form-control" accept="image/*">
                    @if($vendor->logo)
                        <img src="{{ asset('storage/'.$vendor->logo) }}" class="current-img-preview" alt="Current Logo">
                    @endif
                </div>
                
                <div class="form-group">
                    <label class="form-label">Update Banner</label>
                    <input type="file" name="banner" class="form-control" accept="image/*">
                    @if($vendor->banner)
                        <img src="{{ asset('storage/'.$vendor->banner) }}" class="current-banner-preview" alt="Current Banner">
                    @endif
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-end gap-3 mt-4 pt-4" style="border-top: 1px solid var(--border-color);">
            <a href="{{ route('admin.vendors.index') }}" class="btn-cancel">Cancel</a>
            <button type="submit" class="btn-submit">Update Restaurant</button>
        </div>
    </form>
</div>
@endsection
