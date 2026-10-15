@extends('layouts.support')

@section('title', 'Cancellation Management')

@section('content')
<style>
    .page-header {
        display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;
    }
    .card {
        background: white; border-radius: 12px; border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden;
    }
    .table { width: 100%; border-collapse: collapse; }
    .table th, .table td { padding: 16px 20px; text-align: left; border-bottom: 1px solid #e2e8f0; }
    .table th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; }
    .table td { font-size: 14px; color: #1e293b; vertical-align: middle; }
    .table tbody tr:hover { background: #f8fafc; }
    
    .badge { padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-cancelled { background: #fee2e2; color: #ef4444; }
    
    .empty-state { padding: 60px 20px; text-align: center; color: #64748b; }
    .empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
    .pagination-wrapper { padding: 20px; border-top: 1px solid #e2e8f0; }
</style>

<div class="page-header">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0;">Cancellation Management</h1>
        <p style="color: #64748b; margin-top: 4px; font-size: 0.875rem;">View and manage all cancelled orders</p>
    </div>
</div>

<div class="card">
    @if($cancelledOrders->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Cancelled On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cancelledOrders as $order)
                <tr>
                    <td><strong style="color: #2563eb;">#{{ $order->id }}</strong></td>
                    <td>
                        <div style="font-weight: 600;">{{ $order->user->name ?? 'N/A' }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $order->user->mobile ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $order->restaurant->restaurant_name ?? 'N/A' }}</div>
                    </td>
                    <td style="font-weight: 600;">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                    <td>
                        <span class="badge badge-cancelled">Cancelled</span>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $order->updated_at->format('M d, Y') }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $order->updated_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <form action="{{ route('support.cancellations.delete', $order->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this cancelled order?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn" style="background: white; border: 1px solid #fee2e2; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: #ef4444; font-weight: 500; font-size: 13px; transition: 0.2s;" onmouseover="this.style.background='#fee2e2'" onmouseout="this.style.background='white'"><i class="fa-regular fa-trash-can"></i> Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-wrapper">
            {{ $cancelledOrders->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="empty-state">
            <i class="fa-regular fa-circle-xmark"></i>
            <h3 style="margin-bottom: 8px; font-size: 1.1rem; color: #1e293b;">No Cancelled Orders</h3>
            <p style="font-size: 0.9rem;">There are currently no cancelled orders in the system.</p>
        </div>
    @endif
</div>
@endsection
