@extends('layouts.customer')

@section('title', 'Favourites - QuickDines')

@section('styles')
<style>
    .col-header { padding: 0 5px; margin-bottom: 20px; }
    .col-header h2 { font-size: 24px; font-weight: 800; color: #000; margin-bottom: 5px; }
    .col-header p { font-size: 14px; color: var(--text-muted); }

    .col-tabs { display: flex; border-bottom: 1px solid #e2e8f0; margin-bottom: 25px; gap: 20px; max-width: 800px; }
    .col-tab { padding: 12px 15px; font-size: 14px; font-weight: 600; color: var(--text-muted); cursor: pointer; position: relative; white-space: nowrap; display: flex; align-items: center; gap: 8px; }
    .col-tab.active { color: var(--primary); }
    .col-tab.active::after { content: ''; position: absolute; bottom: -1px; left: 0; right: 0; height: 2px; background: var(--primary); }

    .favs-grid { display: flex; flex-direction: column; gap: 20px; max-width: 800px; }

    .fav-card { display: flex; align-items: flex-start; gap: 15px; position: relative; }
    .fav-img { width: 80px; height: 80px; border-radius: 10px; object-fit: cover; }
    .oc-fallback { width: 80px; height: 80px; border-radius: 10px; background: #1e293b; color: white; display: flex; align-items: center; justify-content: center; font-size: 20px; font-weight: 700; }
    
    .fav-details { flex: 1; }
    .fav-details h4 { font-size: 16px; font-weight: 700; color: #000; margin-bottom: 5px; padding-right: 25px; }
    .fav-heart { position: absolute; top: 20px; right: 20px; color: #ef4444; font-size: 18px; cursor: pointer; }
    .fav-rating { display: inline-flex; align-items: center; gap: 4px; background: #f0fdf4; color: #16a34a; padding: 3px 8px; border-radius: 6px; font-size: 11px; font-weight: 700; margin-bottom: 5px; }
    .fav-rest { font-size: 13px; color: var(--text-muted); margin-bottom: 8px; }
    .fav-price { font-size: 16px; font-weight: 800; color: #000; }
    .btn-add-fav { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; border: none; padding: 6px 15px; border-radius: 6px; font-weight: 700; font-size: 13px; cursor: pointer; transition: all 0.2s; position: absolute; bottom: 20px; right: 20px; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2); }
    .btn-add-fav:hover { transform: translateY(-2px); box-shadow: 0 6px 15px rgba(37, 99, 235, 0.3); }

    .explore-banner { background: #fff0e6; border-radius: 12px; padding: 30px; text-align: center; margin-top: 20px; max-width: 800px; }
    .explore-banner i { font-size: 30px; color: var(--primary); margin-bottom: 15px; }
    .explore-banner h4 { font-size: 16px; font-weight: 700; color: #000; margin-bottom: 8px; }
    .explore-banner p { font-size: 13px; color: var(--text-muted); margin-bottom: 20px; line-height: 1.5; }
    .btn-explore { display: inline-block; background: var(--primary); color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-size: 13px; font-weight: 700; }

    .empty-state { text-align: center; padding: 60px 20px; border: 1px dashed #e2e8f0; border-radius: 12px; max-width: 800px; }
    .empty-state i { font-size: 40px; color: #cbd5e1; margin-bottom: 15px; }
    .empty-state p { font-size: 15px; color: var(--text-muted); }
</style>
@endsection

@section('content')
<div class="col-header">
    <h2>Favourites</h2>
    <p>Your favourite food items</p>
</div>



<div class="favs-grid">
    @php
        $favorites = \App\Models\Favorite::with('menu.restaurant')
            ->where('user_id', auth()->id())
            ->get();
    @endphp

    @forelse($favorites as $fav)
    @php
        $menu = $fav->menu;
        $restName = $menu->restaurant->restaurant_name ?? 'Restaurant';
        $initials = strtoupper(substr($menu->item_name, 0, 2));
    @endphp
    <div class="dash-card fav-card">
        @if($menu->image)
            <img src="{{ Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image) }}" class="fav-img" alt="Image">
        @else
            <div class="oc-fallback">{{ $initials }}</div>
        @endif
        
        <div class="fav-details">
            <h4>{{ $menu->item_name }}</h4>
            <div class="fav-heart"><i class="fa-solid fa-heart"></i></div>
            <div class="fav-rating">
                {{ number_format($menu->rating ?? 4.5, 1) }} <i class="fa-solid fa-star"></i>
            </div>
            <div class="fav-rest">{{ $restName }}</div>
            <div class="fav-price">₹{{ number_format($menu->price, 0) }}</div>
        </div>
        <button class="btn-add-fav" onclick="window.location.href='{{ url('/restaurant/' . $menu->restaurant_id) }}'">Add <i class="fa-solid fa-plus"></i></button>
    </div>
    @empty
        <div class="empty-state">
            <i class="fa-regular fa-heart"></i>
            <p>Heart your favorite food items to see them here.</p>
        </div>
    @endforelse
</div>

<div class="explore-banner">
    <i class="fa-regular fa-heart"></i>
    <h4>Love exploring new dishes?</h4>
    <p>Add more food items to your favourites and get quick access anytime!</p>
    <a href="{{ route('home') }}" class="btn-explore">Browse Menu</a>
</div>
@endsection
