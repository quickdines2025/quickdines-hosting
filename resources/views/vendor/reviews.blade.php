@extends('layouts.vendor')

@section('title', 'Customer Reviews')

@push('styles')
<style>
    .page-title { font-size: 24px; font-weight: 800; display: flex; flex-direction: column; gap: 4px; margin-bottom: 24px; }
    .page-title span { font-size: 13px; font-weight: 500; color: #64748b; }
    
    .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 24px; max-width: 600px; }
    .stat-card {
        background: #fff; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 16px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0;
    }
    .s-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 20px; }
    .s-info p { font-size: 12px; color: #64748b; margin-bottom: 4px; font-weight: 600; }
    .s-info h3 { font-size: 22px; font-weight: 800; color: #0f172a; }

    .reviews-container { background: #fff; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; overflow: hidden; }
    .reviews-header { padding: 20px; border-bottom: 1px solid #e2e8f0; }
    .reviews-header h3 { font-size: 16px; font-weight: 700; color: #0f172a; margin: 0; }
    
    .review-item { padding: 20px; border-bottom: 1px solid #f1f5f9; display: flex; gap: 16px; }
    .review-item:last-child { border-bottom: none; }
    
    .reviewer-avatar { width: 40px; height: 40px; border-radius: 50%; background: #f8fafc; color: #64748b; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 16px; flex-shrink: 0; }
    .review-content { flex: 1; }
    
    .review-meta { display: flex; justify-content: space-between; margin-bottom: 8px; }
    .reviewer-name { font-weight: 700; color: #0f172a; font-size: 14px; }
    .review-date { font-size: 12px; color: #94a3b8; }
    
    .stars { color: #fbbf24; font-size: 12px; margin-bottom: 8px; display: flex; gap: 2px; }
    .star-empty { color: #e2e8f0; }
    
    .review-text { font-size: 14px; color: #475569; line-height: 1.5; margin-bottom: 8px; }
    .review-order { font-size: 12px; color: #64748b; background: #f8fafc; display: inline-block; padding: 4px 10px; border-radius: 6px; border: 1px solid #e2e8f0; }
    
    .empty-state { text-align: center; padding: 60px 20px; color: #64748b; }
    .empty-icon { font-size: 48px; color: #cbd5e1; margin-bottom: 16px; }
</style>
@endpush

@section('content')

<!-- Stats -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="s-icon" style="background: #fffbeb; color: #d97706;"><i class="fa-solid fa-star"></i></div>
        <div class="s-info">
            <p>Average Rating</p>
            <h3>{{ number_format($averageRating, 1) }} <span style="font-size: 14px; color: #64748b; font-weight: 500;">/ 5.0</span></h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="s-icon" style="background: #f0fdf4; color: #16a34a;"><i class="fa-solid fa-comment-dots"></i></div>
        <div class="s-info">
            <p>Total Reviews</p>
            <h3>{{ $totalReviews }}</h3>
        </div>
    </div>
</div>

<!-- Reviews List -->
<div class="reviews-container">
    <div class="reviews-header">
        <h3>Recent Reviews</h3>
    </div>
    
    @forelse($reviews as $review)
        <div class="review-item">
            <div class="reviewer-avatar">
                {{ substr(is_object($review) && isset($review->user) ? $review->user->name : ($review->user_name ?? 'U'), 0, 1) }}
            </div>
            <div class="review-content">
                <div class="review-meta">
                    <div class="reviewer-name">{{ is_object($review) && isset($review->user) ? $review->user->name : ($review->user_name ?? 'User') }}</div>
                    <div class="review-date">
                        @if(is_object($review))
                            {{ $review->created_at->diffForHumans() }}
                        @else
                            {{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}
                        @endif
                    </div>
                </div>
                
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        @if($i <= $review->rating)
                            <i class="fa-solid fa-star"></i>
                        @else
                            <i class="fa-solid fa-star star-empty"></i>
                        @endif
                    @endfor
                </div>
                
                <div class="review-text">
                    {{ $review->review_text ?? 'No written feedback provided.' }}
                </div>
                
                @if($review->order_id)
                    <div class="review-order">
                        Order #ORD{{ $review->order_id + 12344 }}
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="empty-state">
            <div class="empty-icon"><i class="fa-regular fa-comments"></i></div>
            <h4>No reviews yet</h4>
            <p>You haven't received any customer reviews yet.</p>
        </div>
    @endforelse
    
    @if(method_exists($reviews, 'links') && $reviews->hasPages())
        <div style="padding: 15px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: flex-end;">
            {{ $reviews->links('pagination::bootstrap-4') }}
        </div>
    @endif
</div>

@endsection
