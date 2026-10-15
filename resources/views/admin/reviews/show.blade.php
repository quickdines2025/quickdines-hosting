@extends('layouts.admin')

@section('title', 'Review Details')

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

    .review-card {
        background: white;
        border-radius: 12px;
        padding: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
        border: 1px solid var(--border-color);
        margin-bottom: 30px;
    }

    .review-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        border-bottom: 1px solid var(--border-color);
        padding-bottom: 20px;
        margin-bottom: 20px;
    }

    .rating-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 8px 15px;
        border-radius: 8px;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .rating-high {
        background: #f0fdf4;
        color: #16a34a;
        border: 1px solid #bbf7d0;
    }

    .rating-mid {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .rating-low {
        background: #fef2f2;
        color: #ef4444;
        border: 1px solid #fecaca;
    }

    .review-content {
        font-size: 1.1rem;
        color: #334155;
        line-height: 1.6;
        margin-bottom: 30px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 8px;
        border-left: 4px solid #6366f1;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .info-block {
        background: #f8fafc;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
    }

    .info-label {
        font-size: 0.75rem;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 5px;
    }

    .info-value {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }

    .info-link {
        color: #6366f1;
        text-decoration: none;
    }

    .info-link:hover {
        text-decoration: underline;
    }

    .btn-delete-large {
        background: #ef4444;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-delete-large:hover {
        background: #dc2626;
    }
</style>

<div class="page-header">
    <div>
        <h1 class="page-title">Review Details</h1>
        <div class="breadcrumb">
            <a href="{{ route('admin.dashboard') }}">Home</a> &gt;
            <a href="{{ route('admin.reviews.index') }}">Reviews & Ratings</a> &gt;
            Details
        </div>
    </div>

    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this review?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-delete-large">
            <i class="fa-solid fa-trash"></i> Delete Review
        </button>
    </form>
</div>

<div class="review-card">
    <div class="review-header">
        <div>
            <h2 style="margin:0 0 5px 0; font-size:1.25rem; color:#1e293b;">Review #{{ $review->id }}</h2>
            <p style="margin:0; font-size:0.85rem; color:#64748b;"><i class="fa-regular fa-clock"></i> Posted on {{ $review->created_at->format('F d, Y \a\t h:i A') }}</p>
        </div>

        @php
        $ratingClass = 'rating-high';
        if($review->rating == 3) $ratingClass = 'rating-mid';
        if($review->rating < 3) $ratingClass='rating-low' ;
            @endphp
            <div class="rating-badge {{ $ratingClass }}">
            <i class="fa-solid fa-star"></i> {{ number_format($review->rating, 1) }} / 5.0
    </div>
</div>

<div class="review-content">
    @if($review->review_text)
    "{{ $review->review_text }}"
    @else
    <span style="color:#94a3b8; font-style:italic;">Customer left a rating without a text review.</span>
    @endif
</div>

<div class="info-grid">
    <div class="info-block">
        <div class="info-label">Customer Information</div>
        <div class="info-value">
            @if($review->user)
            <a href="{{ route('admin.users.show', $review->user_id) }}" class="info-link">
                <i class="fa-solid fa-user" style="margin-right:5px;"></i>{{ $review->user->name }}
            </a>
            <div style="font-size:0.8rem; color:#64748b; margin-top:3px; font-weight:normal;">{{ $review->user->mobile }}</div>
            @else
            <span style="color:#94a3b8;">User Deleted</span>
            @endif
        </div>
    </div>

    <div class="info-block">
        <div class="info-label">Restaurant Information</div>
        <div class="info-value">
            @if($review->restaurant)
            <a href="#" class="info-link">
                <i class="fa-solid fa-store" style="margin-right:5px;"></i>{{ $review->restaurant->restaurant_name }}
            </a>
            <div style="font-size:0.8rem; color:#64748b; margin-top:3px; font-weight:normal;">{{ $review->restaurant->cuisines ?? 'Restaurant' }}</div>
            @else
            <span style="color:#94a3b8;">Restaurant Deleted</span>
            @endif
        </div>
    </div>

    <div class="info-block">
        <div class="info-label">Order Details</div>
        <div class="info-value">
            @if($review->order)
            <a href="{{ route('admin.orders.show', $review->order_id) ?? '#' }}" class="info-link">
                <i class="fa-solid fa-receipt" style="margin-right:5px;"></i>#ORD{{ str_pad($review->order_id, 4, '0', STR_PAD_LEFT) }}
            </a>
            <div style="font-size:0.8rem; color:#64748b; margin-top:3px; font-weight:normal;">
                Amount: ₹{{ number_format($review->order->total_amount ?? 0, 2) }}
            </div>
            @else
            <span style="color:#94a3b8;">Order Not Found</span>
            @endif
        </div>
    </div>
</div>
</div>

@endsection
