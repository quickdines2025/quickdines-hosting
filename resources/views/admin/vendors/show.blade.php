@extends('layouts.admin')

@section('title', 'Restaurant Details')

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
    
    .profile-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        overflow: hidden;
        margin-bottom: 30px;
    }
    
    .profile-banner {
        height: 200px;
        background-color: #f3f4f6;
        background-position: center;
        background-size: cover;
        position: relative;
    }
    
    .profile-header {
        display: flex;
        padding: 0 30px 30px 30px;
        margin-top: -50px;
        position: relative;
        align-items: flex-end;
        gap: 20px;
    }
    
    .profile-logo {
        width: 120px;
        height: 120px;
        border-radius: 16px;
        border: 4px solid white;
        background-color: white;
        object-fit: cover;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        color: var(--text-muted);
    }
    
    .profile-name-area {
        flex: 1;
        padding-bottom: 5px;
    }
    
    .profile-name {
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 5px 0;
    }
    
    .profile-rating {
        font-size: 0.95rem;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .profile-rating i { color: #f59e0b; }
    
    .status-badge {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .status-active { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .status-pending { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .status-suspended { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    
    .profile-actions {
        padding-bottom: 5px;
    }
    .btn-edit {
        background: white;
        border: 1px solid var(--border-color);
        color: var(--text-main);
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-edit:hover { background: #f9fafb; color: var(--text-main); }
    
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 30px;
        padding: 30px;
        border-top: 1px solid var(--border-color);
    }
    
    .info-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-main);
        margin-bottom: 20px;
    }
    
    .info-item {
        margin-bottom: 15px;
        display: flex;
        gap: 15px;
    }
    
    .info-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: #f3f4f6;
        color: var(--text-muted);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
    }
    
    .info-content { flex: 1; }
    .info-label { font-size: 0.8rem; color: var(--text-muted); font-weight: 500; margin-bottom: 3px; }
    .info-value { font-size: 0.95rem; color: var(--text-main); font-weight: 500; }
    
    .category-tags { display: flex; gap: 8px; flex-wrap: wrap; margin-top: 5px; }
    .category-tag {
        font-size: 0.75rem;
        padding: 4px 10px;
        border-radius: 12px;
        background: rgba(59, 130, 246, 0.1);
        color: var(--blue);
        font-weight: 500;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Restaurant Details</h1>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">Home</a> <span>&gt;</span> 
            <a href="{{ route('admin.vendors.index') }}">Restaurants</a> <span>&gt;</span> 
            View Details
        </div>
    </div>
</div>

<div class="profile-card">
    @php
        $bannerUrl = $vendor->banner ? asset('storage/'.$vendor->banner) : '';
        $statusClass = 'status-pending';
        $statusText = 'Pending';
        if ($vendor->status == 1) { $statusClass = 'status-active'; $statusText = 'Active'; }
        if ($vendor->status == 2) { $statusClass = 'status-suspended'; $statusText = 'Suspended'; }
        
        $categories = array_filter(array_map('trim', explode(',', $vendor->cuisines)));
    @endphp
    
    <div class="profile-banner" style="{{ $bannerUrl ? 'background-image: url('.$bannerUrl.');' : 'background: linear-gradient(135deg, #e0e7ff 0%, #c7d2fe 100%);' }}"></div>
    
    <div class="profile-header">
        @if($vendor->logo)
            <img src="{{ asset('storage/'.$vendor->logo) }}" class="profile-logo" alt="Logo">
        @else
            <div class="profile-logo"><i class="fa-solid fa-store"></i></div>
        @endif
        
        <div class="profile-name-area">
            <h2 class="profile-name">{{ $vendor->restaurant_name }}</h2>
            <div class="profile-rating">
                <i class="fa-solid fa-star"></i> {{ $vendor->rating ?: 'N/A' }} Rating
                <span class="mx-2">•</span>
                <span class="status-badge {{ $statusClass }}">{{ $statusText }}</span>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="{{ route('admin.vendors.edit', $vendor->id) }}" class="btn-edit">
                <i class="fa-solid fa-pen-to-square"></i> Edit Details
            </a>
        </div>
    </div>
    
    <div class="info-grid">
        <!-- Column 1 -->
        <div>
            <div class="info-section-title">Contact Information</div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-user"></i></div>
                <div class="info-content">
                    <div class="info-label">Owner Name</div>
                    <div class="info-value">{{ $vendor->owner_name ?: 'Not Provided' }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                <div class="info-content">
                    <div class="info-label">Mobile Number</div>
                    <div class="info-value">{{ $vendor->mobile ?: 'Not Provided' }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="info-content">
                    <div class="info-label">Full Address</div>
                    <div class="info-value">{{ $vendor->address ?: 'Not Provided' }}</div>
                </div>
            </div>
        </div>
        
        <!-- Column 2 -->
        <div>
            <div class="info-section-title">Restaurant Details</div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-utensils"></i></div>
                <div class="info-content">
                    <div class="info-label">Cuisines</div>
                    @if(count($categories) > 0)
                        <div class="category-tags">
                            @foreach($categories as $cat)
                                <span class="category-tag">{{ $cat }}</span>
                            @endforeach
                        </div>
                    @else
                        <div class="info-value text-muted">No cuisines specified</div>
                    @endif
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-wallet"></i></div>
                <div class="info-content">
                    <div class="info-label">Wallet Balance</div>
                    <div class="info-value">₹ {{ number_format($vendor->wallet_balance, 2) }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-calendar-alt"></i></div>
                <div class="info-content">
                    <div class="info-label">Registered On</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($vendor->created_at)->format('F d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
        
        <!-- Column 3 -->
        <div>
            <div class="info-section-title">Bank Details</div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-building-columns"></i></div>
                <div class="info-content">
                    <div class="info-label">Bank Name</div>
                    <div class="info-value">{{ $vendor->bank_name ?: 'Not Provided' }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-money-check"></i></div>
                <div class="info-content">
                    <div class="info-label">Account Number</div>
                    <div class="info-value">{{ $vendor->account_number ?: 'Not Provided' }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-qrcode"></i></div>
                <div class="info-content">
                    <div class="info-label">IFSC Code</div>
                    <div class="info-value">{{ $vendor->ifsc_code ?: 'Not Provided' }}</div>
                </div>
            </div>
            
            <div class="info-item">
                <div class="info-icon"><i class="fa-solid fa-code-branch"></i></div>
                <div class="info-content">
                    <div class="info-label">Branch Name</div>
                    <div class="info-value">{{ $vendor->branch_name ?: 'Not Provided' }}</div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Assigned Buses Section -->
    <div class="assigned-buses-section" id="buses" style="padding: 30px; border-top: 1px solid var(--border-color); background: #fafafa;">
        <div class="info-section-title" style="margin-bottom: 20px;"><i class="fa-solid fa-bus text-muted me-2"></i> Assigned Buses ({{ $vendor->buses->count() }})</div>
        
        @if($vendor->buses->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px;">
                @foreach($vendor->buses as $bus)
                    @php
                        $bStatusClass = $bus->status == 1 ? 'status-active' : 'status-inactive';
                        $bStatusText = $bus->status == 1 ? 'Active' : 'Inactive';
                        $routeName = '';
                        if($bus->busRoute) {
                            $routeName = $bus->busRoute->source . ' → ' . $bus->busRoute->destination;
                        } else {
                            $routeName = $bus->route_from . ' → ' . $bus->route_to;
                        }
                    @endphp
                    <div style="background: white; border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; display: flex; align-items: flex-start; gap: 15px; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                        <div style="width: 48px; height: 48px; border-radius: 10px; background: rgba(99, 102, 241, 0.1); color: #6366f1; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; flex-shrink: 0;">
                            <i class="fa-solid fa-bus"></i>
                        </div>
                        <div style="flex: 1;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <div style="font-weight: 700; color: var(--text-main); font-size: 1.05rem;">{{ $bus->bus_number }}</div>
                                <span class="status-badge {{ $bStatusClass }}" style="font-size: 0.7rem; padding: 3px 8px;">{{ $bStatusText }}</span>
                            </div>
                            <div style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 10px;">{{ $bus->bus_name }} • {{ $bus->operator }}</div>
                            <div style="font-size: 0.8rem; font-weight: 500; color: var(--text-main); background: #f3f4f6; padding: 6px 10px; border-radius: 6px; display: inline-block;">
                                <i class="fa-solid fa-route me-1 text-muted"></i> {{ $routeName }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="padding: 30px; text-align: center; background: white; border-radius: 12px; border: 1px dashed var(--border-color);">
                <div style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px;"><i class="fa-solid fa-bus-slash"></i></div>
                <div style="font-weight: 600; color: var(--text-muted);">No buses assigned yet</div>
                <div style="font-size: 0.85rem; color: #94a3b8; margin-top: 5px;">Assign buses to this restaurant from the Restaurant Assignment page.</div>
            </div>
        @endif
    </div>
</div>
@endsection
