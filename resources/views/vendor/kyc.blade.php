@extends('layouts.vendor')

@section('title', 'KYC & Bank Details')

@push('styles')
<style>
    .k-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        border: 1px solid #e2e8f0;
        margin-bottom: 24px;
    }
    .k-label { font-size: 13px; font-weight: 600; color: #475569; margin-bottom: 6px; }
    .k-input { border-radius: 8px; padding: 10px; border: 1px solid #cbd5e1; }
    
    .doc-upload {
        border: 2px dashed #cbd5e1;
        border-radius: 8px;
        padding: 20px;
        text-align: center;
        background: #f8fafc;
        cursor: pointer;
    }
    .doc-upload:hover { border-color: #94a3b8; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title" style="font-size: 24px; font-weight: 800;">KYC & Bank Details <span style="font-size: 13px; font-weight: 500; color: #64748b;">Verify your identity to receive payouts</span></h2>
    <span style="background: #dcfce7; color: #16a34a; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700;"><i class="fa-solid fa-check-circle me-1"></i> KYC Verified</span>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="k-card">
            <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Bank Account Information</h4>
            
            @if(session('success'))
                <div class="alert alert-success" style="padding: 10px; font-size: 14px;">{{ session('success') }}</div>
            @endif
            
            <form action="{{ route('vendor.kyc.bank') }}" method="POST">
                @csrf
                <div class="mb-3">
                <label class="k-label">Account Holder Name</label>
                <input type="text" class="form-control k-input" value="{{ Auth::user()->name }}" readonly style="background-color: #f8fafc;">
            </div>
            <div class="mb-3">
                <label class="k-label">Bank Name</label>
                <input type="text" name="bank_name" class="form-control k-input" value="{{ $restaurant->bank_name ?? '' }}" required placeholder="e.g. State Bank of India">
            </div>
            <div class="mb-3">
                <label class="k-label">Account Number</label>
                <input type="text" name="account_number" class="form-control k-input" value="{{ $restaurant->account_number ?? '' }}" required placeholder="Account Number">
            </div>
            <div class="mb-4">
                <label class="k-label">IFSC Code</label>
                <input type="text" name="ifsc_code" class="form-control k-input" value="{{ $restaurant->ifsc_code ?? '' }}" required placeholder="IFSC Code">
            </div>
            <div class="mb-4">
                <label class="k-label">Branch Name</label>
                <input type="text" name="branch_name" class="form-control k-input" value="{{ $restaurant->branch_name ?? '' }}" required placeholder="Branch Name">
            </div>
            <button type="submit" class="btn" style="background: #0f172a; color: white; font-weight: 700; padding: 10px 24px; border-radius: 8px;">Update Bank Details</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="k-card">
            <h4 style="font-size: 16px; font-weight: 700; margin-bottom: 20px;">Identity Documents</h4>
            
            <div class="mb-4">
                <label class="k-label">FSSAI License</label>
                <div class="d-flex align-items-center justify-content-between" style="border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px;">
                    <div style="font-size: 14px; font-weight: 600;"><i class="fa-solid fa-file-pdf text-danger me-2"></i> fssai_certificate.pdf</div>
                    <span style="color: #10b981; font-weight: 600; font-size: 12px;">Verified</span>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="k-label">GST Certificate</label>
                <div class="d-flex align-items-center justify-content-between" style="border: 1px solid #e2e8f0; padding: 12px; border-radius: 8px;">
                    <div style="font-size: 14px; font-weight: 600;"><i class="fa-solid fa-file-pdf text-danger me-2"></i> gst_registration.pdf</div>
                    <span style="color: #10b981; font-weight: 600; font-size: 12px;">Verified</span>
                </div>
            </div>
            
            <div class="mb-3">
                <label class="k-label">Upload New Document</label>
                <div class="doc-upload">
                    <i class="fa-solid fa-cloud-arrow-up fa-2x mb-2" style="color: #94a3b8;"></i>
                    <div style="font-weight: 600; font-size: 14px;">Click to upload or drag and drop</div>
                    <div style="font-size: 12px; color: #64748b;">PDF, JPG or PNG (max. 5MB)</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
