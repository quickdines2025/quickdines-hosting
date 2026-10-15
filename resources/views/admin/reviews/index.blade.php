@extends('layouts.admin')

@section('title', 'Reviews & Ratings')

@section('content')
<style>
    .page-header {
        margin-bottom: 25px;
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        justify-content: space-between;
        align-items: center;
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

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 15px;
        margin-bottom: 25px;
    }
    @media (max-width: 1200px) {
        .stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (max-width: 768px) {
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card {
            padding: 12px;
            gap: 10px;
            flex-direction: column;
            text-align: center;
        }
        .stat-icon { width: 40px; height: 40px; font-size: 1.1rem; }
        .stat-info h3 { font-size: 1.2rem; }
        .stat-info p { font-size: 0.75rem; }
    }
    .stat-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
    .stat-icon.total { background: #f3e8ff; color: #a855f7; }
    .stat-icon.average { background: #fffbeb; color: #f59e0b; }
    .stat-icon.five-star { background: #f0fdf4; color: #22c55e; }
    .stat-icon.four-star { background: #eff6ff; color: #3b82f6; }
    .stat-icon.low-star { background: #fef2f2; color: #ef4444; }

    .stat-info h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }
    .stat-info p {
        margin: 0;
        font-size: 0.8rem;
        color: #64748b;
        font-weight: 500;
    }

    /* Filters */
    .filter-bar {
        display: flex;
        gap: 12px;
        margin-bottom: 20px;
        background: white;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        border: 1px solid var(--border-color);
        align-items: center;
        flex-wrap: wrap;
    }
    .search-input {
        flex: 1 1 200px;
        position: relative;
    }
    .search-input input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.85rem;
        outline: none;
    }
    .search-input i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .filter-select, .filter-date { flex: 1 1 140px; }
    .filter-select select, .filter-date input {
        width: 100%;
        padding: 10px 35px 10px 15px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 0.85rem;
        outline: none;
        appearance: none;
        color: #475569;
        font-weight: 500;
        background: white url("data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E") no-repeat right 10px center;
    }
    .filter-date {
        position: relative;
    }
    .filter-date input {
        padding-left: 35px;
        background: white;
    }
    .filter-date i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }
    .btn-export, .btn-reset {
        flex: 1 1 100px; justify-content: center;
        background: white;
        border: 1px solid var(--border-color);
        padding: 10px 15px;
        border-radius: 8px;
        color: #475569;
        font-size: 0.85rem;
        cursor: pointer;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Table */
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
        font-size: 0.75rem;
        font-weight: 600;
        color: #64748b;
        padding: 15px 20px;
        background: #f8fafc;
        border-bottom: 1px solid var(--border-color);
        text-align: left;
    }
    .table-custom td {
        padding: 15px 20px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color);
        color: #334155;
        font-size: 0.85rem;
    }
    .table-custom tr:last-child td {
        border-bottom: none;
    }

    .cell-title {
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 2px;
    }
    .cell-subtitle {
        font-size: 0.75rem;
        color: #64748b;
    }
    
    .rating-stars {
        color: #fbbf24;
        font-size: 0.8rem;
        margin-right: 5px;
    }
    .rating-stars i.empty {
        color: #cbd5e1;
    }
    .rating-value {
        font-weight: 600;
        color: #475569;
        font-size: 0.85rem;
    }

    .btn-view {
        background: white;
        color: #6366f1;
        border: 1px solid #c7d2fe;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-view:hover {
        background: #e0e7ff;
    }
    
    .pagination-custom {
        display: flex;
        justify-content: flex-end;
        gap: 5px;
    }
    .pagination-custom .page-link {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 500;
        color: #64748b;
        background: white;
        border: 1px solid var(--border-color);
        text-decoration: none;
    }
    .pagination-custom .page-link.active {
        background: #6366f1;
        color: white;
        border-color: #6366f1;
    }
</style>

<div class="page-header">
    <h1 class="page-title">Reviews & Ratings</h1>
    <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Home</a> &gt; Reviews & Ratings
    </div>
</div>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total"><i class="fa-solid fa-comments"></i></div>
        <div class="stat-info">
            <p>Total Reviews</p>
            <h3>{{ $stats['total'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon average"><i class="fa-regular fa-star"></i></div>
        <div class="stat-info">
            <p>Average Rating</p>
            <h3>{{ $stats['average'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon five-star"><i class="fa-solid fa-star"></i></div>
        <div class="stat-info">
            <p>5 Star Reviews</p>
            <h3>{{ $stats['five_star'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon four-star"><i class="fa-regular fa-star-half-stroke"></i></div>
        <div class="stat-info">
            <p>4 Star Reviews</p>
            <h3>{{ $stats['four_star'] }}</h3>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon low-star"><i class="fa-solid fa-thumbs-down"></i></div>
        <div class="stat-info">
            <p>3 Star & Below</p>
            <h3>{{ $stats['three_below'] }}</h3>
        </div>
    </div>
</div>

<form action="{{ route('admin.reviews.index') }}" method="GET" id="filterForm">
    <div class="filter-bar">
        <div class="search-input">
            <i class="fa-solid fa-search"></i>
            <input type="text" name="search" placeholder="Search by customer or restaurant..." value="{{ request('search') }}" onchange="document.getElementById('filterForm').submit()">
        </div>
        <div class="filter-select">
            <select name="restaurant_id" onchange="document.getElementById('filterForm').submit()">
                <option value="all">All Restaurants</option>
                @foreach($restaurants as $restaurant)
                    <option value="{{ $restaurant->id }}" {{ request('restaurant_id') == $restaurant->id ? 'selected' : '' }}>{{ $restaurant->restaurant_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-select">
            <select name="rating" onchange="document.getElementById('filterForm').submit()">
                <option value="all">All Ratings</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
            </select>
        </div>
        <div class="filter-date">
            <i class="fa-regular fa-calendar"></i>
            <input type="date" name="from_date" value="{{ request('from_date') }}" onchange="document.getElementById('filterForm').submit()" style="padding-left:35px; border-radius:8px; border:1px solid var(--border-color); font-size:0.85rem; outline:none; height:38px;">
        </div>
        <span style="color:#94a3b8; font-size:0.85rem;">to</span>
        <div class="filter-date">
            <input type="date" name="to_date" value="{{ request('to_date') }}" onchange="document.getElementById('filterForm').submit()" style="padding-left:15px; border-radius:8px; border:1px solid var(--border-color); font-size:0.85rem; outline:none; height:38px;">
        </div>
        <a href="{{ route('admin.reviews.index') }}" class="btn-export" style="background:#f1f5f9; text-decoration:none;"><i class="fa-solid fa-rotate-left"></i> Reset</a>
        <button type="submit" name="export" value="1" class="btn-export ms-auto">
            <i class="fa-solid fa-download"></i> Export
        </button>
    </div>
</form>

<div class="table-container">
    <table class="table-custom">
        <thead>
            <tr>
                <th>Customer</th>
                <th>Restaurant</th>
                <th>Rating</th>
                <th>Review</th>
                <th>Order ID</th>
                <th>Reviewed On</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reviews as $review)
            <tr>
                <td>
                    <div class="cell-title">{{ $review->user->name ?? 'N/A' }}</div>
                    <div class="cell-subtitle">{{ $review->user->mobile ?? 'N/A' }}</div>
                </td>
                <td>
                    <div class="cell-title">{{ $review->restaurant->restaurant_name ?? 'N/A' }}</div>
                    <div class="cell-subtitle">{{ $review->restaurant->cuisines ?? 'N/A' }}</div>
                </td>
                <td>
                    <span class="rating-stars">
                        @for($i=1; $i<=5; $i++)
                            @if($i <= $review->rating)
                                <i class="fa-solid fa-star"></i>
                            @else
                                <i class="fa-solid fa-star empty"></i>
                            @endif
                        @endfor
                    </span>
                    <span class="rating-value">{{ number_format($review->rating, 1) }}</span>
                </td>
                <td style="color: #475569; font-size: 0.8rem; max-width: 250px;">{{ Str::limit($review->review_text, 50) }}</td>
                <td style="color: #64748b; font-size: 0.8rem;">#ORD{{ str_pad($review->order_id, 4, '0', STR_PAD_LEFT) }}</td>
                <td>
                    <div class="cell-title" style="font-weight: 500;">{{ $review->created_at->format('M d, Y') }}</div>
                    <div class="cell-subtitle">{{ $review->created_at->format('h:i A') }}</div>
                </td>
                <td>
                    <div style="display:flex; gap:8px;">
                        <a href="{{ route('admin.reviews.show', $review->id) }}" class="btn-view">View</a>
                        <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this review?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete" style="background: white; border: 1px solid #fecaca; padding: 6px 10px; border-radius: 6px; color: #ef4444; font-size: 0.8rem; cursor: pointer; transition: all 0.2s ease;"><i class="fa-solid fa-trash"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #64748b;">
                    <i class="fa-solid fa-comments" style="font-size: 2rem; color: #cbd5e1; margin-bottom: 10px;"></i>
                    <p>No reviews found.</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="p-3" style="border-top: 1px solid var(--border-color);">
        {{ $reviews->links('pagination::bootstrap-4') }}
    </div>
</div>

@endsection
