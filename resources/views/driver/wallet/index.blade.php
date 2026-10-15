@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card" style="background: linear-gradient(135deg, #202a5d, #111827); color: white; text-align: center; padding: 30px 20px;">
    <p style="color: #cbd5e1; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Wallet Balance</p>
    <h2 style="font-size: 40px; font-weight: 800; margin: 8px 0 16px 0;">₹{{ number_format($driver->wallet_balance ?? 0, 2) }}</h2>
    <p style="font-size: 12px; color: #94a3b8;">Incentives are credited automatically for completed midway orders.</p>
</div>

<h3 style="font-size: 16px; margin: 24px 0 16px 0; color: #0f172a;">Earnings History</h3>

@if($transactions->count() > 0)
    <div class="card" style="padding: 0;">
        @foreach($transactions as $txn)
        <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; border-bottom: 1px solid #f1f5f9;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; 
                            {{ $txn->type == 'credit' ? 'background: #dcfce3; color: #166534;' : 'background: #fee2e2; color: #991b1b;' }}">
                    <i class="fa-solid fa-{{ $txn->type == 'credit' ? 'arrow-down' : 'arrow-up' }}"></i>
                </div>
                <div>
                    <div style="font-size: 14px; font-weight: 600; color: #1e293b;">{{ ucfirst($txn->type) }}</div>
                    <div style="font-size: 11px; color: #64748b;">{{ $txn->created_at->format('M d, Y h:i A') }}</div>
                </div>
            </div>
            <div style="text-align: right;">
                <div style="font-size: 15px; font-weight: 700; {{ $txn->type == 'credit' ? 'color: #166534;' : 'color: #0f172a;' }}">
                    {{ $txn->type == 'credit' ? '+' : '-' }}₹{{ number_format($txn->amount, 2) }}
                </div>
                <div style="font-size: 10px; color: #94a3b8; text-transform: uppercase;">{{ $txn->status }}</div>
            </div>
        </div>
        @endforeach
    </div>
@else
    <div style="text-align: center; padding: 40px 0; color: #94a3b8;">
        <i class="fa-solid fa-coins" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p style="font-size: 14px;">No earnings history yet.</p>
    </div>
@endif
@endsection
