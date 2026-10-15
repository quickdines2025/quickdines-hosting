@extends('layouts.frontend')

@section('content')
<style>
    /* Search Results Hero Section */
    .search-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 60px 0;
        text-align: center;
        color: white;
        margin-bottom: -40px;
        /* Pull content up over the background */
    }

    .search-hero h1 {
        font-family: 'Inter', sans-serif;
        font-size: 36px;
        font-weight: 800;
        margin-bottom: 10px;
        letter-spacing: -0.5px;
    }

    .search-hero h1 span {
        color: #2563eb;
    }

    .search-hero p {
        font-size: 16px;
        color: #94a3b8;
        font-weight: 500;
    }

    /* Main Container */
    .search-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 10;
        padding-bottom: 80px;
    }

    /* Grid Layout */
    .menu-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
        gap: 25px;
        margin-top: 20px;
    }

    @media (max-width: 768px) {
        .menu-items-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Premium Item Card */
    .menu-item-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 16px;
        display: flex;
        gap: 20px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .menu-item-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .item-img-container {
        position: relative;
        width: 140px;
        height: 140px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    }

    .item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .menu-item-card:hover .item-img {
        transform: scale(1.05);
    }

    .item-details {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 6px;
    }

    .item-title {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 4px;
    }

    .restaurant-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        font-weight: 600;
        color: #2563eb;
        text-decoration: none;
        background: #fff0e6;
        padding: 4px 10px;
        border-radius: 6px;
        transition: background 0.2s;
    }

    .restaurant-link:hover {
        background: #ffe4d6;
    }

    .item-desc {
        font-size: 13px;
        color: #64748b;
        line-height: 1.5;
        margin-top: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .item-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px dashed #e2e8f0;
    }

    .item-price {
        font-weight: 800;
        color: #0f172a;
        font-size: 20px;
        display: flex;
        align-items: center;
    }

    .item-price span {
        font-size: 14px;
        color: #94a3b8;
        font-weight: 600;
        margin-left: 4px;
    }

    .btn-add {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
    }

    /* Empty State styling */
    .empty-state-premium {
        background: white;
        border-radius: 20px;
        padding: 80px 40px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        border: 1px solid #f1f5f9;
        margin-top: 40px;
    }

    .empty-icon {
        width: 80px;
        height: 80px;
        background: #f8fafc;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 24px;
        font-size: 32px;
        color: #94a3b8;
    }

    .empty-state-premium h3 {
        font-size: 24px;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 12px;
    }

    .empty-state-premium p {
        font-size: 16px;
        color: #64748b;
        margin-bottom: 30px;
        max-width: 400px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-home {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: #0f172a;
        color: white;
        font-weight: 600;
        border-radius: 10px;
        text-decoration: none;
        transition: background 0.2s;
    }

    .btn-home:hover {
        background: #1e293b;
    }
</style>

<!-- Hero Section -->
<!-- <div class="search-hero">
    <h1>Search Results for <span>"{{ $query }}"</span></h1>
    <p>We found {{ $menus->count() }} delicious {{ $menus->count() === 1 ? 'item' : 'items' }} waiting for you</p>
</div> -->

<!-- new -->

<div class="search-hero">

    @if(!empty($category))
    <h1>
        {{ $category }}
    </h1>

    <p>
        We found {{ $menus->count() }}
        delicious {{ $menus->count() === 1 ? 'item' : 'items' }}
        in this category
    </p>

    @elseif(!empty($query))

    <h1>
        Search Results for
        <span>"{{ $query }}"</span>
    </h1>

    <p>
        We found {{ $menus->count() }}
        delicious {{ $menus->count() === 1 ? 'item' : 'items' }}
        waiting for you
    </p>

    @else

    <h1>
        Menu Items
    </h1>

    <p>
        We found {{ $menus->count() }}
        delicious {{ $menus->count() === 1 ? 'item' : 'items' }}
    </p>

    @endif

</div>

<!-- new end -->



<div style="background-color: #f8fafc; min-height: calc(100vh - 350px);">
    <div class="search-container">
        @if($menus->count() > 0)
        <div class="menu-items-grid">
            @foreach($menus as $menu)
            <div class="menu-item-card">
                <div class="item-img-container">
                    <img src="{{ $menu->image ? (\Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80' }}" class="item-img" alt="Item Image">
                </div>

                <div class="item-details">
                    <div>
                        <div class="item-header">
                            <div class="item-title">{{ $menu->item_name }}</div>
                        </div>
                        <!-- <a href="{{ url('/restaurant/' . $menu->restaurant_id) }}" class="restaurant-link">
                            <i class="fa-solid fa-store"></i> {{ $menu->restaurant->restaurant_name ?? 'QuickDines' }}
                        </a> -->
                        <a href="{{ url('/restaurant/' . $menu->restaurant_id) }}" class="restaurant-link">
                            <i class="fa-solid fa-store"></i>
                            {{ $menu->restaurant->restaurant_name ?? 'QuickDines' }}
                        </a>
                        <div class="item-desc">
                            {{ $menu->description ?: 'Delicious ' . strtolower($menu->item_name) . ' prepared fresh. ' . ($menu->timing_type ? 'Available for ' . $menu->timing_type : '') }}
                        </div>
                    </div>

                    <div class="item-bottom">
                        <div class="item-price">₹{{ number_format($menu->price, 0) }}</div>
                        <button class="btn-add" onclick="window.location.href='{{ url('/restaurant/' . $menu->restaurant_id) }}'">
                            View Menu <i class="fa-solid fa-arrow-right"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state-premium">
            <div class="empty-icon">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h3>Nothing found for "{{ $query }}"</h3>
            <p>We couldn't find any food items matching your search. Try adjusting your keywords or browse our full menu.</p>
            <a href="{{ url('/') }}" class="btn-home">
                <i class="fa-solid fa-house"></i> Back to Homepage
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
