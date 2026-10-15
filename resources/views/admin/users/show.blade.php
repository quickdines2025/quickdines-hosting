@extends('layouts.admin')

@section('title', 'Customer Profile')

@section('content')
<style>
    .page-header {
        margin-bottom: 25px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .page-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 5px 0;
    }
    .breadcrumb {
        font-size: 0.85rem;
        color: #64748b;
        margin: 0;
    }
    .breadcrumb a {
        color: #64748b;
        text-decoration: none;
    }
    .breadcrumb a:hover {
        color: #f26a21;
    }

    .profile-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        margin-bottom: 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .profile-info {
        display: flex;
        align-items: center;
        gap: 20px;
    }
    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #94a3b8;
    }
    .profile-details h2 {
        margin: 0 0 5px 0;
        font-size: 1.5rem;
        color: #1e293b;
    }
    .profile-details p {
        margin: 0;
        color: #64748b;
        font-size: 0.9rem;
    }
    
    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .status-active { background: #dcfce7; color: #16a34a; }
    .status-blocked { background: #fef2f2; color: #ef4444; }

    .btn-action {
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 600;
        cursor: pointer;
        border: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }
    .btn-block {
        background: #ef4444;
        color: white;
    }
    .btn-block:hover { background: #dc2626; }
    
    .btn-unblock {
        background: #22c55e;
        color: white;
    }
    .btn-unblock:hover { background: #16a34a; }

    .section-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 15px;
    }

    /* Table Styles */
    .table-container {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        overflow: hidden;
    }
    .table-custom {
        width: 100%;
        border-collapse: collapse;
    }
    .table-custom th {
        background: #f8fafc;
        padding: 15px;
        text-align: left;
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        border-bottom: 1px solid var(--border-color);
    }
    .table-custom td {
        padding: 15px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        font-size: 0.9rem;
        color: #334155;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }
    .cell-title {
        font-weight: 600;
        color: #1e293b;
    }
    .cell-subtitle {
        font-size: 0.8rem;
        color: #64748b;
        margin-top: 3px;
    }
    .status-order-pending { background: #fffbeb; color: #d97706; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 600;}
    .status-order-preparing { background: #eff6ff; color: #2563eb; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 600;}
    .status-order-completed { background: #f0fdf4; color: #16a34a; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 600;}
    .status-order-cancelled { background: #fef2f2; color: #ef4444; padding: 4px 10px; border-radius: 15px; font-size: 0.75rem; font-weight: 600;}
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Customer Profile</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a> &gt; 
            <a href="{{ route('admin.users.index') }}">Customers</a> &gt; 
            Profile
        </div>
    </div>
</div>

<div class="profile-card">
    <div class="profile-info">
        <div class="profile-avatar">
            <i class="fa-solid fa-user"></i>
        </div>
        <div class="profile-details">
            <h2>
                {{ $user->name }} 
                @if($user->status == 'active')
                    <span class="status-badge status-active" style="margin-left: 10px; vertical-align: middle;">Active</span>
                @else
                    <span class="status-badge status-blocked" style="margin-left: 10px; vertical-align: middle;">Blocked</span>
                @endif
            </h2>
            <p><i class="fa-solid fa-phone" style="width: 15px;"></i> {{ $user->mobile ?? 'N/A' }}</p>
            <p><i class="fa-solid fa-envelope" style="width: 15px;"></i> {{ $user->email ?? 'N/A' }}</p>
            <p style="margin-top: 5px;"><i class="fa-solid fa-calendar" style="width: 15px;"></i> Joined: {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Unknown' }}</p>
        </div>
    </div>
    
    <div class="profile-actions">
        @if($user->status == 'active')
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="blocked">
                <button type="submit" class="btn-action btn-block" onclick="return confirm('Are you sure you want to block this customer?');">
                    <i class="fa-solid fa-ban"></i> Block Customer
                </button>
            </form>
        @else
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="active">
                <button type="submit" class="btn-action btn-unblock">
                    <i class="fa-solid fa-check"></i> Unblock Customer
                </button>
            </form>
        @endif
    </div>
</div>

<h3 class="section-title">Order History ({{ $user->orders->count() }})</h3>
<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Restaurant</th>
                <th>Bus Details</th>
                <th>Amount</th>
                <th>Status</th>
                <th>Date & Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse($user->orders as $order)
            <tr>
                <td style="font-weight: 600;">#ORD{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="cell-title">{{ optional($order->restaurant)->restaurant_name ?? 'N/A' }}</div>
                    <div class="cell-subtitle">{{ optional($order->restaurant)->cuisines ?? 'N/A' }}</div>
                </td>
                <td>
                    @if($order->bus)
                        <div class="cell-title">{{ $order->bus->bus_number }}</div>
                        <div class="cell-subtitle">
                            @if($order->bus->busRoute)
                                {{ $order->bus->busRoute->source }} &rarr; {{ $order->bus->busRoute->destination }}
                            @else
                                {{ $order->bus->route_from ?? 'Unknown' }} &rarr; {{ $order->bus->route_to ?? 'Unknown' }}
                            @endif
                        </div>
                    @else
                        <span style="color:#94a3b8; font-size:0.85rem;">N/A</span>
                    @endif
                </td>
                <td style="font-weight: 600;">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                <td>
                    @if(in_array($order->order_status, ['placed', 'pending']))
                        <span class="status-order-pending">{{ ucfirst($order->order_status) }}</span>
                    @elseif($order->order_status == 'preparing')
                        <span class="status-order-preparing">Preparing</span>
                    @elseif(in_array($order->order_status, ['ready', 'out_for_delivery', 'delivered', 'completed']))
                        <span class="status-order-completed">{{ ucfirst($order->order_status) }}</span>
                    @else
                        <span class="status-order-cancelled">{{ ucfirst($order->order_status) }}</span>
                    @endif
                </td>
                <td>
                    <div class="cell-title">{{ $order->created_at ? $order->created_at->format('M d, Y') : 'Unknown' }}</div>
                    <div class="cell-subtitle">{{ $order->created_at ? $order->created_at->format('h:i A') : '' }}</div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fa-solid fa-receipt" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                    <p>This customer hasn't placed any orders yet.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
