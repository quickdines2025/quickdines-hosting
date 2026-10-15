@extends('layouts.support')

@section('title', 'Customer Tickets')

@section('content')
<div class="card">
    <h3 style="margin-bottom: 20px; color: #0f172a;">Manage Support Tickets</h3>
    
    @if($tickets->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Ticket ID</th>
                <th>Customer</th>
                <th>Issue</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tickets as $ticket)
            <tr>
                <td style="font-weight: 700; color: #3b82f6;">#{{ $ticket->id }}</td>
                <td>
                    <div style="font-weight: 600;">{{ $ticket->user->name ?? 'Unknown' }}</div>
                    <div style="font-size: 12px; color: #64748b;">{{ $ticket->user->mobile ?? '' }}</div>
                </td>
                <td style="max-width: 300px;">
                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $ticket->issue }}">
                        {{ $ticket->issue }}
                    </div>
                </td>
                <td>
                    @if($ticket->status == 'open')
                        <span class="badge badge-pending">Open</span>
                    @elseif($ticket->status == 'resolved')
                        <span class="badge badge-active">Resolved</span>
                    @else
                        <span style="background: #e2e8f0; color: #475569; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 600;">Closed</span>
                    @endif
                </td>
                <td>
                    <form action="{{ route('support.tickets.status', $ticket->id) }}" method="POST" style="display: flex; gap: 8px;">
                        @csrf
                        <select name="status" style="padding: 6px; border: 1px solid #cbd5e1; border-radius: 4px;" onchange="this.form.submit()">
                            <option value="open" {{ $ticket->status == 'open' ? 'selected' : '' }}>Open</option>
                            <option value="resolved" {{ $ticket->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px 0; color: #64748b;">
        <i class="fa-solid fa-ticket" style="font-size: 48px; margin-bottom: 16px; opacity: 0.5;"></i>
        <p>No support tickets found.</p>
    </div>
    @endif
</div>
@endsection
