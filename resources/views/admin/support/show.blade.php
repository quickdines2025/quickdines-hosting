@extends(Auth::user()->role === 'support' ? 'layouts.support' : 'layouts.admin')

@php
    $rPrefix = Auth::user()->role === 'support' ? 'support.tickets' : 'admin.support.tickets';
@endphp

@section('content')
<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 24px;">
    <div>
        <h1 class="page-title" style="font-size:1.5rem; font-weight:700; color:#1e293b; margin:0;">Ticket #{{ $ticket->ticket_number ?? 'TKT'.$ticket->id }}</h1>
        <div class="breadcrumb" style="font-size:0.875rem; color:#64748b; margin-top:4px;">
            <a href="{{ route($rPrefix . '.index') }}" style="color:#64748b; text-decoration:none;">Support Tickets</a> <i class="fa-solid fa-chevron-right" style="font-size:0.7rem; margin:0 8px;"></i> Ticket Details
        </div>
    </div>
    <a href="{{ route($rPrefix . '.index') }}" class="btn-outline" style="background:white; border:1px solid #e2e8f0; padding:8px 16px; border-radius:8px; color:#475569; text-decoration:none;"><i class="fa-solid fa-arrow-left"></i> Back to Tickets</a>
</div>

<div style="background:#fff; border-radius:12px; border:1px solid #f1f5f9; padding:24px; box-shadow:0 1px 3px rgba(0,0,0,0.05);">
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 32px;">
        
        <div>
            <h3 style="font-size:1.1rem; color:#1e293b; margin-top:0;">Issue Details</h3>
            <div style="background:#f8fafc; padding:16px; border-radius:8px; border:1px solid #e2e8f0; margin-bottom:24px;">
                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                    <strong style="color:#1e293b; font-size:0.875rem;">{{ $ticket->customer ? $ticket->customer->name : 'Customer' }}</strong>
                    <span style="font-size:0.75rem; color:#64748b;">{{ $ticket->created_at->format('M d, Y h:i A') }}</span>
                </div>
                <p style="margin:0; font-size:0.9rem; color:#334155; line-height:1.6;">{{ $ticket->issue }}</p>
            </div>

            <div class="card">
            <h3 style="font-size: 1rem; margin-bottom: 20px;">Ticket Actions</h3>
            <form action="{{ route($rPrefix . '.status', $ticket->id) }}" method="POST" style="background:#f8fafc; padding:20px; border-radius:8px; border:1px solid #e2e8f0;">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display:block; margin-bottom:8px; font-weight:600; font-size:0.875rem; color:#334155;">Ticket Status</label>
                    <select name="status" style="width:100%; padding:10px 12px; border:1px solid #cbd5e1; border-radius:6px; outline:none; background:white; font-size:0.875rem; color:#1e293b;">
                        <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                        <option value="in progress" {{ $ticket->status == 'in progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                    </select>
                </div>
                <button type="submit" style="background:#10b981; color:white; border:none; padding:10px 24px; border-radius:6px; font-weight:600; cursor:pointer; font-size:0.875rem;">Update Status</button>
            </form>
        </div>
        
        <div style="border-left:1px solid #e2e8f0; padding-left:24px;">
            <h3 style="font-size:1.1rem; color:#1e293b; margin-top:0;">Ticket Information</h3>
            
            <div style="margin-bottom:16px;">
                <span style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600; display:block; margin-bottom:4px;">Customer</span>
                <div style="font-weight:600; color:#1e293b;">{{ $ticket->customer ? $ticket->customer->name : 'Guest' }}</div>
                <div style="font-size:0.875rem; color:#64748b;">{{ $ticket->customer ? $ticket->customer->email : 'N/A' }}</div>
                @if($ticket->customer && $ticket->customer->mobile)
                    <div style="font-size:0.875rem; color:#64748b; margin-top:2px;">{{ $ticket->customer->mobile }}</div>
                @endif
            </div>
            
            <div style="margin-bottom:16px;">
                <span style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600; display:block; margin-bottom:4px;">Category</span>
                <div style="font-weight:500; color:#1e293b;">{{ $ticket->category ?? 'General' }}</div>
            </div>
            
            <div style="margin-bottom:16px;">
                <span style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600; display:block; margin-bottom:4px;">Priority</span>
                <div style="font-weight:500; color:#1e293b;">{{ ucfirst($ticket->priority) }}</div>
            </div>
            
            <div style="margin-bottom:16px;">
                <span style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600; display:block; margin-bottom:4px;">Status</span>
                <div style="font-weight:500; color:#1e293b;">{{ ucfirst($ticket->status) }}</div>
            </div>
            
            <div style="margin-bottom:16px;">
                <span style="font-size:0.75rem; color:#64748b; text-transform:uppercase; font-weight:600; display:block; margin-bottom:4px;">Created On</span>
                <div style="font-weight:500; color:#1e293b;">{{ $ticket->created_at->format('M d, Y h:i A') }}</div>
            </div>
        </div>
        
    </div>
</div>
@endsection
