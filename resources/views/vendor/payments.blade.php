@extends('layouts.vendor')

@section('title', 'Customer Payments')

@section('content')
<div class="card">
    <div style="font-weight: 700; font-size: 1.1rem; color: #1e293b; margin-bottom: 20px;">Customer Payment History</div>
    
    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; text-align: left; border-collapse: collapse;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 12px 16px; color: #64748b;">Order ID</th>
                    <th style="padding: 12px 16px; color: #64748b;">Customer Name</th>
                    <th style="padding: 12px 16px; color: #64748b;">Mobile Number</th>
                    <th style="padding: 12px 16px; color: #64748b;">Seat No</th>
                    <th style="padding: 12px 16px; color: #64748b;">Item Name</th>
                    <th style="padding: 12px 16px; color: #64748b;">Quantity</th>
                    <th style="padding: 12px 16px; color: #64748b;">Method</th>
                    <th style="padding: 12px 16px; color: #64748b;">Amount</th>
                    <th style="padding: 12px 16px; color: #64748b;">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    @php
                        $item = $payment->items->first();
                    @endphp
                    <tr style="border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 12px 16px; font-weight: 600;">#ORD-{{ $payment->id }}</td>
                        <td style="padding: 12px 16px;">{{ $payment->user->name ?? 'Guest' }}</td>
                        <td style="padding: 12px 16px;">{{ $payment->user->mobile ?? 'N/A' }}</td>
                        <td style="padding: 12px 16px; font-weight: 600;">{{ $payment->seat_number ?? 'N/A' }}</td>
                        <td style="padding: 12px 16px;">{{ $item ? ($item->menuItem->name ?? $item->menuItem->item_name) : 'Unknown' }}</td>
                        <td style="padding: 12px 16px;">{{ $item ? $item->quantity : 1 }}</td>
                        <td style="padding: 12px 16px;">
                            <span class="badge {{ $payment->payment_method == 'cod' ? 'badge-pending' : 'badge-delivered' }}">
                                {{ strtoupper($payment->payment_method ?? 'ONLINE') }}
                            </span>
                        </td>
                        <td style="padding: 12px 16px; font-weight: 600;">₹{{ $payment->total_amount }}</td>
                        <td style="padding: 12px 16px;">
                            @if(strtolower($payment->payment_status) == 'paid')
                                <span class="badge badge-delivered">
                                    PAID
                                </span>
                            @else
                                <form action="{{ route('vendor.payments.mark', $payment->id) }}" method="POST" style="display: inline-block;">
                                    @csrf
                                    <button type="submit" class="btn btn-primary" style="font-size: 0.75rem; padding: 4px 10px;">
                                        Mark as Paid
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="padding: 24px; text-align: center; color: #64748b;">No payments found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
