@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<style>
    .wallet-layout-grid { display: grid; grid-template-columns: 1fr 350px; gap: 24px; }
    @media(max-width: 992px) { .wallet-layout-grid { grid-template-columns: 1fr; } }
</style>

<div class="wallet-layout-grid">
    
    <!-- Left Column: Bank Details Form -->
    <div>
        <div class="card">
            <h3 style="margin-bottom: 20px; color: #0f172a;">Bank Details</h3>
            <p style="color: #64748b; font-size: 13px; margin-bottom: 20px;">Please provide your bank account details for receiving incentive payouts.</p>
            
            @if(session('success'))
                <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: 500; font-size: 14px;">
                    <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('driver.wallet.bank_details') }}" method="POST">
                @csrf
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Bank Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="bank_name" value="{{ $driver->bank_name }}" required placeholder="e.g. State Bank of India"
                           style="width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Account Number <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="account_number" value="{{ $driver->account_number }}" required placeholder="Enter account number"
                           style="width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
                
                <div class="form-group" style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">IFSC Code <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="ifsc_code" value="{{ $driver->ifsc_code }}" required placeholder="e.g. SBIN0001234"
                           style="width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none; text-transform: uppercase;">
                </div>
                
                <div class="form-group" style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; font-size: 14px;">Branch Name <span style="color:#ef4444;">*</span></label>
                    <input type="text" name="branch_name" value="{{ $driver->branch_name }}" required placeholder="Enter branch name"
                           style="width: 100%; padding: 10px 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 14px; outline: none;">
                </div>
                
                <button type="submit" style="background: var(--primary); color: white; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                    Save Bank Details
                </button>
            </form>
        </div>
    </div>

    <!-- Right Column: Wallet Balance -->
    <div>
        <div class="card" style="background: linear-gradient(135deg, #1e3a8a, #0f172a); color: white; text-align: center; padding: 40px 24px;">
            <p style="color: #cbd5e1; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Wallet Balance</p>
            <h2 style="font-size: 48px; font-weight: 800; margin: 10px 0 20px 0;">₹{{ number_format($driver->wallet_balance ?? 0, 2) }}</h2>
            <div style="width: 50px; height: 4px; background: #60a5fa; margin: 0 auto 24px auto; border-radius: 2px;"></div>
            <p style="font-size: 13px; color: #94a3b8; margin-bottom: 24px;">This is your total earned incentive from passenger orders.</p>
            
            <form action="{{ route('driver.wallet.withdraw') }}" method="POST" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                @csrf
                <p style="font-size: 13px; color: #cbd5e1; margin-bottom: 15px;">Minimum withdrawal amount is ₹100.00.</p>
                <div style="position: relative; margin-bottom: 16px;">
                    <span style="position: absolute; left: 16px; top: 12px; color: #94a3b8; font-weight: 600;">₹</span>
                    <input type="number" name="amount" required min="100" max="{{ $driver->wallet_balance ?? 0 }}" placeholder="Enter amount" 
                           style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding-left: 36px; width: 100%; height: 44px; border-radius: 8px; outline: none; font-size: 16px;">
                </div>
                <button type="submit" style="background: #3b82f6; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.2s;">
                    Request Withdrawal
                </button>
            </form>
        </div>
    </div>

</div>
@endsection
