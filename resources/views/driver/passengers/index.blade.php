@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card">
    <div class="card-header-title">
        <h3>Passenger Orders</h3>
    </div>
    
    @if(isset($orders) && count($orders) > 0)
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 12px;">Order ID</th>
                    <th style="padding: 12px;">Restaurant</th>
                    <th style="padding: 12px;">Seat Number</th>
                    <th style="padding: 12px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-weight: 600;">#ORD{{ $order->id }}</td>
                    <td style="padding: 12px; font-weight: 600; color: var(--primary);">{{ $order->restaurant->restaurant_name ?? 'N/A' }}</td>
                    <td style="padding: 12px; font-weight: 700;">{{ $order->seat_number ?? 'N/A' }}</td>
                    <td style="padding: 12px;">
                        @if($order->order_status == 'delivered')
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #dcfce7; color: #16a34a;">Delivered</span>
                        @elseif($order->order_status == 'cancelled')
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #fee2e2; color: #ef4444;">Cancelled</span>
                        @else
                            <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #e0e7ff; color: #4338ca;">{{ ucfirst($order->order_status) }}</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
        <i class="fa-solid fa-users-slash" style="font-size: 48px; margin-bottom: 16px; color: #cbd5e1;"></i>
        <h2>No Passenger Orders Yet</h2>
        <p>There are no food orders assigned to your bus at this time.</p>
    </div>
    @endif
</div>
@endsection
