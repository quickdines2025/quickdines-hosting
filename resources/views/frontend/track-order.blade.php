@extends('layouts.customer')

@section('title', 'Track My Order - QuickDines')

@section('styles')
<style>
    .col-header { padding: 0 5px; margin-bottom: 20px; }
    .col-header h2 { font-size: 24px; font-weight: 800; color: #000; margin-bottom: 5px; }
    .col-header p { font-size: 14px; color: var(--text-muted); }

    .track-wrapper { max-width: 600px; display: flex; flex-direction: column; gap: 20px; }

    /* Timeline Box */
    .timeline-box {
        background: white;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        margin-bottom: 24px;
        border: 1px solid #f1f5f9;
    }
    .tb-title { font-size: 16px; font-weight: 700; color: #000; margin-bottom: 24px; }
    
    .timeline {
        position: relative;
        padding-left: 30px;
    }
    .timeline::before {
        content: '';
        position: absolute;
        top: 0; left: 11px;
        bottom: 20px;
        width: 2px;
        background: #e2e8f0;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 30px;
    }
    .timeline-item:last-child { margin-bottom: 0; }
    
    .t-icon {
        position: absolute;
        left: -30px;
        width: 24px; height: 24px;
        border-radius: 50%;
        background: white;
        border: 2px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
    }
    .timeline-item.completed .t-icon {
        background: #10b981;
        border-color: #10b981;
        color: white;
    }
    .timeline-item.active .t-icon {
        border-color: #10b981;
    }
    .timeline-item.active .t-icon::after {
        content: '';
        width: 10px; height: 10px;
        background: #10b981;
        border-radius: 50%;
    }
    
    .t-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }
    .t-text h4 { font-size: 14px; font-weight: 700; color: #000; margin-bottom: 4px; }
    .t-text p { font-size: 12px; color: #64748b; line-height: 1.4; max-width: 80%; }
    .t-time { font-size: 12px; color: #475569; font-weight: 600; }
    
    .timeline-item.pending .t-text h4 { color: #94a3b8; }

    .support-card { background: #fff; border-radius: 12px; padding: 20px; border: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
    .sc-left { display: flex; gap: 15px; align-items: center; }
    .sc-left i { font-size: 24px; color: #475569; }
    .sc-text h4 { font-size: 15px; font-weight: 700; color: #000; }
    .sc-text p { font-size: 12px; color: var(--text-muted); }
    .btn-support { background: var(--primary); color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; }

    .empty-state { text-align: center; padding: 60px 20px; border: 1px dashed #e2e8f0; border-radius: 12px; }
    .empty-state i { font-size: 40px; color: #cbd5e1; margin-bottom: 15px; }
    .empty-state p { font-size: 15px; color: var(--text-muted); }
    .view-all-btn { display: inline-block; padding: 12px 25px; background: var(--primary); border-radius: 8px; font-size: 14px; font-weight: 600; color: white; text-decoration: none; margin-top: 20px; }
</style>
@endsection

@section('content')
<div class="col-header">
    <h2>Track My Order</h2>
    <p>Real-time status of your current order</p>
</div>

<div class="track-wrapper">
    @php
        $activeOrders = \App\Models\Order::with(['restaurant', 'bus', 'items'])
            ->where('user_id', auth()->id())
            ->whereIn('order_status', ['pending', 'preparing', 'packing', 'ready', 'on_the_way', 'bus_arrived'])
            ->orderBy('created_at', 'desc')
            ->get();
    @endphp

    @if($activeOrders->count() > 0)
        @foreach($activeOrders as $activeOrder)
        @php
            $status = strtolower($activeOrder->order_status ?? 'pending');
            $currentIndex = 0;
            if (in_array($status, ['preparing', 'packing', 'ready', 'on_the_way'])) {
                $currentIndex = 1;
            } elseif ($status == 'bus_arrived') {
                $currentIndex = 2;
            } elseif (in_array($status, ['completed', 'delivered'])) {
                $currentIndex = 3;
            }
        @endphp
        
        <div class="timeline-box">
            <div class="tb-title" style="display:flex; justify-content:space-between; align-items:center;">
                <span>Order Status</span>
                <div style="display:flex; gap:12px; align-items:center;">
                    @if($status == 'pending')
                    <form action="{{ route('customer.order.cancel', $activeOrder->id) }}" method="POST" style="margin:0;" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <button type="submit" style="background:#ef4444; color:white; border:none; padding:4px 12px; border-radius:6px; font-size:12px; font-weight:600; cursor:pointer;">Cancel Order</button>
                    </form>
                    @endif
                    <span style="color:var(--primary); font-size:12px;">#ORD-{{ $activeOrder->id }}</span>
                </div>
            </div>
            <div class="timeline">
                <!-- Order Confirmed -->
                <div class="timeline-item {{ $currentIndex >= 0 ? ($currentIndex == 0 ? 'active' : 'completed') : 'pending' }}">
                    <div class="t-icon">@if($currentIndex > 0)<i class="fa-solid fa-check" style="font-size: 10px;"></i>@endif</div>
                    <div class="t-content">
                        <div class="t-text">
                            <h4>Order Confirmed</h4>
                            <p>We've received your order</p>
                        </div>
                        @if($currentIndex >= 0)<div class="t-time" {{ $currentIndex == 0 ? 'style=color:#10b981;' : '' }}>{{ $activeOrder->created_at->format('h:i A') }}</div>@endif
                    </div>
                </div>
                
                <!-- Preparing Food -->
                <div class="timeline-item {{ $currentIndex >= 1 ? ($currentIndex == 1 ? 'active' : 'completed') : 'pending' }}">
                    <div class="t-icon">@if($currentIndex > 1)<i class="fa-solid fa-check" style="font-size: 10px;"></i>@endif</div>
                    <div class="t-content">
                        <div class="t-text">
                            <h4>Preparing Food</h4>
                            <p>Restaurant is preparing your order</p>
                        </div>
                        @if($currentIndex == 1)<div class="t-time" style="color:#10b981;">{{ $activeOrder->updated_at->format('h:i A') }}</div>@endif
                    </div>
                </div>
                
                <!-- Bus Arrive to Restaurant -->
                <div class="timeline-item {{ $currentIndex >= 2 ? ($currentIndex == 2 ? 'active' : 'completed') : 'pending' }}">
                    <div class="t-icon">@if($currentIndex > 2)<i class="fa-solid fa-check" style="font-size: 10px;"></i>@endif</div>
                    <div class="t-content">
                        <div class="t-text">
                            <h4>Bus Arrive to Restaurant</h4>
                            <p>Your bus has arrived at the restaurant</p>
                            
                            @php
                                $bus = $activeOrder->bus;
                                $restaurant = $activeOrder->restaurant;
                                $eta = null;
                                if ($bus && $bus->busRoute && $bus->journey_start_time) {
                                    $stop = $bus->busRoute->stops->where('stop_name', $restaurant->city)->first() 
                                         ?? $bus->busRoute->stops->where('stop_name', $restaurant->restaurant_name)->first();
                                    if ($stop) {
                                        $eta = $bus->calculateEtaForStop($stop);
                                    }
                                }
                            @endphp
                            
                            @if($currentIndex < 2 && $eta)
                                <div style="margin-top: 8px; padding: 8px 12px; background: #eff6ff; border-radius: 8px; border: 1px solid #bfdbfe;">
                                    <div style="font-size: 11px; font-weight: 700; color: #1e3a8a; margin-bottom: 2px;">LIVE BUS TRACKING</div>
                                    <div style="font-size: 13px; font-weight: 600; color: #1d4ed8;">Estimated Arrival: {{ $eta->format('h:i A') }}</div>
                                    @if($bus->journey_delay_minutes > 0)
                                        <div style="font-size: 11px; color: #ef4444; margin-top: 2px;"><i class="fas fa-exclamation-circle"></i> Delayed by {{ $bus->journey_delay_minutes }} mins (Included in ETA)</div>
                                    @elseif($bus->journey_delay_minutes < 0)
                                        <div style="font-size: 11px; color: #10b981; margin-top: 2px;"><i class="fas fa-clock"></i> Early by {{ abs($bus->journey_delay_minutes) }} mins (Included in ETA)</div>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @if($currentIndex == 2)<div class="t-time" style="color:#10b981;">{{ $activeOrder->updated_at->format('h:i A') }}</div>@endif
                    </div>
                </div>
                
                <!-- Food Delivered -->
                <div class="timeline-item {{ $currentIndex >= 3 ? 'completed' : 'pending' }}">
                    <div class="t-icon">@if($currentIndex >= 3)<i class="fa-solid fa-check" style="font-size: 10px;"></i>@endif</div>
                    <div class="t-content">
                        <div class="t-text">
                            <h4>Food Delivered</h4>
                            <p>Enjoy your meal!</p>
                        </div>
                        @if($currentIndex >= 3)<div class="t-time">{{ $activeOrder->updated_at->format('h:i A') }}</div>@endif
                    </div>
                </div>
            </div>
        </div>
            
            <div class="support-card">
                <div class="sc-left">
                    <i class="fa-solid fa-headset"></i>
                    <div class="sc-text">
                        <h4>Need Help?</h4>
                        <p>Our support team is here to help you.</p>
                    </div>
                </div>
                <a href="{{ route('support') }}" class="btn-support">Contact Support</a>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fa-solid fa-route"></i>
            <p style="margin-top:15px; font-weight:700; color:#000; font-size: 18px;">No active orders</p>
            <p style="margin-top:8px;">You don't have any orders currently in progress.</p>
            <a href="{{ route('home') }}" class="view-all-btn">Browse Menu</a>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    // The auto-refresh and sound is now handled entirely by the global script in layouts/customer.blade.php
</script>
@endpush
