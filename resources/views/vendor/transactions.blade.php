@extends('layouts.vendor')

@section('title', 'Transactions')

@push('styles')
<style>
    .trx-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        border: 1px solid #e2e8f0;
    }
    .trx-table th { font-weight: 600; color: #64748b; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 2px solid #f1f5f9; padding-bottom: 12px; }
    .trx-table td { padding: 16px 0; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
    .trx-table tr:last-child td { border-bottom: none; }
    
    .status-badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        display: inline-block;
    }
    .status-success { background: #dcfce7; color: #16a34a; }
    .status-pending { background: #fef3c7; color: #d97706; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <h2 class="page-title" style="font-size: 24px; font-weight: 800; margin: 0;">Transactions <span style="font-size: 13px; font-weight: 500; color: #64748b; display: block;">History of all your payments and deductions</span></h2>
    <button class="btn" style="background: white; border: 1px solid #e2e8f0; border-radius: 8px; font-weight: 600;"><i class="fa-solid fa-download me-2"></i> Download Report</button>
</div>

<div class="trx-card">
    <div style="overflow-x: auto;">
    <table class="table trx-table mb-0">
        <thead>
            <tr>
                <th>Date & Time</th>
                <th>Transaction ID</th>
                <th>Description</th>
                <th>Type</th>
                <th>Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $txn)
            <tr>
                <td><div style="font-weight: 600;">{{ $txn->created_at->format('d M, Y') }}</div><div style="font-size: 12px; color: #64748b;">{{ $txn->created_at->format('h:i A') }}</div></td>
                <td style="font-weight: 600; color: #0f172a;">TXN-{{ str_pad($txn->id, 6, '0', STR_PAD_LEFT) }}</td>
                <td>
                    @if($txn->type == 'credit')
                        Order #ORD-{{ $txn->order_id ?? 'N/A' }} Payment
                    @else
                        Bank Withdrawal Request
                    @endif
                    
                    @if($txn->reference_id)
                        <div style="font-size: 11px; color: #64748b; margin-top: 4px;">
                            <i class="fa-solid fa-receipt me-1"></i> {{ $txn->reference_id }}
                        </div>
                    @endif
                </td>
                <td>
                    @if($txn->type == 'credit')
                        <span style="color: #10b981; font-weight: 600;"><i class="fa-solid fa-arrow-down me-1"></i> Credit</span>
                    @else
                        <span style="color: #ef4444; font-weight: 600;"><i class="fa-solid fa-arrow-up me-1"></i> Debit</span>
                    @endif
                </td>
                <td style="font-weight: 800; font-size: 16px;">
                    @if($txn->type == 'credit')
                        + ₹{{ number_format($txn->amount, 0) }}
                    @else
                        - ₹{{ number_format($txn->amount, 0) }}
                    @endif
                </td>
                <td>
                    @if($txn->status == 'completed')
                        <span class="status-badge status-success">Completed</span>
                    @elseif($txn->status == 'pending')
                        <span class="status-badge status-pending">Processing</span>
                    @else
                        <span class="status-badge" style="background: #fee2e2; color: #b91c1c;">Failed</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                    No transactions found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
