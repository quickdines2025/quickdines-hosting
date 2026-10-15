@extends('layouts.vendor')

@section('title', 'Support Tickets')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 style="font-size: 24px; font-weight: 800;">Support Tickets <span style="font-size: 13px; font-weight: 500; color: #64748b;">Manage your issues and requests</span></h2>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
    <div class="card-body p-0">
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%;">
            <table style="width: 100%; border-collapse: collapse; min-width: 600px;">
                <thead style="background: #f8fafc;">
                    <tr>
                        <th style="padding: 15px 20px; font-weight: 600; color: #475569; font-size: 13px; border-bottom: 2px solid #e2e8f0;">Ticket ID</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: #475569; font-size: 13px; border-bottom: 2px solid #e2e8f0;">Category</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: #475569; font-size: 13px; border-bottom: 2px solid #e2e8f0;">Issue</th>
                        <th style="padding: 15px 20px; font-weight: 600; color: #475569; font-size: 13px; border-bottom: 2px solid #e2e8f0;">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets as $ticket)
                    <tr>
                        <td style="padding: 15px 20px; font-weight: 600;">{{ $ticket->ticket_number ?? 'TKT-'.$ticket->id }}</td>
                        <td style="padding: 15px 20px;">
                            <span class="badge" style="background: #e0e7ff; color: #4338ca;">{{ $ticket->category ?? 'General' }}</span>
                        </td>
                        <td style="padding: 15px 20px;">
                            {{ $ticket->issue }}
                        </td>
                        <td style="padding: 15px 20px; font-size: 13px; color: #64748b;">
                            {{ $ticket->created_at->format('M d, Y h:i A') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="fa-solid fa-ticket fa-2x mb-3" style="opacity: 0.2"></i>
                            <p>No support tickets raised yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Ticket Form Section -->
<div class="card shadow-sm border-0 mt-5" style="border-radius: 12px; overflow: hidden; background: #fff; border: 1px solid #e2e8f0;">
    <div style="border-bottom: 1px solid #e2e8f0; background: #f8fafc; padding: 16px 20px;">
        <h5 style="font-weight: 700; margin: 0; font-size: 16px; color: #1e293b;">Raise a Ticket</h5>
    </div>
    <form action="{{ route('vendor.tickets.store') }}" method="POST">
        @csrf
        <div style="padding: 20px;">
            <div style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 8px; color: #475569;">Category</label>
                <select name="category" style="width: 100%; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; background: #fff;" required>
                    <option value="">Select Category</option>
                    <option value="Order Issue">Order Issue</option>
                    <option value="Payment">Payment</option>
                    <option value="Menu Update">Menu Update</option>
                    <option value="Account">Account</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div style="margin-bottom: 16px;">
                <label style="font-weight: 600; font-size: 13px; display: block; margin-bottom: 8px; color: #475569;">Describe the Issue</label>
                <textarea name="issue" rows="5" style="width: 100%; padding: 12px; border: 1px solid #cbd5e1; border-radius: 8px; outline: none; resize: vertical; font-family: inherit;" required placeholder="Please provide details about your issue..."></textarea>
            </div>
        </div>
        <div style="border-top: 1px solid #e2e8f0; padding: 16px 20px; text-align: right; background: #f8fafc;">
            <button type="submit" class="btn" style="background: #0f172a; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer;">Submit Ticket</button>
        </div>
    </form>
</div>
@endsection
