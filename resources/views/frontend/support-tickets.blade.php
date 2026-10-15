@extends('layouts.customer')

@section('title', 'Support Tickets - QuickDines')

@section('content')
<div class="content-header">
    <div class="header-left">
        <h2 style="font-size: 24px; font-weight: 800; color: #1e293b; margin: 0 0 5px 0;">Support Tickets</h2>
        <p>Manage your issues and requests</p>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success" style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
        {{ session('success') }}
    </div>
@endif

<div class="content-card">
    <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
        <table class="table" style="width: 100%; text-align: left; border-collapse: collapse; min-width: 600px;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0;">
                    <th style="padding: 15px; color: #64748b; font-weight: 600; font-size: 14px;">Ticket ID</th>
                    <th style="padding: 15px; color: #64748b; font-weight: 600; font-size: 14px;">Category</th>
                    <th style="padding: 15px; color: #64748b; font-weight: 600; font-size: 14px;">Issue</th>
                    <th style="padding: 15px; color: #64748b; font-weight: 600; font-size: 14px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 15px; font-weight: 600;">{{ $ticket->ticket_number ?? 'TKT-'.$ticket->id }}</td>
                    <td style="padding: 15px;">
                        <span style="background: #e0e7ff; color: #4338ca; padding: 4px 10px; border-radius: 12px; font-size: 12px; font-weight: 600;">{{ $ticket->category ?? 'General' }}</span>
                    </td>
                    <td style="padding: 15px; max-width: 250px; white-space: pre-wrap; word-wrap: break-word;">
                        {{ $ticket->issue }}
                    </td>
                    <td style="padding: 15px; color: #64748b; font-size: 13px;">
                        {{ $ticket->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 40px; text-align: center; color: #94a3b8;">
                        <i class="fa-solid fa-ticket fa-3x" style="opacity: 0.2; margin-bottom: 15px; display: block;"></i>
                        No support tickets raised yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Create Ticket Form Section -->
<div class="content-card" style="margin-top: 30px;">
    <h3 style="margin-top: 0; margin-bottom: 20px; font-weight: 700; color: #1e293b;">Raise a Ticket</h3>
    <form action="{{ route('customer.support.ticket.store') }}" method="POST">
        @csrf
        <div class="form-group" style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Category</label>
            <select name="category" id="ticketCategory" class="form-control" required style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: inherit;">
                <option value="">Select Category</option>
                <option value="Order Issue">Order Issue</option>
                <option value="Payment">Payment</option>
                <option value="Refund">Refund</option>
                <option value="Delivery">Delivery</option>
                <option value="Account">Account</option>
                <option value="Other">Other</option>
            </select>
        </div>
        
        <div class="form-group" id="orderSelectionGroup" style="margin-bottom: 15px; display: none;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Select Order (For Refund)</label>
            <select name="order_id" id="orderSelect" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; font-family: inherit;">
                <option value="">Select an order</option>
                @foreach($recentOrders as $order)
                    <option value="{{ $order->id }}">Order #{{ $order->id }} - {{ $order->restaurant->restaurant_name ?? 'Restaurant' }} (₹{{ number_format($order->total_amount, 2) }})</option>
                @endforeach
            </select>
        </div>

        <input type="hidden" name="priority" value="medium">
        
        <div class="form-group" style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #1e293b;">Describe the Issue</label>
            <textarea name="issue" class="form-control" rows="5" required placeholder="Please provide details about your issue..." style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; resize: vertical; font-family: inherit;"></textarea>
        </div>
        <button type="submit" class="btn-primary" style="width: 100%; padding: 14px; font-weight: 600; border-radius: 8px; background: #2563eb; color: white; border: none; cursor: pointer;">Submit Ticket</button>
    </form>
</div>

<script>
    document.getElementById('ticketCategory').addEventListener('change', function() {
        var orderGroup = document.getElementById('orderSelectionGroup');
        var orderSelect = document.getElementById('orderSelect');
        if (this.value === 'Refund') {
            orderGroup.style.display = 'block';
            orderSelect.setAttribute('required', 'required');
        } else {
            orderGroup.style.display = 'none';
            orderSelect.removeAttribute('required');
        }
    });
</script>
@endsection
