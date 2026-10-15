@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    .breadcrumb {
        font-size: 0.875rem;
        color: #64748b;
        margin-top: 4px;
    }
    .breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }

    .settings-container {
        display: flex;
        gap: 24px;
    }
    
    /* Left Sidebar Tabs */
    .settings-sidebar {
        width: 250px;
        flex-shrink: 0;
    }
    .nav-tabs-vertical {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .nav-tab-v {
        padding: 12px 16px;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
    }
    .nav-tab-v:hover {
        background: #f1f5f9;
        color: #1e293b;
    }
    .nav-tab-v.active {
        background: #e0e7ff;
        color: #6366f1;
        font-weight: 600;
    }

    /* Main Settings Area */
    .settings-main {
        flex: 1;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        padding: 32px;
    }

    .settings-grid-layout {
        display: grid;
        grid-template-columns: 1.5fr 1fr;
        gap: 48px;
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-top: 0;
        margin-bottom: 24px;
    }
    
    .form-group {
        margin-bottom: 20px;
    }
    .form-group label {
        display: block;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
        margin-bottom: 8px;
    }
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: #fff;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #6366f1;
    }
    
    .radio-group {
        display: flex;
        gap: 16px;
    }
    .radio-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #475569;
        cursor: pointer;
    }

    /* Logo Upload */
    .logo-upload-box {
        border: 1px dashed #cbd5e1;
        border-radius: 8px;
        padding: 16px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
    }
    .logo-preview {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        font-weight: 800;
        color: #f26a21;
    }
    .logo-preview span {
        color: #1e293b;
    }
    .btn-outline-primary {
        background: white;
        border: 1px solid #6366f1;
        color: #6366f1;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
    }

    /* Toggles */
    .setting-card {
        margin-bottom: 32px;
    }
    .setting-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 12px;
    }
    .setting-title {
        font-size: 0.875rem;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 4px 0;
    }
    .setting-desc {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0;
    }

    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0; left: 0; right: 0; bottom: 0;
        background-color: #cbd5e1;
        transition: .4s;
        border-radius: 24px;
    }
    .slider:before {
        position: absolute;
        content: "";
        height: 18px;
        width: 18px;
        left: 3px;
        bottom: 3px;
        background-color: white;
        transition: .4s;
        border-radius: 50%;
    }
    input:checked + .slider { background-color: #6366f1; }
    input:checked + .slider:before { transform: translateX(20px); }

    .btn-save-bottom {
        background: #6366f1;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 32px;
        margin-left: auto;
        cursor: pointer;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Settings</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Settings
        </div>
    </div>
</div>

<div class="settings-container">
    <div class="settings-sidebar">
        <div class="nav-tabs-vertical">
            <a href="#" class="nav-tab-v active" data-tab="platform">Platform Settings</a>
            <a href="#" class="nav-tab-v" data-tab="orders">Order & Delivery Settings</a>
            <a href="#" class="nav-tab-v" data-tab="vendors">Vendor & Driver Settings</a>
        </div>
    </div>
    
    <div class="settings-main">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="settings-grid-layout">
                
                <!-- Left Column -->
                <div>
                    @if(session('success'))
                        <div style="background: #10b981; color: white; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    <h3 class="section-title">Platform Settings</h3>
                    
                    <div class="form-group">
                        <label>App Name</label>
                        <input type="text" name="site_name" class="form-control" value="{{ setting('site_name', 'QuickDines') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Currency Symbol</label>
                        <input type="text" name="currency_symbol" class="form-control" value="{{ setting('currency_symbol', '₹') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Contact Email</label>
                        <input type="email" name="contact_email" class="form-control" value="{{ setting('contact_email', 'support@quickdines.com') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Contact Phone</label>
                        <input type="text" name="contact_phone" class="form-control" value="{{ setting('contact_phone', '+91 98765 43210') }}">
                    </div>

                    <h3 class="section-title" style="margin-top: 40px;">Order & Delivery Settings</h3>
                    
                    <div class="form-group">
                        <label>Minimum Order Amount</label>
                        <input type="number" name="min_order_amount" class="form-control" value="{{ setting('min_order_amount', '100') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Base Delivery Fee</label>
                        <input type="number" name="base_delivery_fee" class="form-control" value="{{ setting('base_delivery_fee', '20') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Delivery Fee per KM</label>
                        <input type="number" name="delivery_fee_per_km" class="form-control" value="{{ setting('delivery_fee_per_km', '5') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Maximum Delivery Radius (KM)</label>
                        <input type="number" name="max_delivery_radius" class="form-control" value="{{ setting('max_delivery_radius', '10') }}">
                    </div>
                </div>
                
                <!-- Right Column -->
                <div>
                    <h3 class="section-title">Vendor & Driver Settings</h3>
                    
                    <div class="form-group">
                        <label>Default Platform Commission (%)</label>
                        <input type="number" step="0.1" name="default_commission" class="form-control" value="{{ setting('default_commission', '10') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>Driver Earnings per Delivery</label>
                        <input type="number" name="driver_earnings" class="form-control" value="{{ setting('driver_earnings', '15') }}">
                    </div>
                    
                    <div class="setting-card" style="margin-top: 32px;">
                        <div class="setting-header">
                            <div>
                                <p class="setting-title">Auto-Assign Drivers</p>
                                <p class="setting-desc">Automatically find and assign nearest drivers to ready orders.</p>
                            </div>
                            <label class="switch">
                                <!-- Hidden input for unchecked state -->
                                <input type="hidden" name="auto_assign_drivers" value="0">
                                <input type="checkbox" name="auto_assign_drivers" value="1" {{ setting('auto_assign_drivers', '1') == '1' ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-save-bottom"><i class="fa-solid fa-check"></i> Save Changes</button>
        </form>
    </div>
</div>
@endsection
