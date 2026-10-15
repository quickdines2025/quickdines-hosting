@extends('layouts.frontend')

@section('content')
<style>
    /* Search Results Hero Section */
    .search-hero {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        padding: 60px 0;
        text-align: center;
        color: white;
        margin-bottom: -40px; /* Pull content up over the background */
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
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        position: relative;
        z-index: 10;
        padding-bottom: 80px;
    }

    /* Grid Layout */
    .menu-items-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 30px;
        margin-top: 30px;
    }

    @media (max-width: 768px) {
        .menu-items-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }

    /* Premium Item Card */
    .menu-item-card {
        background: #ffffff;
        border-radius: 20px;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(226, 232, 240, 0.6);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .menu-item-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
        border-color: #cbd5e1;
    }

    .item-img-container {
        position: relative;
        width: 100%;
        height: 200px;
        overflow: hidden;
        background: #f1f5f9;
    }

    .item-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .menu-item-card:hover .item-img {
        transform: scale(1.08);
    }

    .item-details {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .item-title {
        font-family: 'Inter', sans-serif;
        font-size: 18px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.3;
        margin-bottom: 8px;
    }

    .restaurant-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #1d4ed8;
        text-decoration: none;
        background: #eff6ff;
        padding: 6px 12px;
        border-radius: 20px;
        transition: background 0.2s;
        align-self: flex-start;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .restaurant-link:hover {
        background: #bfdbfe;
    }

    .item-desc {
        font-size: 14px;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 20px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex-grow: 1;
    }

    .item-bottom {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 15px;
        border-top: 1px dashed #e2e8f0;
    }

    .item-price {
        font-weight: 800;
        color: #0f172a;
        font-size: 22px;
    }

    .btn-add {
        background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
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

    /* Pagination */
    .pagination-wrapper {
        margin-top: 50px;
        display: flex;
        justify-content: center;
    }
    
    .pagination {
        display: flex;
        gap: 8px;
        list-style: none;
    }
    
    .pagination li a, .pagination li span {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: white;
        color: #0f172a;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        border: 1px solid #e2e8f0;
        transition: all 0.2s;
    }
    
    .pagination li.active span {
        background: #2563eb;
        color: white;
        border-color: #2563eb;
    }
    
    .pagination li a:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
    }

    .search-filter {
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: white;
        padding: 15px 25px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
    }

    .search-input-wrapper {
        position: relative;
        flex: 1;
        max-width: 400px;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #94a3b8;
    }

    .search-input-wrapper input {
        width: 100%;
        padding: 12px 15px 12px 40px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        outline: none;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        transition: border-color 0.2s;
    }
    
    .search-input-wrapper input:focus {
        border-color: #2563eb;
    }
</style>

<!-- Hero Section -->
<div class="search-hero">
    <h1>All <span>Dishes</span></h1>
    <p>Explore all the delicious dishes available across our restaurants</p>
</div>

<div style="background-color: #f8fafc; min-height: calc(100vh - 350px);">
    <div class="search-container">
        
        <div class="search-filter">
            <h3 style="font-size: 18px; font-weight: 700; color: #0f172a;">Menu Items</h3>
            <form action="{{ route('search') }}" method="GET" class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass"></i>
                <input type="text" name="query" placeholder="Search for any dish...">
            </form>
        </div>

        @php
            $userFavorites = auth()->check() ? \App\Models\Favorite::where('user_id', auth()->id())->pluck('menu_id')->toArray() : [];
        @endphp
        @if($menus->count() > 0)
        <div class="menu-items-grid">
            @foreach($menus as $menu)
            <div class="menu-item-card">
                <div class="item-img-container">
                    <img src="{{ $menu->image ? (\Illuminate\Support\Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80' }}" class="item-img" alt="Item Image">
                    <div class="fav-icon" style="position: absolute; top: 12px; right: 12px; color: {{ in_array($menu->id, $userFavorites) ? '#ef4444' : '#fff' }}; font-size: 22px; z-index: 10; cursor: pointer; text-shadow: 0 2px 5px rgba(0,0,0,0.4);" onclick="event.preventDefault(); event.stopPropagation(); toggleFavorite({{ $menu->id }}, this);">
                        <i class="{{ in_array($menu->id, $userFavorites) ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                    </div>
                </div>
                
                <div class="item-details">
                    <a href="{{ url('/restaurant/' . $menu->restaurant_id) }}" class="restaurant-link">
                        <i class="fa-solid fa-store"></i> {{ $menu->restaurant->restaurant_name ?? 'QuickDines' }}
                    </a>
                    
                    <div class="item-title">{{ $menu->item_name }}</div>
                    
                    <div class="item-desc">
                        {{ $menu->description ?: 'Delicious ' . strtolower($menu->item_name) . ' prepared fresh. ' . ($menu->timing_type ? 'Available for ' . $menu->timing_type : '') }}
                    </div>
                    
                    <div class="item-bottom">
                        <div class="item-price">₹{{ number_format($menu->price, 0) }}</div>
                        <button class="btn-add" onclick="window.location.href='{{ url('/restaurant/' . $menu->restaurant_id) }}'">
                            Add <i class="fa-solid fa-plus"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="pagination-wrapper">
            {{ $menus->links('pagination::bootstrap-4') }}
        </div>
        
        @else
        <div class="empty-state-premium" style="margin-top: 0; padding: 60px 40px;">
            <div class="empty-icon">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h3>No dishes available</h3>
            <p>We couldn't find any food items at the moment. Please check back later.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function toggleFavorite(menuId, element) {
    @if(!auth()->check())
        window.location.href = "{{ route('customer.auth') }}";
        return;
    @endif

    fetch("{{ route('favorite.toggle') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ menu_id: menuId })
    })
    .then(response => response.json())
    .then(data => {
        let icon = element.querySelector('i');
        if (data.status === 'added') {
            icon.classList.remove('fa-regular');
            icon.classList.add('fa-solid');
            icon.style.color = '#ef4444';
        } else {
            icon.classList.remove('fa-solid');
            icon.classList.add('fa-regular');
            icon.style.color = '#fff';
        }
    });
}
</script>
@endpush
@endsection
