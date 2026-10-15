@extends('layouts.admin')

@section('content')
<style>
    .page-header {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
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
    
    .btn-save {
        background: #6366f1;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(99,102,241,0.2);
    }

    /* Tabs */
    .settings-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 32px;
        border-bottom: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .tab-item {
        padding: 12px 0;
        color: #64748b;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        position: relative;
    }
    .tab-item.active {
        color: #6366f1;
    }
    .tab-item.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 0;
        width: 100%;
        height: 2px;
        background: #6366f1;
    }

    /* Layout */
    .settings-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 24px;
    }
    @media (max-width: 992px) {
        .settings-grid { grid-template-columns: 1fr; }
    }

    /* Cards */
    .card-settings {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        border: 1px solid #f1f5f9;
        margin-bottom: 24px;
    }
    .card-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 4px 0;
    }
    .card-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 24px;
    }

    /* Form Elements */
    .form-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    @media (max-width: 1200px) {
        .form-row { grid-template-columns: repeat(2, 1fr); }
    }
    @media (max-width: 576px) {
        .form-row { grid-template-columns: 1fr; }
    }
    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .form-group label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #334155;
    }
    .input-wrapper {
        position: relative;
    }
    .input-wrapper input, .input-wrapper select {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: #fff;
    }
    .input-icon-right {
        position: absolute;
        right: 12px;
        top: 10px;
        color: #94a3b8;
        font-size: 0.875rem;
    }

    /* Radios & Toggles */
    .radio-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.875rem;
        color: #334155;
        cursor: pointer;
    }
    .radio-option input[type="radio"] {
        accent-color: #6366f1;
        width: 16px;
        height: 16px;
    }

    .toggle-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0;
        border-bottom: 1px solid #f1f5f9;
    }
    .toggle-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .toggle-label {
        font-size: 0.875rem;
        color: #334155;
        font-weight: 500;
    }
    .switch {
        position: relative;
        display: inline-block;
        width: 44px;
        height: 24px;
    }
    .switch input { 
        opacity: 0;
        width: 0;
        height: 0;
    }
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
    input:checked + .slider {
        background-color: #6366f1;
    }
    input:checked + .slider:before {
        transform: translateX(20px);
    }

    /* Receipt preview */
    .receipt-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
        font-size: 0.875rem;
    }
    .receipt-row.total {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px dashed #cbd5e1;
        font-weight: 700;
        color: #16a34a;
        font-size: 1rem;
    }
    .receipt-label { color: #64748b; }
    .receipt-val { color: #334155; font-weight: 500; text-align: right;}
    .receipt-val.negative { color: #dc2626; }
</style>

<form method="POST" action="{{ route('admin.finance.commissions.save') }}" id="commissions-form">
@csrf
<div class="page-header">
    <div>
        <h1 class="page-title">Commission Settings</h1>
        <div class="breadcrumb">
            <a href="#">Home</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Commission Settings
        </div>
    </div>
    <button type="submit" class="btn-save"><i class="fa-regular fa-floppy-disk"></i> Save Changes</button>
</div>

<div class="settings-grid">
    <div class="settings-main">
        <div class="card-settings">
            <h3 class="card-title">Global Commission Settings</h3>
            <p class="card-subtitle">Set default commission rates for platform.</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Platform Commission (₹)</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.01" name="platform_commission" id="input_platform" value="{{ $settings['platform_commission'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>GST on Commission (%)</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.01" name="gst_on_commission" id="input_gst" value="{{ $settings['gst_on_commission'] }}">
                        <span class="input-icon-right">%</span>
                    </div>
                </div>
                <div class="form-group">
                    <label>Minimum Commission (₹)</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.01" name="min_commission" id="input_min" value="{{ $settings['min_commission'] }}">
                    </div>
                </div>
                <div class="form-group">
                    <label>Maximum Commission (₹)</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.01" name="max_commission" id="input_max" value="{{ $settings['max_commission'] }}">
                    </div>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label>Driver Commission Per Order (₹)</label>
                    <div class="input-wrapper">
                        <input type="number" step="0.01" name="driver_commission_per_order" id="input_driver_comm" value="{{ $settings['driver_commission_per_order'] ?? 10 }}">
                    </div>
                </div>
            </div>


    </div>

    <div class="settings-sidebar">
        <div class="card-settings">
            <h3 class="card-title" style="margin-bottom:24px;">Example Calculation</h3>
            
            <div class="receipt-row">
                <span class="receipt-label">Order Amount</span>
                <span class="receipt-val">₹500.00</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label" id="calc_platform_label">Platform Commission</span>
                <span class="receipt-val negative" id="calc_platform_val">- ₹0.00</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label" id="calc_gst_label">GST on Commission ({{ $settings['gst_on_commission'] }}%)</span>
                <span class="receipt-val negative" id="calc_gst_val">- ₹0.00</span>
            </div>
            <div class="receipt-row">
                <span class="receipt-label" style="font-weight:600; color:#334155;">Total Commission</span>
                <span class="receipt-val negative" style="font-weight:600;" id="calc_total_val">- ₹0.00</span>
            </div>
            
            <div class="receipt-row total">
                <span>Partner Receives</span>
                <span id="calc_partner_receives">₹0.00</span>
            </div>
        </div>
    </div>
</div>
</form>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputPlatform = document.getElementById('input_platform');
        const inputGst = document.getElementById('input_gst');
        const inputMin = document.getElementById('input_min');
        const inputMax = document.getElementById('input_max');
        const inputDelivery = document.getElementById('input_delivery');

        function calculate() {
            let orderAmount = 500.00;
            let platformAmt = parseFloat(inputPlatform.value) || 0;
            let gstPct = parseFloat(inputGst.value) || 0;
            let minComm = parseFloat(inputMin.value) || 0;
            let maxComm = parseFloat(inputMax.value) || 0;

            let platformComm = platformAmt;
            
            if (minComm > 0 && platformComm < minComm) platformComm = minComm;
            if (maxComm > 0 && platformComm > maxComm) platformComm = maxComm;

            let gstComm = platformComm * (gstPct / 100);
            let totalComm = platformComm + gstComm;
            let partnerReceives = orderAmount - totalComm;

            document.getElementById('calc_platform_label').innerText = `Platform Commission`;
            document.getElementById('calc_gst_label').innerText = `GST on Commission (${gstPct}%)`;

            document.getElementById('calc_platform_val').innerText = `- ₹${platformComm.toFixed(2)}`;
            document.getElementById('calc_gst_val').innerText = `- ₹${gstComm.toFixed(2)}`;
            document.getElementById('calc_total_val').innerText = `- ₹${totalComm.toFixed(2)}`;
            document.getElementById('calc_partner_receives').innerText = `₹${partnerReceives.toFixed(2)}`;
        }

        inputPlatform.addEventListener('input', calculate);
        inputGst.addEventListener('input', calculate);
        inputMin.addEventListener('input', calculate);
        inputMax.addEventListener('input', calculate);

        // Initial calculation
        calculate();
    });
</script>
