@extends('layouts.vendor')

@section('title', 'Order Management')

@push('styles')
<style>
    .page-title {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        margin-bottom: 0.25rem;
    }

    .page-subtitle {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Custom Tabs */
    .custom-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 24px;
        background: #f8fafc;
        padding: 6px;
        border-radius: 12px;
        width: 100%;
        border: 1px solid #e2e8f0;
    }

    @media (min-width: 768px) {
        .custom-tabs {
            width: fit-content;
        }
    }

    .custom-tab {
        padding: 10px 24px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.9rem;
        color: #64748b;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        background: transparent;
        flex: 1 1 auto;
        text-align: center;
    }

    .custom-tab.active {
        background: #2563eb;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .custom-tab:hover:not(.active) {
        background: #e2e8f0;
        color: #1e293b;
    }

    .tab-badge {
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 0.75rem;
        margin-left: 6px;
    }

    .custom-tab:not(.active) .tab-badge {
        background: #cbd5e1;
        color: #475569;
    }

    /* Order Cards */
    .order-card {
        background: white;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .order-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.025);
    }

    .order-header {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px dashed #e2e8f0;
        padding-bottom: 16px;
        margin-bottom: 16px;
    }

    .order-id {
        font-size: 1.125rem;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .order-time {
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .status-preparing {
        background: #e0e7ff;
        color: #4338ca;
        border: 1px solid #c7d2fe;
    }

    .status-ready {
        background: #dcfce7;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .status-completed,
    .status-delivered {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .status-cancelled,
    .status-rejected {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .order-items {
        margin-bottom: 20px;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        font-size: 0.9rem;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
    }

    .item-row:last-child {
        border-bottom: none;
    }

    .item-qty {
        font-weight: 700;
        color: #2563eb;
        background: #fff5f2;
        padding: 2px 8px;
        border-radius: 6px;
        margin-right: 10px;
        font-size: 0.8rem;
    }

    .item-name {
        font-weight: 600;
        color: #1e293b;
    }

    .item-price {
        font-weight: 600;
        color: #475569;
    }

    .item-thumbnail {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        object-fit: cover;
        margin-right: 10px;
        border: 1px solid #e2e8f0;
    }

    .order-footer {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: center;
        border-top: 1px dashed #e2e8f0;
        padding-top: 20px;
        margin-top: auto;
    }

    .total-label {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .order-total {
        font-size: 1.25rem;
        font-weight: 800;
        color: #0f172a;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 576px) {
        .action-buttons {
            width: 100%;
        }

        .btn-action {
            flex: 1 1 100%;
            justify-content: center;
        }
    }

    .btn-action {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-accept {
        background: #2563eb;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2);
    }

    .btn-accept:hover {
        background: #1d4ed8;
        transform: translateY(-1px);
    }

    .btn-ready {
        background: #10b981;
        color: white;
        box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.2);
    }

    .btn-ready:hover {
        background: #059669;
        transform: translateY(-1px);
    }

    .btn-reject {
        background: #f1f5f9;
        color: #475569;
        border: 1px solid #e2e8f0;
    }

    .btn-reject:hover {
        background: #e2e8f0;
        color: #1e293b;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: white;
        border-radius: 16px;
        border: 1px dashed #cbd5e1;
    }

    .empty-state i {
        font-size: 48px;
        color: #cbd5e1;
        margin-bottom: 16px;
    }

    .empty-state h4 {
        color: #1e293b;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-state p {
        color: #64748b;
        font-size: 0.9rem;
    }

    /* Timeline styling */
    .timeline {
        position: relative;
        padding: 20px 10px;
        margin-top: 15px;
        border-top: 1px dashed #cbd5e1;
    }

    .timeline-container {
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: relative;
    }

    .timeline-container::before {
        content: '';
        position: absolute;
        left: 11px;
        top: 10px;
        bottom: 10px;
        width: 2px;
        border-left: 2px dashed #cbd5e1;
        z-index: 1;
    }

    .tl-item {
        display: flex;
        gap: 15px;
        position: relative;
        z-index: 2;
    }

    .tl-icon {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: white;
        border: 2px solid #cbd5e1;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 2;
        font-size: 0.65rem;
        color: transparent;
        flex-shrink: 0;
        margin-top: 2px;
    }

    .tl-item.completed .tl-icon {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .tl-item.active .tl-icon {
        background: #4f46e5;
        border-color: #4f46e5;
        color: white;
    }

    .tl-content {
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .tl-title {
        font-weight: 600;
        font-size: 0.85rem;
        color: #1e293b;
        margin-bottom: 2px;
    }

    .tl-desc {
        font-size: 0.75rem;
        color: #64748b;
        margin: 0;
    }

    @media (min-width: 768px) {
        .timeline-container {
            flex-direction: row;
            justify-content: space-between;
            gap: 10px;
        }

        .timeline-container::before {
            left: 10%;
            right: 10%;
            top: 13px;
            bottom: auto;
            width: auto;
            height: 2px;
            border-left: none;
            border-top: 2px dashed #cbd5e1;
        }

        .tl-item {
            flex-direction: column;
            align-items: center;
            text-align: center;
            width: 100%;
            gap: 10px;
        }

        .tl-icon {
            margin-top: 0;
        }
    }
</style>
@endpush

@section('content')

@if(session('success'))
<div class="alert alert-success border-0 bg-success text-white" style="border-radius: 10px;">
    <i class="fa-solid fa-check-circle me-2"></i> {{ session('success') }}
</div>
@endif

<!-- Bus-wise Order Filter -->
<div style="
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 16px 18px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 15px;
    flex-wrap: wrap;
">

    <div>
        <div style="
            font-size: 0.95rem;
            font-weight: 800;
            color: #1e293b;
            margin-bottom: 3px;
        ">
            <i class="fa-solid fa-bus" style="color:#2563eb;"></i>
            Bus-wise Orders
        </div>

        <div style="
            font-size: 0.78rem;
            color: #64748b;
        ">
            View orders by bus number
        </div>
    </div>

    <form method="GET"
        action="{{ url()->current() }}"
        style="display:flex; align-items:center; gap:10px; margin:0;">

        <select
            name="bus_id"
            onchange="this.form.submit()"
            style="
                min-width:220px;
                padding:10px 14px;
                border:1px solid #cbd5e1;
                border-radius:8px;
                background:#fff;
                color:#1e293b;
                font-size:0.9rem;
                font-weight:600;
                outline:none;
            ">
            <option value="">All Buses</option>

            @foreach($buses as $bus)
            <option
                value="{{ $bus->id }}"
                {{ (string)$selectedBusId === (string)$bus->id ? 'selected' : '' }}>
                {{ $bus->bus_number }}
            </option>
            @endforeach

        </select>

        @if($selectedBusId)
        <a
            href="{{ url()->current() }}"
            style="
                    padding:10px 14px;
                    border-radius:8px;
                    background:#f1f5f9;
                    color:#475569;
                    text-decoration:none;
                    font-size:0.85rem;
                    font-weight:700;
                    white-space:nowrap;
                ">
            Clear
        </a>
        @endif

    </form>

</div>

<!-- Custom Tabs -->
<div class="custom-tabs">
    <button class="custom-tab active" onclick="switchTab('active')">
        Active Orders <span class="tab-badge">{{ count($activeOrders) }}</span>
    </button>
    <button class="custom-tab" onclick="switchTab('past')">
        Completed Orders <span class="tab-badge">{{ count($pastOrders->where('order_status', 'completed')) }}</span>
    </button>
    <button class="custom-tab" onclick="switchTab('cancelled')">
        Cancelled Orders <span class="tab-badge">{{ count($pastOrders->where('order_status', 'cancelled')) }}</span>
    </button>
</div>

<!-- Active Orders Container -->
<div id="activeOrdersContainer" class="row">
    @forelse($activeOrders as $order)
    <div class="col-xl-4 col-md-6 mb-4" id="order-{{ $order->id }}">
        <div class="order-card h-100 d-flex flex-column">
            <div class="order-header">
                <div>
                    <div class="order-id">
                        #ORD-{{ $order->id }}
                    </div>
                    <div class="order-time">
                        <i class="fa-regular fa-clock"></i> {{ $order->created_at->format('M d, h:i A') }}
                        @if($order->bus)
                        &bull; <i class="fa-solid fa-bus"></i> {{ $order->bus->bus_number }}
                        @if($order->bus->route_from && $order->bus->route_to)
                        ({{ $order->bus->route_from }} &rarr; {{ $order->bus->route_to }})
                        @elseif($order->bus->busRoute)
                        ({{ $order->bus->busRoute->source }} &rarr; {{ $order->bus->busRoute->destination }})
                        @endif
                        @endif
                    </div>

                    <div class="order-passenger-info"
                        style="margin-top: 8px; font-size: 0.85rem; color: #475569;">

                        <div>
                            <i class="fa-regular fa-user"></i>
                            <strong>{{ $order->passenger_name }}</strong>
                        </div>

                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-phone" style="font-size: 0.75rem;"></i>
                            {{ $order->user->mobile ?? 'N/A' }}
                        </div>

                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-users"></i>
                            <strong>Passengers:</strong>
                            {{ $order->passenger_count ?? 1 }}
                        </div>
                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-venus-mars"></i>
                            <strong>Gender:</strong>
                            {{ ucfirst($order->gender ?? 'N/A') }}
                        </div>

                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-chair"></i>
                            <strong>Seat Nos:</strong>

                            @if(!empty($order->passenger_seats))
                            {{ collect($order->passenger_seats)->pluck('seat_number')->implode(', ') }}
                            @else
                            {{ $order->seat_number ?? 'N/A' }}
                            @endif
                        </div>
                    </div>


                    @if($order->cooking_instructions)
                    <div style="margin-top: 10px; font-size: 0.85rem; background: #fff7ed; padding: 10px 12px; border-radius: 8px; border-left: 4px solid #1d4ed8; color: #9a3412;">
                        <i class="fa-solid fa-utensils" style="margin-right: 6px;"></i> <strong>Instructions:</strong> {{ $order->cooking_instructions }}
                    </div>
                    @endif
                </div>
                <div class="status-badge status-{{ strtolower($order->order_status) }}">
                    {{ str_replace('_', ' ', $order->order_status) }}
                </div>
            </div>

            <div class="order-items">
                @foreach($order->items as $item)
                <div class="item-row">
                    <div style="display: flex; align-items: center;">
                        <span class="item-qty">{{ $item->quantity }}x</span>
                        @if($item->menuItem && $item->menuItem->image)
                        <img src="{{ \Str::startsWith($item->menuItem->image, 'http') ? $item->menuItem->image : asset('storage/' . $item->menuItem->image) }}" class="item-thumbnail" alt="">
                        @else
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80" class="item-thumbnail" alt="">
                        @endif
                        <span class="item-name">{{ $item->menuItem->item_name ?? 'Unknown Item' }}</span>
                    </div>
                    <div class="item-price">₹{{ $item->price * $item->quantity }}</div>
                </div>
                @endforeach
            </div>

            <div class="timeline">
                @php
                $status = strtolower($order->order_status);
                $timelineSteps = [
                'pending' => 'Order Confirmed',
                'preparing' => 'Preparing Food',
                'bus_arrived' => 'Bus Arrive to Restaurant',
                'completed' => 'Food Delivered'
                ];

                $keys = array_keys($timelineSteps);
                $activeIndex = array_search($status, $keys);
                if ($activeIndex === false && $status != 'cancelled') $activeIndex = 0;
                @endphp

                @if($status == 'cancelled')
                <div class="text-danger fw-bold text-center py-2"><i class="fa-solid fa-xmark"></i> Order Cancelled</div>
                @else
                <div class="timeline-container">
                    @foreach($timelineSteps as $key => $label)
                    @php
                    $currentIndex = $loop->index;
                    $itemClass = '';
                    if ($currentIndex < $activeIndex) $itemClass='completed' ;
                        elseif ($currentIndex==$activeIndex) $itemClass='active' ;
                        @endphp
                        <div class="tl-item {{ $itemClass }}">
                        <div class="tl-icon">
                            @if($currentIndex < $activeIndex) <i class="fa-solid fa-check"></i>
                                @elseif($currentIndex == $activeIndex) <i class="fa-solid fa-fire-burner"></i>
                                @endif
                        </div>
                        <div class="tl-content">
                            <div class="tl-title">{{ $label }}</div>
                        </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="order-footer">

            <div id="kot-{{ $order->id }}" style="display:none;">

                <div class="kot-data"
                    data-order-id="{{ $order->id }}"
                    data-customer="{{ $order->passenger_name }}"
                    data-passengers="{{ $order->passenger_count ?? 1 }}"
                    data-seats="{{ !empty($order->passenger_seats)
        ? collect($order->passenger_seats)->pluck('seat_number')->implode(', ')
        : ($order->seat_number ?? 'N/A') }}"
                    data-bus="{{ $order->bus->bus_number ?? 'N/A' }}"
                    data-route="{{
        $order->bus
            ? (
                ($order->bus->route_from && $order->bus->route_to)
                    ? $order->bus->route_from . ' → ' . $order->bus->route_to
                    : (
                        $order->bus->busRoute
                            ? $order->bus->busRoute->source . ' → ' . $order->bus->busRoute->destination
                            : 'N/A'
                    )
              )
            : 'N/A'
     }}"
                    data-created="{{ $order->created_at->format('M d, Y h:i A') }}"
                    data-total="{{ $order->total_amount }}"
                    data-payment="{{ strtoupper($order->payment_method ?? 'ONLINE') }}"
                    data-instructions="{{ $order->cooking_instructions ?? '' }}">

                    @foreach($order->items as $item)

                    <div
                        class="kot-item"
                        data-name="{{ $item->menuItem->item_name ?? 'Unknown Item' }}"
                        data-quantity="{{ $item->quantity }}"></div>

                    @endforeach

                </div>

            </div>


            <div>
                <div class="total-label">Total Amount</div>
                <div class="order-total">
                    ₹{{ $order->total_amount }}
                    <span style="font-size: 0.75rem; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; color: #475569; margin-left: 8px; vertical-align: middle;">{{ strtoupper($order->payment_method ?? 'ONLINE') }}</span>
                </div>
            </div>

            <div class="action-buttons">

                <button
                    type="button"
                    class="btn-action"
                    style="background:#111827; color:white;"
                    onclick="printKOT('{{ route('vendor.orders.kot', $order->id) }}')">
                    <i class="fa-solid fa-print"></i>
                    Print KOT
                </button>

                @if(strtolower($order->order_status) == 'pending')
                <form action="{{ route('vendor.orders.status', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="btn-action btn-reject" onclick="return confirm('Are you sure you want to reject this order?');">Reject</button>
                </form>
                <form action="{{ route('vendor.orders.status', $order->id) }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="preparing">
                    <button type="submit" class="btn-action btn-accept"><i class="fa-solid fa-check"></i> Accept</button>
                </form>
                @elseif(in_array(strtolower($order->order_status), ['preparing', 'packing', 'ready', 'on_the_way']))
                <form action="{{ route('vendor.orders.status', $order->id) }}" method="POST" class="d-inline w-100">
                    @csrf
                    <input type="hidden" name="status" value="bus_arrived">
                    <button type="submit" class="btn-action btn-ready w-100 justify-content-center" style="background:#f59e0b;"><i class="fa-solid fa-bus"></i> Mark Bus Arrived</button>
                </form>
                @elseif(strtolower($order->order_status) == 'bus_arrived')
                <form action="{{ route('vendor.orders.status', $order->id) }}" method="POST" class="d-inline w-100">
                    @csrf
                    <input type="hidden" name="status" value="completed">
                    <button type="submit" class="btn-action w-100 justify-content-center" style="background:#059669; color:white; border:none; border-radius:8px; padding:10px;"><i class="fa-solid fa-clipboard-check"></i> Deliver Food</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@empty
<div class="col-12">
    <div class="empty-state">
        <i class="fa-solid fa-clipboard-list"></i>
        <h4>No Active Orders</h4>
        <p>You don't have any active orders right now. When customers place orders, they will appear here.</p>
    </div>
</div>
@endforelse
</div>

<!-- Past Orders Container -->
<div id="pastOrdersContainer" class="row" style="display: none;">
    @forelse($pastOrders as $order)
    <div class="col-xl-4 col-md-6 mb-4 past-order-col" data-status="{{ strtolower($order->order_status) }}" id="order-{{ $order->id }}">
        <div class="order-card h-100 d-flex flex-column" style="opacity: 0.85;">
            <div class="order-header">
                <div>
                    <div class="order-id" style="color: #475569;">
                        #ORD-{{ $order->id }}
                    </div>
                    <div class="order-time">
                        <i class="fa-regular fa-calendar"></i> {{ $order->created_at->format('M d, Y h:i A') }}
                        @if($order->bus)
                        &bull; <i class="fa-solid fa-bus"></i> {{ $order->bus->bus_number }}
                        @if($order->bus->route_from && $order->bus->route_to)
                        ({{ $order->bus->route_from }} &rarr; {{ $order->bus->route_to }})
                        @elseif($order->bus->busRoute)
                        ({{ $order->bus->busRoute->source }} &rarr; {{ $order->bus->busRoute->destination }})
                        @endif
                        @endif
                    </div>


                    <div class="order-passenger-info"
                        style="margin-top: 8px; font-size: 0.85rem; color: #64748b;">

                        <div>
                            <i class="fa-regular fa-user"></i>
                            <strong>{{ $order->passenger_name }}</strong>
                        </div>

                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-phone" style="font-size: 0.75rem;"></i>
                            {{ $order->user->mobile ?? 'N/A' }}
                        </div>

                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-users"></i>
                            <strong>Passengers:</strong>
                            {{ $order->passenger_count ?? 1 }}
                        </div>
                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-venus-mars"></i>
                            <strong>Gender:</strong>
                            {{ ucfirst($order->gender ?? 'N/A') }}
                        </div>

                        <div style="margin-top: 5px;">
                            <i class="fa-solid fa-chair"></i>
                            <strong>Seat Nos:</strong>

                            @if(!empty($order->passenger_seats))
                            {{ collect($order->passenger_seats)->pluck('seat_number')->implode(', ') }}
                            @else
                            {{ $order->seat_number ?? 'N/A' }}
                            @endif
                        </div>
                    </div>

                    @if($order->cooking_instructions)
                    <div style="margin-top: 10px; font-size: 0.85rem; background: #f8fafc; padding: 10px 12px; border-radius: 8px; border-left: 4px solid #94a3b8; color: #475569;">
                        <i class="fa-solid fa-utensils" style="margin-right: 6px;"></i> <strong>Instructions:</strong> {{ $order->cooking_instructions }}
                    </div>
                    @endif
                </div>
                <div class="status-badge status-{{ strtolower($order->order_status) }}">
                    {{ str_replace('_', ' ', $order->order_status) }}
                </div>
            </div>

            <div class="order-items">
                @foreach($order->items as $item)
                <div class="item-row">
                    <div style="display: flex; align-items: center;">
                        <span class="item-qty" style="background: #f1f5f9; color: #475569;">{{ $item->quantity }}x</span>
                        @if($item->menuItem && $item->menuItem->image)
                        <img src="{{ \Str::startsWith($item->menuItem->image, 'http') ? $item->menuItem->image : asset('storage/' . $item->menuItem->image) }}" class="item-thumbnail" style="opacity: 0.8;" alt="">
                        @else
                        <img src="https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=100&q=80" class="item-thumbnail" style="opacity: 0.8;" alt="">
                        @endif
                        <span class="item-name" style="color: #475569;">{{ $item->menuItem->item_name ?? 'Unknown Item' }}</span>
                    </div>
                    <div class="item-price" style="color: #64748b;">₹{{ $item->price * $item->quantity }}</div>
                </div>
                @endforeach
            </div>

            <div class="order-footer" style="padding-top: 15px;">
                <div>
                    <div class="total-label">Total Amount</div>


                    <div class="order-total" style="color: #334155;">
                        ₹{{ $order->total_amount }}
                        <span style="font-size: 0.75rem; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; color: #475569; margin-left: 8px; vertical-align: middle;">{{ strtoupper($order->payment_method ?? 'ONLINE') }}</span>
                    </div>
                </div>

                <div>
                    <button
                        type="button"
                        class="btn-action"
                        style="background:#111827; color:white;"
                        onclick="printKOT('{{ route('vendor.orders.kot', $order->id) }}')">
                        <i class="fa-solid fa-print"></i>
                        Print KOT
                    </button>
                </div>

            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="empty-state">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <h4>No Past Orders</h4>
            <p>Your order history is currently empty.</p>
        </div>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
    function printKOT(url) {
        const iframe = document.createElement('iframe');

        iframe.style.position = 'fixed';
        iframe.style.width = '1px';
        iframe.style.height = '1px';
        iframe.style.left = '-9999px';
        iframe.style.border = '0';

        document.body.appendChild(iframe);

        iframe.onload = function() {
            setTimeout(function() {
                iframe.contentWindow.focus();
                iframe.contentWindow.print();
            }, 500);
        };

        iframe.src = url;

        // Remove iframe after printing
        iframe.contentWindow?.addEventListener('afterprint', function() {
            document.body.removeChild(iframe);
        });
    }






    function switchTab(tab) {
        // Update buttons
        document.querySelectorAll('.custom-tab').forEach(el => el.classList.remove('active'));

        if (tab === 'active') {
            document.querySelectorAll('.custom-tab')[0].classList.add('active');
            document.getElementById('activeOrdersContainer').style.display = '';
            document.getElementById('pastOrdersContainer').style.display = 'none';
        } else if (tab === 'past') {
            document.querySelectorAll('.custom-tab')[1].classList.add('active');
            document.getElementById('activeOrdersContainer').style.display = 'none';
            document.getElementById('pastOrdersContainer').style.display = '';

            // Show only completed
            document.querySelectorAll('.past-order-col').forEach(el => {
                if (el.getAttribute('data-status') === 'completed') {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        } else if (tab === 'cancelled') {
            document.querySelectorAll('.custom-tab')[2].classList.add('active');
            document.getElementById('activeOrdersContainer').style.display = 'none';
            document.getElementById('pastOrdersContainer').style.display = '';

            // Show only cancelled
            document.querySelectorAll('.past-order-col').forEach(el => {
                if (el.getAttribute('data-status') === 'cancelled') {
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            });
        }
    }

    // Initialize tab filtering if there's a status param in URL
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        const status = urlParams.get('status');
        if (status === 'cancelled') {
            switchTab('cancelled');
        } else if (status === 'completed') {
            switchTab('past');
        }
    });
</script>
@endpush
