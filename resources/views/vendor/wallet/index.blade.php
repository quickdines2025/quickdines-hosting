@extends('layouts.vendor')

@section('title', 'Wallet & Earnings')

@section('content')
<style>
    .wallet-layout-grid { display: grid; grid-template-columns: 1fr 350px; gap: 24px; }
    @media(max-width: 992px) { .wallet-layout-grid { grid-template-columns: 1fr; } }
    .table-responsive { overflow-x: auto; -webkit-overflow-scrolling: touch; }
</style>
<div class="wallet-layout-grid">
    
    <!-- Left Column: Transactions History -->
    <div>
        <div class="card">
            <h3 style="margin-bottom: 20px; color: #0f172a;">Transaction History</h3>
            @if($transactions->count() > 0)
            <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $txn)
                    <tr>
                        <td>
                            <div style="font-weight: 600; color: #1e293b;">{{ $txn->created_at->format('M d, Y') }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $txn->created_at->format('h:i A') }}</div>
                        </td>
                        <td>
                            @if($txn->type == 'credit')
                                <span style="color: #166534; font-weight: 600;"><i class="fa-solid fa-arrow-down"></i> Credit</span>
                            @else
                                <span style="color: #991b1b; font-weight: 600;"><i class="fa-solid fa-arrow-up"></i> Debit (Withdrawal)</span>
                            @endif
                        </td>
                        <td style="font-weight: 700; font-size: 16px;">₹{{ number_format($txn->amount, 2) }}</td>
                        <td>
                            @if($txn->status == 'completed')
                                <span class="badge badge-delivered">Completed</span>
                            @elseif($txn->status == 'pending')
                                <span class="badge badge-pending">Pending</span>
                            @else
                                <span class="badge badge-preparing">Failed</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            @else
            <div style="text-align: center; padding: 40px 0; color: #64748b;">
                <i class="fa-solid fa-money-bill-transfer" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
                <p>No transactions yet.</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Right Column: Balance & Withdraw -->
    <div>
        <div class="card" style="background: linear-gradient(135deg, #202a5d, #111827); color: white; text-align: center; padding: 40px 24px;">
            <p style="color: #cbd5e1; font-weight: 600; font-size: 14px; text-transform: uppercase; letter-spacing: 1px;">Available Balance</p>
            <h2 style="font-size: 48px; font-weight: 800; margin: 10px 0 20px 0;">₹{{ number_format($restaurant->wallet_balance ?? 0, 2) }}</h2>
            <div style="width: 50px; height: 4px; background: var(--primary); margin: 0 auto 24px auto; border-radius: 2px;"></div>
            <p style="font-size: 13px; color: #94a3b8; margin-bottom: 24px;">Minimum withdrawal amount is ₹100.00. Processing takes 1-2 business days.</p>
            
            <form action="{{ route('vendor.wallet.withdraw') }}" method="POST" style="background: rgba(255,255,255,0.05); padding: 20px; border-radius: 12px; border: 1px solid rgba(255,255,255,0.1);">
                @csrf
                <div style="position: relative; margin-bottom: 16px;">
                    <span style="position: absolute; left: 16px; top: 12px; color: #94a3b8; font-weight: 600;">₹</span>
                    <input type="number" name="amount" required min="100" max="{{ $restaurant->wallet_balance ?? 0 }}" placeholder="Enter amount" 
                           style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; padding-left: 36px;">
                </div>
                <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; font-size: 16px; padding: 12px;">Request Withdrawal</button>
            </form>
        </div>
    </div>
</div>
@endsection
