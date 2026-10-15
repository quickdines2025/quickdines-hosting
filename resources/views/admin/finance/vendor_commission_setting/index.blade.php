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
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        cursor: pointer;
    }

    .btn-save:hover {
        background: #4f46e5;
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
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Cards */
    .card-settings {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
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
        .form-row {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 576px) {
        .form-row {
            grid-template-columns: 1fr;
        }
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

    .input-wrapper input {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 0.875rem;
        color: #1e293b;
        outline: none;
        background: #fff;
        box-sizing: border-box;
    }

    .input-wrapper input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.08);
    }

    .input-icon-right {
        position: absolute;
        right: 12px;
        top: 10px;
        color: #94a3b8;
        font-size: 0.875rem;
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

    .receipt-label {
        color: #64748b;
    }

    .receipt-val {
        color: #334155;
        font-weight: 500;
        text-align: right;
    }

    .receipt-val.negative {
        color: #dc2626;
    }

    .success-message {
        padding: 12px 16px;
        margin-bottom: 20px;
        background: #dcfce7;
        color: #166534;
        border: 1px solid #bbf7d0;
        border-radius: 8px;
        font-size: 0.875rem;
    }

    .error-message {
        padding: 12px 16px;
        margin-bottom: 20px;
        background: #fee2e2;
        color: #991b1b;
        border: 1px solid #fecaca;
        border-radius: 8px;
        font-size: 0.875rem;
    }
</style>


{{-- Success Message --}}
@if(session('success'))
<div class="success-message">
    <i class="fa-solid fa-circle-check"></i>
    {{ session('success') }}
</div>
@endif


{{-- Validation Errors --}}
@if($errors->any())
<div class="error-message">
    <strong>Please correct the following:</strong>

    <ul style="margin: 8px 0 0 20px;">
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif


<form method="POST"
    action="{{ route('admin.vendor-commission.update') }}"
    id="commissions-form">

    @csrf


    {{-- Page Header --}}
    <div class="page-header">

        <div>
            <h1 class="page-title">
                Vendor Commission Setting
            </h1>

            <div class="breadcrumb">
                <a href="#">Home</a>

                <i class="fa-solid fa-chevron-right"
                    style="font-size:0.7rem; margin:0 8px;">
                </i>

                Vendor Commission Setting
            </div>
        </div>


        <button type="submit" class="btn-save">
            <i class="fa-regular fa-floppy-disk"></i>
            Save Changes
        </button>

    </div>


    <div class="settings-grid">

        {{-- LEFT SIDE --}}
        <div class="settings-main">

            <div class="card-settings">

                <h3 class="card-title">
                    Global Commission Settings
                </h3>

                <p class="card-subtitle">
                    Set default commission rates and fees for vendors.
                </p>


                {{-- First Row --}}
                <div class="form-row">

                    {{-- Example Order Amount --}}
                    <div class="form-group">

                        <label>
                            Example Order Amount (₹)
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="example_order_amount"
                                id="input_order_amount"
                                value="{{ old('example_order_amount', $setting->example_order_amount ?? 1000) }}"
                                required>

                        </div>

                    </div>


                    {{-- Commission Percentage --}}
                    <div class="form-group">

                        <label>
                            Commission (%)
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="commission_percentage"
                                id="input_commission"
                                value="{{ old('commission_percentage', $setting->commission_percentage ?? 18) }}"
                                required>

                            <span class="input-icon-right">
                                %
                            </span>

                        </div>

                    </div>


                    {{-- GST Percentage --}}
                    <div class="form-group">

                        <label>
                            GST on Commission (%)
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                max="100"
                                name="gst_percentage"
                                id="input_gst"
                                value="{{ old('gst_percentage', $setting->gst_percentage ?? 18) }}"
                                required>

                            <span class="input-icon-right">
                                %
                            </span>

                        </div>

                    </div>


                    {{-- Platform Fee --}}
                    <div class="form-group">

                        <label>
                            Platform Fee (₹)
                        </label>

                        <div class="input-wrapper">

                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                name="platform_fee"
                                id="input_platform_fee"
                                value="{{ old('platform_fee', $setting->platform_fee ?? 10) }}"
                                required>

                        </div>

                    </div>

                </div>


                {{-- Information --}}
                <div style="
                    background:#f8fafc;
                    border:1px solid #e2e8f0;
                    border-radius:8px;
                    padding:14px 16px;
                    font-size:0.8rem;
                    color:#64748b;
                    line-height:1.6;
                ">

                    <strong style="color:#334155;">
                        Calculation Logic
                    </strong>

                    <br>

                    Commission = Order Amount × Commission %

                    <br>

                    GST = Commission × GST %

                    <br>

                    Total Deduction = Commission + GST + Platform Fee

                    <br>

                    Vendor Receives = Order Amount − Total Deduction

                </div>

            </div>

        </div>


        {{-- RIGHT SIDE --}}
        <div class="settings-sidebar">

            <div class="card-settings">

                <h3 class="card-title"
                    style="margin-bottom:24px;">
                    Example Calculation
                </h3>


                {{-- Order Amount --}}
                <div class="receipt-row">

                    <span class="receipt-label">
                        Order Amount
                    </span>

                    <span class="receipt-val"
                        id="calc_order_amount">
                        ₹0.00
                    </span>

                </div>


                {{-- Commission --}}
                <div class="receipt-row">

                    <span class="receipt-label"
                        id="calc_commission_label">

                        Commission (18%)

                    </span>

                    <span class="receipt-val negative"
                        id="calc_commission_val">

                        - ₹0.00

                    </span>

                </div>


                {{-- GST --}}
                <div class="receipt-row">

                    <span class="receipt-label"
                        id="calc_gst_label">

                        GST on Commission (18%)

                    </span>

                    <span class="receipt-val negative"
                        id="calc_gst_val">

                        - ₹0.00

                    </span>

                </div>


                {{-- Platform Fee --}}
                <div class="receipt-row">

                    <span class="receipt-label">

                        Platform Fee

                    </span>

                    <span class="receipt-val negative"
                        id="calc_platform_val">

                        - ₹0.00

                    </span>

                </div>


                {{-- Total Deduction --}}
                <div class="receipt-row">

                    <span class="receipt-label"
                        style="font-weight:600; color:#334155;">

                        Total Deduction

                    </span>

                    <span class="receipt-val negative"
                        style="font-weight:600;"
                        id="calc_total_val">

                        - ₹0.00

                    </span>

                </div>


                {{-- Vendor Receives --}}
                <div class="receipt-row total">

                    <span>
                        Vendor Receives
                    </span>

                    <span id="calc_vendor_receives">
                        ₹0.00
                    </span>

                </div>

            </div>

        </div>

    </div>

</form>


@endsection


@section('scripts')

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const inputOrderAmount =
            document.getElementById('input_order_amount');

        const inputCommission =
            document.getElementById('input_commission');

        const inputGst =
            document.getElementById('input_gst');

        const inputPlatformFee =
            document.getElementById('input_platform_fee');


        function calculate() {

            // Get values
            const orderAmount =
                parseFloat(inputOrderAmount.value) || 0;

            const commissionPercentage =
                parseFloat(inputCommission.value) || 0;

            const gstPercentage =
                parseFloat(inputGst.value) || 0;

            const platformFee =
                parseFloat(inputPlatformFee.value) || 0;


            // Commission
            const commission =
                orderAmount * (commissionPercentage / 100);


            // GST on commission
            const gst =
                commission * (gstPercentage / 100);


            // Total deduction
            const totalDeduction =
                commission + gst + platformFee;


            // Vendor receives
            const vendorReceives =
                orderAmount - totalDeduction;


            // Display order amount
            document.getElementById('calc_order_amount').innerText =
                `₹${orderAmount.toFixed(2)}`;


            // Commission label
            document.getElementById('calc_commission_label').innerText =
                `Commission (${commissionPercentage}%)`;


            // Commission value
            document.getElementById('calc_commission_val').innerText =
                `- ₹${commission.toFixed(2)}`;


            // GST label
            document.getElementById('calc_gst_label').innerText =
                `GST on Commission (${gstPercentage}%)`;


            // GST value
            document.getElementById('calc_gst_val').innerText =
                `- ₹${gst.toFixed(2)}`;


            // Platform fee
            document.getElementById('calc_platform_val').innerText =
                `- ₹${platformFee.toFixed(2)}`;


            // Total deduction
            document.getElementById('calc_total_val').innerText =
                `- ₹${totalDeduction.toFixed(2)}`;


            // Vendor receives
            document.getElementById('calc_vendor_receives').innerText =
                `₹${vendorReceives.toFixed(2)}`;

        }


        // Live calculation
        inputOrderAmount.addEventListener('input', calculate);

        inputCommission.addEventListener('input', calculate);

        inputGst.addEventListener('input', calculate);

        inputPlatformFee.addEventListener('input', calculate);


        // Initial calculation
        calculate();

    });
</script>

@endsection
