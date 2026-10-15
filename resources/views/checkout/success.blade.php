@extends('layouts.frontend')

@section('content')
<style>
    body { background-color: #fff8f5; }
    .success-wrapper {
        padding: 40px 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }
    .success-card {
        background: #ffffff;
        border-radius: 24px;
        width: 100%;
        max-width: 500px;
        padding: 25px 25px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    /* Confetti Background Pattern */
    .success-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; height: 130px;
        background-image: radial-gradient(#fca5a5 15%, transparent 16%), radial-gradient(#fdba74 15%, transparent 16%), radial-gradient(#86efac 15%, transparent 16%);
        background-size: 80px 80px;
        background-position: 0 0, 40px 40px, 20px 60px;
        opacity: 0.15;
        z-index: 0;
    }
    .sc-content { position: relative; z-index: 1; }
    .check-badge {
        width: 50px; height: 50px;
        background: #dcfce7;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 10px;
        box-shadow: 0 10px 25px rgba(34, 197, 94, 0.25);
    }
    .check-badge i { font-size: 26px; color: #22c55e; }
    .bus-illustration {
        width: 100%; max-width: 300px;
        margin: 0 auto 10px;
        display: block;
        border-radius: 10px;
    }
    .sc-title {
        font-size: 20px; font-weight: 800; color: #1e293b; margin-bottom: 4px;
    }
    .sc-title span { color: #22c55e; }
    .sc-desc {
        font-size: 13px; color: #64748b; line-height: 1.4; margin-bottom: 15px;
        padding: 0 5px;
    }
    .btn-group {
        display: flex; gap: 12px; margin-bottom: 15px;
    }
    .btn-track {
        flex: 1; background: #2563eb; color: white; padding: 10px; border-radius: 10px;
        font-size: 14px; font-weight: 600; text-decoration: none; border: none; display: flex; align-items: center; justify-content: center; gap: 6px; transition: 0.2s;
    }
    .btn-track:hover { background: #1d4ed8; color: white; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2); }
    
    .btn-cancel {
        flex: 1; background: white; color: #2563eb; padding: 10px; border-radius: 10px;
        font-size: 14px; font-weight: 600; text-decoration: none; border: 1.5px solid #93c5fd; display: flex; align-items: center; justify-content: center; gap: 6px; transition: 0.2s;
    }
    .btn-cancel:hover { background: #eff6ff; color: #1d4ed8; transform: translateY(-2px); border-color: #1d4ed8; }
    
    .note-box {
        background: #eff6ff; border-radius: 10px; padding: 12px;
        display: flex; gap: 10px; text-align: left; align-items: flex-start;
    }
    .note-icon { color: #2563eb; font-size: 18px; }
    .note-text h5 { font-size: 13px; font-weight: 700; color: #1e40af; margin: 0 0 2px 0; }
    .note-text p { font-size: 12px; color: #1e3a8a; margin: 0; line-height: 1.3; }
</style>

<div class="success-wrapper">
    <div class="success-card">
        <div class="sc-content">
            <div class="check-badge">
                <i class="fa-solid fa-check"></i>
            </div>
            
            <img src="{{ asset('images/success.jpg') }}" class="bus-illustration" alt="QuickDines Delivery Bus">
            
            <h2 class="sc-title">Your Order Placed <span>Successfully!</span></h2>
            <p class="sc-desc">Thank you for choosing QuickDines.<br>We have received your order and it is being processed.</p>
            
            <div class="btn-group">
                <a href="{{ route('track-order') }}" class="btn-track">
                    <i class="fa-solid fa-bag-shopping"></i> Track Your Order
                </a>
                <form action="{{ route('customer.order.cancel', $order->id ?? 0) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" class="btn-cancel" style="width: 100%;">
                        <i class="fa-regular fa-circle-xmark"></i> Cancel Order
                    </button>
                </form>
            </div>
            
            <div class="note-box">
                <div class="note-icon"><i class="fa-solid fa-circle-info"></i></div>
                <div class="note-text">
                    <h5>Note:</h5>
                    <p>If the restaurant accepts your order, you cannot cancel it.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    @if(isset($order) && $order)
    const orderId = {{ $order->id }};
    let currentStatus = '{{ strtolower($order->order_status ?? "pending") }}';

    setInterval(function() {
        if(currentStatus === 'completed' || currentStatus === 'cancelled') return;

        fetch(`/api/order-status/${orderId}`)
            .then(response => response.json())
            .then(data => {
                if(data.status && data.status !== 'unknown' && data.status !== currentStatus) {
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error checking order status:', error));
    }, 5000); // Check every 5 seconds
    @endif
</script>
@endpush
