@extends('layouts.customer')

@section('title', 'My Orders - QuickDines')

@section('styles')
<style>
    /* My Orders Styles */
    .col-header { padding: 0 5px; margin-bottom: 20px; }
    .col-header h2 { font-size: 24px; font-weight: 800; color: #000; margin-bottom: 5px; }
    .col-header p { font-size: 14px; color: var(--text-muted); }
    
    .col-tabs { display: flex; border-bottom: 1px solid #e2e8f0; margin-bottom: 25px; gap: 20px; }
    .col-tab { padding: 12px 15px; font-size: 14px; font-weight: 600; color: var(--text-muted); cursor: pointer; position: relative; white-space: nowrap; }
    .col-tab.active { color: var(--primary); }
    .col-tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: var(--primary); }

    .badge-status { padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 700; display: inline-block; }
    .bg-green { background: #dcfce7; color: #16a34a; }
    .bg-red { background: #fee2e2; color: #ef4444; }
    .bg-blue { background: #e0f2fe; color: #0284c7; }

    .oc-left { display: flex; gap: 15px; align-items: center; }
    .oc-logo { width: 60px; height: 60px; border-radius: 50%; object-fit: cover; }
    .oc-fallback { width: 60px; height: 60px; border-radius: 50%; background: #1e293b; color: white; display: flex; align-items: center; justify-content: center; font-size: 16px; font-weight: 700; }
    .oc-details h4 { font-size: 16px; font-weight: 700; margin-bottom: 5px; display: flex; align-items: center; gap: 10px; }
    .oc-details p { font-size: 13px; color: var(--text-muted); margin-bottom: 8px; line-height: 1.4; }
    .oc-time { font-size: 12px; color: #94a3b8; font-weight: 500; display: flex; gap: 10px; align-items: center; }
    
    .oc-right { text-align: right; }
    .oc-items { font-size: 13px; color: var(--text-muted); margin-bottom: 5px; }
    .oc-price { font-size: 18px; font-weight: 800; color: #000; display: flex; align-items: center; justify-content: flex-end; gap: 5px; }
    .oc-price i { font-size: 12px; color: var(--primary); }
    
    .orders-grid { display: flex; flex-direction: column; gap: 20px; max-width: 800px; }
    
    .empty-state { text-align: center; padding: 60px 20px; border: 1px dashed #e2e8f0; border-radius: 12px; max-width: 800px; }
    .empty-state i { font-size: 40px; color: #cbd5e1; margin-bottom: 15px; }
    .empty-state p { font-size: 15px; color: var(--text-muted); }

    @media (max-width: 768px) {
        .order-card-item {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 15px;
        }
        .oc-right {
            width: 100%;
            display: flex;
            flex-direction: row !important;
            justify-content: space-between !important;
            align-items: center;
        }
        .oc-right > div {
            align-items: flex-start !important;
        }
        .oc-right .btn-outline-primary {
            padding: 8px 15px !important;
            font-size: 13px !important;
        }
    }
</style>
@endsection

@section('content')
<div class="col-header">
    <h2>My Orders</h2>
    <p>View and manage all your orders</p>
</div>

<div class="col-tabs" id="orderTabs" style="max-width: 800px;">
    <div class="col-tab active" onclick="filterOrders('current', this)">Current</div>
    <div class="col-tab" onclick="filterOrders('completed', this)">Completed</div>
    <div class="col-tab" onclick="filterOrders('cancelled', this)">Cancelled</div>
</div>

<div class="orders-grid" id="ordersContainer">
    @php
        $filteredOrders = collect($orders);
    @endphp
    
    @forelse($filteredOrders as $order)
        @php
            $isCancelled = $order->order_status == 'cancelled';
            $isCompleted = $order->order_status == 'completed';
            
            if ($isCancelled) {
                $statusClass = 'bg-red';
                $statusText = 'Cancelled';
                $filterClass = 'cancelled';
            } elseif ($isCompleted) {
                $statusClass = 'bg-green';
                $statusText = 'Completed';
                $filterClass = 'completed';
            } else {
                $statusClass = 'bg-blue';
                $statusText = ucwords(str_replace('_', ' ', $order->order_status));
                $filterClass = 'current';
            }
            
            $restName = $order->restaurant->restaurant_name ?? 'Restaurant';
            $initials = strtoupper(substr($restName, 0, 2));
        @endphp
        <div class="dash-card order-card-item {{ $filterClass }}">
            <div class="oc-left">
                @if($order->restaurant && $order->restaurant->logo)
                    <img src="{{ Str::startsWith($order->restaurant->logo, 'http') ? $order->restaurant->logo : asset('storage/' . $order->restaurant->logo) }}" class="oc-logo" alt="Logo">
                @else
                    <div class="oc-fallback">{{ $initials }}</div>
                @endif
                <div class="oc-details">
                    <h4>{{ $restName }} <span class="badge-status {{ $statusClass }}">{{ $statusText }}</span></h4>
                    <p>{{ $order->restaurant->city ?? 'City' }}<br>Bus No. {{ $order->bus->bus_number ?? 'N/A' }}</p>
                    <div class="oc-time">
                        <span><i class="fa-regular fa-calendar"></i> {{ $order->created_at->format('d M Y') }}</span>
                        <span><i class="fa-regular fa-clock"></i> {{ $order->created_at->format('h:i A') }}</span>
                    </div>
                </div>
            </div>
            <div class="oc-right" style="display:flex; flex-direction:row; align-items:center; gap:20px;">
                <div style="display:flex; flex-direction:column; align-items:flex-end;">
                    <div class="oc-items">{{ $order->items->sum('quantity') }} Items</div>
                    <div class="oc-price">₹{{ number_format($order->total_amount, 0) }}</div>
                </div>
                
                @if($filterClass == 'current')
                    <a href="{{ url('/track-order') }}" class="btn btn-sm btn-outline-primary" style="border-radius:20px; font-size:12px; color:#2563eb; border-color:#2563eb; padding:5px 15px; background:transparent; white-space:nowrap; cursor:pointer; text-decoration:none;">Track Order</a>
                @elseif($order->order_status == 'completed' && !\App\Models\Review::where('order_id', $order->id)->exists())
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="event.preventDefault(); openReviewModal({{ $order->id }})" style="border-radius:20px; font-size:12px; color:#2563eb; border-color:#2563eb; padding:5px 15px; background:transparent; white-space:nowrap; cursor:pointer;">Leave a Review</button>
                @elseif($order->order_status == 'completed')
                    <span style="font-size:12px; color:#10b981; font-weight:500; white-space:nowrap;"><i class="fa-solid fa-check-circle"></i> Reviewed</span>
                @endif
                
                <i class="fa-solid fa-chevron-right" style="color:#cbd5e1; font-size:14px; margin-left:5px;"></i>
            </div>
        </div>
    @empty
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p>No orders found.</p>
        </div>
    @endforelse
    
    <!-- Empty state for filtering -->
    <div id="filterEmptyState" class="empty-state" style="display: none;">
        <i class="fa-solid fa-receipt"></i>
        <p>No orders in this category.</p>
    </div>
</div>

<style>
/* Custom Review Modal */
.custom-modal-overlay {
    position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center; z-index: 1000;
    opacity: 0; transition: opacity 0.3s ease;
}
.custom-modal-overlay.show { opacity: 1; }
.custom-modal {
    background: white; border-radius: 20px; width: 90%; max-width: 400px;
    padding: 30px; box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    transform: translateY(20px); transition: transform 0.3s ease;
    text-align: center; position: relative;
}
.custom-modal-overlay.show .custom-modal { transform: translateY(0); }
.cm-close {
    position: absolute; top: 15px; right: 20px; font-size: 20px; color: #94a3b8;
    cursor: pointer; transition: color 0.2s;
}
.cm-close:hover { color: #ef4444; }
.cm-title { font-size: 1.2rem; font-weight: 700; color: #0f172a; margin-bottom: 5px; }
.cm-subtitle { font-size: 0.9rem; color: #64748b; margin-bottom: 25px; }
.rating-stars { display: flex; justify-content: center; gap: 8px; margin-bottom: 20px; }
.rating-stars .star { font-size: 32px; color: #e2e8f0; cursor: pointer; transition: all 0.2s; }
.rating-stars .star:hover { transform: scale(1.1); }
.cm-textarea {
    width: 100%; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 15px;
    font-size: 0.9rem; resize: none; margin-bottom: 25px; outline: none; transition: border-color 0.2s; font-family: 'Inter', sans-serif;
}
.cm-textarea:focus { border-color: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.1); }
.cm-submit {
    background: #2563eb; color: white; border: none; width: 100%; padding: 12px;
    border-radius: 10px; font-weight: 600; cursor: pointer; transition: background 0.2s; font-size: 1rem;
}
.cm-submit:hover { background: #ea3f0d; }
</style>

<div class="custom-modal-overlay" id="customReviewModal">
    <div class="custom-modal">
        <i class="fa-solid fa-xmark cm-close" onclick="closeReviewModal()"></i>
        <div class="cm-title">Rate your experience</div>
        <div class="cm-subtitle">How was your food and delivery?</div>
        
        <form id="reviewForm" method="POST">
            @csrf
            <div class="rating-stars">
                <i class="fa-solid fa-star star" data-val="1"></i>
                <i class="fa-solid fa-star star" data-val="2"></i>
                <i class="fa-solid fa-star star" data-val="3"></i>
                <i class="fa-solid fa-star star" data-val="4"></i>
                <i class="fa-solid fa-star star" data-val="5"></i>
            </div>
            <input type="hidden" name="rating" id="ratingInput" required>
            <textarea name="review_text" class="cm-textarea" rows="3" placeholder="Tell us what you liked (optional)"></textarea>
            <button type="submit" class="cm-submit">Submit Review</button>
        </form>
    </div>
</div>

@section('scripts')
<script>
    function openReviewModal(orderId) {
        document.getElementById('reviewForm').action = '{{ url("/orders") }}/' + orderId + '/review';
        document.querySelectorAll('.rating-stars .star').forEach(s => s.style.color = '#e2e8f0');
        document.getElementById('ratingInput').value = '';
        
        const modal = document.getElementById('customReviewModal');
        modal.style.display = 'flex';
        setTimeout(() => modal.classList.add('show'), 10);
    }
    
    function closeReviewModal() {
        const modal = document.getElementById('customReviewModal');
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }

    document.querySelectorAll('.rating-stars .star').forEach(star => {
        star.addEventListener('click', function() {
            let val = this.getAttribute('data-val');
            document.getElementById('ratingInput').value = val;
            document.querySelectorAll('.rating-stars .star').forEach(s => {
                if(s.getAttribute('data-val') <= val) {
                    s.style.color = '#ffc107'; // Gold
                } else {
                    s.style.color = '#e2e8f0';
                }
            });
        });
    });

    function filterOrders(status, tabElement) {
        // Update active tab
        document.querySelectorAll('.col-tab').forEach(tab => tab.classList.remove('active'));
        tabElement.classList.add('active');
        
        // Filter cards
        const cards = document.querySelectorAll('.order-card-item');
        let visibleCount = 0;
        
        cards.forEach(card => {
            if (card.classList.contains(status)) {
                card.style.display = 'flex';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });
        
        // Handle empty state
        const emptyState = document.getElementById('filterEmptyState');
        const hasAnyOrders = {{ $filteredOrders->count() > 0 ? 'true' : 'false' }};
        
        if (hasAnyOrders) {
            if (visibleCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }
    }
    
    // Initialize filter to current
    document.addEventListener('DOMContentLoaded', () => {
        const activeTab = document.querySelector('.col-tab.active');
        if (activeTab) {
            filterOrders('current', activeTab);
        }
    });
</script>
@endsection
@endsection
