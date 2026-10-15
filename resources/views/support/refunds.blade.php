@extends('layouts.support')

@section('title', 'Refund Management')

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
    .badge-pending { background: #fef3c7; color: #d97706; }
    .badge-success { background: #dcfce3; color: #16a34a; }
    .badge-danger { background: #fee2e2; color: #ef4444; }
    
    .empty-state { padding: 60px 20px; text-align: center; color: #64748b; }
    .empty-state i { font-size: 48px; margin-bottom: 16px; opacity: 0.5; }
    .pagination-wrapper { padding: 20px; border-top: 1px solid #e2e8f0; }

    .stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-bottom: 24px; }
    .stat-card { background: white; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px; }
    .stat-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .stat-icon.primary { background: #eff6ff; color: #2563eb; }
    .stat-icon.success { background: #f0fdf4; color: #16a34a; }
    .stat-icon.warning { background: #fffbeb; color: #d97706; }
    .stat-label { font-size: 13px; color: #64748b; font-weight: 600; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px; }
    .stat-value { font-size: 24px; font-weight: 700; color: #0f172a; }
    
    .section-title { font-size: 1.1rem; font-weight: 600; color: #1e293b; margin-bottom: 16px; margin-top: 32px; }
    
    .action-buttons { display: flex; gap: 8px; }
    .btn-approve { background: #10b981; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: white; font-weight: 500; font-size: 13px; transition: 0.2s; }
    .btn-approve:hover { background: #059669; }
    .btn-reject { background: white; border: 1px solid #fee2e2; padding: 6px 12px; border-radius: 6px; cursor: pointer; color: #ef4444; font-weight: 500; font-size: 13px; transition: 0.2s; }
    .btn-reject:hover { background: #fee2e2; }
</style>

<div class="page-header">
    <div>
        <h1 style="font-size: 1.5rem; font-weight: 700; color: #0f172a; margin: 0;">Refund Management</h1>
        <p style="color: #64748b; margin-top: 4px; font-size: 0.875rem;">View and process pending refunds for customer support tickets</p>
    </div>
</div>

@if(session('success'))
    <div style="background: #dcfce3; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0;">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
        {{ session('error') }}
    </div>
@endif

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon warning"><i class="fa-solid fa-clock-rotate-left"></i></div>
        <div>
            <div class="stat-label">Pending Requests</div>
            <div class="stat-value">{{ $pendingRefunds->total() }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon success"><i class="fa-solid fa-check-double"></i></div>
        <div>
            <div class="stat-label">Total Processed</div>
            <div class="stat-value">{{ $totalRefundsGiven }}</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon primary"><i class="fa-solid fa-indian-rupee-sign"></i></div>
        <div>
            <div class="stat-label">Total Amount Refunded</div>
            <div class="stat-value">₹{{ number_format($totalRefundAmount, 2) }}</div>
        </div>
    </div>
</div>

<h2 class="section-title">Pending Customer Refund Requests</h2>

<div class="card">
    @if($pendingRefunds->count() > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Ticket / Order ID</th>
                    <th>Customer</th>
                    <th>Restaurant</th>
                    <th>Order Total</th>
                    <th>Customer Issue</th>
                    <th>Requested On</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pendingRefunds as $ticket)
                <tr>
                    <td>
                        <strong style="color: #2563eb;">{{ $ticket->ticket_number }}</strong><br>
                        @if($ticket->order)
                            <span style="font-size: 12px; color: #64748b;">Order #{{ $ticket->order->id }}</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight: 600;">{{ $ticket->customer->name ?? 'N/A' }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $ticket->customer->mobile ?? 'N/A' }}</div>
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $ticket->order->restaurant->restaurant_name ?? 'N/A' }}</div>
                    </td>
                    <td style="font-weight: 600;">₹{{ number_format($ticket->order->total_amount ?? 0, 2) }}</td>
                    <td style="max-width: 250px; font-size: 13px; color: #475569;">
                        {{ \Illuminate\Support\Str::limit($ticket->issue, 50) }}
                    </td>
                    <td>
                        <div style="font-weight: 500;">{{ $ticket->created_at->format('M d, Y') }}</div>
                        <div style="font-size: 12px; color: #64748b;">{{ $ticket->created_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <form action="{{ route('support.refunds.process', $ticket->id) }}" method="POST" onsubmit="return confirmApprove(this, {{ $ticket->order->total_amount ?? 0 }});" style="margin:0;">
                                @csrf
                                <input type="hidden" name="refund_amount" class="refund-amount-input">
                                <button type="submit" class="btn-approve" title="Approve & Pay"><i class="fa-solid fa-check"></i> Approve</button>
                            </form>
                            
                            <form action="{{ route('support.refunds.reject', $ticket->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this refund request?');" style="margin:0;">
                                @csrf
                                <button type="submit" class="btn-reject" title="Reject"><i class="fa-solid fa-xmark"></i> Reject</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="pagination-wrapper">
            {{ $pendingRefunds->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="empty-state">
            <i class="fa-solid fa-check-double" style="color: #10b981;"></i>
            <h3 style="margin-bottom: 8px; font-size: 1.1rem; color: #1e293b;">All Caught Up!</h3>
            <p style="font-size: 0.9rem;">There are currently no pending refund requests from customers.</p>
        </div>
    @endif
</div>

@if($processedRefunds->count() > 0)
<h2 class="section-title">Processed Refunds History</h2>
<div class="card">
    <table class="table">
        <thead>
            <tr>
                <th>Ticket / Order ID</th>
                <th>Customer</th>
                <th>Restaurant</th>
                <th>Paid Amount</th>
                <th>Status</th>
                <th>Processed On</th>
            </tr>
        </thead>
        <tbody>
            @foreach($processedRefunds as $ticket)
            <tr>
                <td>
                    <strong style="color: #2563eb;">{{ $ticket->ticket_number }}</strong><br>
                    @if($ticket->order)
                        <span style="font-size: 12px; color: #64748b;">Order #{{ $ticket->order->id }}</span>
                    @endif
                </td>
                <td>
                    <div style="font-weight: 600;">{{ $ticket->customer->name ?? 'N/A' }}</div>
                    <div style="font-size: 12px; color: #64748b;">{{ $ticket->customer->mobile ?? 'N/A' }}</div>
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $ticket->order->restaurant->restaurant_name ?? 'N/A' }}</div>
                </td>
                <td style="font-weight: 600; color: #16a34a;">₹{{ number_format($ticket->refund_amount ?? 0, 2) }}</td>
                <td>
                    <span class="badge badge-success">Paid</span>
                </td>
                <td>
                    <div style="font-weight: 500;">{{ $ticket->updated_at->format('M d, Y') }}</div>
                    <div style="font-size: 12px; color: #64748b;">{{ $ticket->updated_at->format('h:i A') }}</div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="pagination-wrapper">
        {{ $processedRefunds->appends(['pending_page' => request('pending_page')])->links('pagination::bootstrap-4') }}
    </div>
</div>
@endif

<script>
    function confirmApprove(form, maxAmount) {
        let amount = prompt("How much are you refunding? (Max: ₹" + maxAmount + ")", maxAmount);
        
        if (amount === null) {
            return false; // Cancelled
        }
        
        amount = parseFloat(amount);
        if (isNaN(amount) || amount <= 0) {
            alert("Please enter a valid amount.");
            return false;
        }
        
        form.querySelector('.refund-amount-input').value = amount;
        return true;
    }
</script>
@endsection
