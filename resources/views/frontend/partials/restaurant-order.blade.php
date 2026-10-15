<style>
    .restaurant-detail-wrapper {
        background: #f8f9fa;
        padding: 40px 0;
        min-height: 80vh;
    }

    .rest-header-card {
        background: white;
        border-radius: 16px;
        padding: 0;
        margin-bottom: 30px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        overflow: hidden;
    }

    .rest-cover {
        height: 180px;
        background-size: cover;
        background-position: center;
    }

    .rest-info-bar {
        padding: 20px 30px;
        display: flex;
        gap: 24px;
        align-items: flex-start;
        position: relative;
    }

    .rest-logo-large {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid white;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        margin-top: -50px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }

    .rest-logo-large img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .rest-main-details h1 {
        font-size: 24px;
        font-weight: 800;
        color: #000;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .rest-main-details .rest-rating {
        background: #22c55e;
        color: white;
        padding: 4px 8px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .rest-main-details p {
        color: #64748b;
        font-size: 14px;
        margin-bottom: 12px;
    }

    .rest-stats {
        display: flex;
        gap: 20px;
        color: #475569;
        font-size: 13px;
        font-weight: 500;
    }

    .rest-stats span {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Main Grid */
    .menu-layout {
        display: grid;
        grid-template-columns: 200px 1fr 320px;
        gap: 30px;
    }

    /* Mobile Top Bar (Transparent) */
    .rest-mobile-nav {
        display: none;
    }

    /* Mobile Floating Cart */
    .mobile-floating-cart {
        display: none;
    }



    /* Sidebar */
    .menu-sidebar {
        background: white;
        border-radius: 12px;
        padding: 20px 0;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.03);
        position: sticky;
        top: 20px;
    }

    .cat-link {
        display: block;
        padding: 12px 24px;
        color: #475569;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        border-left: 3px solid transparent;
        transition: 0.2s;
    }

    .cat-link:hover,
    .cat-link.active {
        color: #2563eb;
        border-left-color: #2563eb;
        background: #fff0e6;
    }

    .sidebar-promo {
        margin: 20px 24px;
        background: #fff0e6;
        border: 1px dashed #2563eb;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }

    .sidebar-promo h4 {
        color: #1d4ed8;
        font-size: 14px;
        font-weight: 800;
        margin-bottom: 4px;
    }

    .sidebar-promo p {
        font-size: 11px;
        color: #475569;
        margin-bottom: 8px;
    }

    .sidebar-promo .code {
        font-size: 11px;
        font-weight: 700;
        color: #2563eb;
    }

    .rest-tabs {
        display: none;
    }

    @media (max-width: 768px) {
        .rest-tabs {
            display: flex;
            justify-content: space-around;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 15px;
            margin-top: 5px;
        }

        .rest-tab {
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 700;
            color: #64748b;
            text-decoration: none;
            border-bottom: 2px solid transparent;
        }

        .rest-tab.active {
            color: #5b21b6;
            border-bottom-color: #5b21b6;
        }
    }

    /* Menu Items */
    .menu-category {
        margin-bottom: 40px;
    }

    .menu-category-title {
        font-size: 18px;
        font-weight: 800;
        color: #000;
        margin-bottom: 15px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
    }

    .menu-category-title .view-all {
        font-size: 12px;
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
    }

    .menu-items-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .menu-item-card {
        background: white;
        border-radius: 12px;
        padding: 12px;
        display: flex;
        gap: 15px;
        border: 1px solid #f1f5f9;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
    }

    .item-img {
        width: 100px;
        height: 100px;
        border-radius: 12px;
        object-fit: cover;
        flex-shrink: 0;
    }

    .item-details {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .item-header-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 4px;
    }

    .item-title {
        font-size: 15px;
        font-weight: 700;
        color: #000;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .item-price {
        font-weight: 800;
        color: #000;
        font-size: 14px;
    }

    .item-rating {
        font-size: 11px;
        color: #000;
        font-weight: 700;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    .item-desc {
        font-size: 11px;
        color: #64748b;
        line-height: 1.4;
        margin-bottom: auto;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .item-bottom {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 8px;
    }

    .btn-add {
        background: white;
        color: #5b21b6;
        border: 1px solid #e2e8f0;
        padding: 6px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 13px;
        cursor: pointer;
        transition: 0.2s;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
    }

    .btn-add:hover {
        background: #f8f9fa;
        border-color: #5b21b6;
    }

    /* Cart Quantity Controller */
    .qty-ctrl {
        display: none;
        /* hidden by default, shown when added */
        align-items: center;
        gap: 10px;
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        padding: 2px 8px;
    }

    .qty-btn {
        background: none;
        border: none;
        color: #10b981;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
    }

    .qty-btn.minus {
        color: #ef4444;
    }

    .qty-num {
        font-weight: 700;
        font-size: 13px;
        width: 16px;
        text-align: center;
        color: #000;
    }

    /* Cart Sidebar */
    .cart-sidebar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        position: sticky;
        top: 20px;
    }

    .cart-header {
        font-size: 18px;
        font-weight: 800;
        color: #000;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
    }

    .cart-header span {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
    }

    .cart-items {
        margin-bottom: 20px;
        min-height: 50px;
    }

    .cart-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .ci-info {
        display: flex;
        gap: 10px;
        align-items: flex-start;
    }

    .ci-img {
        width: 40px;
        height: 40px;
        border-radius: 6px;
        object-fit: cover;
    }

    .ci-name {
        font-size: 13px;
        font-weight: 600;
        color: #000;
        margin-bottom: 2px;
    }

    .ci-price {
        font-size: 12px;
        color: #475569;
    }

    .cooking-instr {
        margin-bottom: 20px;
    }

    .cooking-instr p {
        font-size: 12px;
        color: #475569;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .cooking-instr input {
        width: 100%;
        padding: 10px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 12px;
        outline: none;
    }

    .bill-details {
        border-top: 1px dashed #e2e8f0;
        padding-top: 15px;
        margin-bottom: 20px;
    }

    .bill-details h4 {
        font-size: 14px;
        font-weight: 700;
        margin-bottom: 10px;
        color: #000;
    }

    .bd-row {
        display: flex;
        justify-content: space-between;
        font-size: 13px;
        color: #475569;
        margin-bottom: 8px;
    }

    .bd-total {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: 800;
        color: #000;
        margin-top: 15px;
        border-top: 1px solid #e2e8f0;
        padding-top: 15px;
    }

    .btn-checkout {
        width: 100%;
        background: #2563eb;
        color: white;
        border: none;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 15px;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 8px;
        text-decoration: none;
    }

    .btn-checkout:hover {
        background: #1d4ed8;
    }

    .est-delivery {
        margin-top: 15px;
        background: #f0fdf4;
        border: 1px solid #bbf7d0;
        padding: 12px;
        border-radius: 8px;
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .est-delivery i {
        color: #16a34a;
        font-size: 20px;
    }

    .est-text p {
        font-size: 11px;
        color: #16a34a;
        font-weight: 600;
        margin-bottom: 2px;
    }

    .est-text span {
        font-size: 10px;
        color: #15803d;
    }

    .est-time {
        font-weight: 700;
        color: #16a34a;
        font-size: 13px;
        margin-left: auto;
    }

    /* Empty Cart State */
    .empty-cart {
        text-align: center;
        padding: 20px 0;
        color: #94a3b8;
    }

    .empty-cart i {
        font-size: 30px;
        margin-bottom: 10px;
        opacity: 0.5;
    }

    .empty-cart p {
        font-size: 13px;
    }


    /* Mobile Styles Moved to Bottom */
    @media (max-width: 768px) {
        .restaurant-detail-wrapper {
            padding: 0;
            background: #fff;
        }

        .container {
            padding: 0 15px;
        }

        .rest-header-card {
            border-radius: 0;
            box-shadow: none;
            margin-bottom: 20px;
            position: relative;
            background: transparent;
        }

        .rest-cover {
            height: 220px;
            border-radius: 0 0 20px 20px;
        }

        /* Transparent Mobile Nav */
        .rest-mobile-nav {
            display: flex;
            justify-content: space-between;
            position: absolute;
            top: 15px;
            left: 15px;
            right: 15px;
            z-index: 10;
        }

        .rest-nav-btn {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            text-decoration: none;
        }

        .rest-nav-right {
            display: flex;
            gap: 10px;
        }

        /* Overlapping Info Card */
        .rest-info-bar {
            background: white;
            margin: -60px 15px 0 15px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 5;
            position: relative;
        }

        .rest-logo-large {
            display: none;
        }

        .rest-main-details {
            width: 100%;
            text-align: center;
        }

        .rest-main-details h1 {
            justify-content: center;
            font-size: 20px;
        }

        .rest-main-details p {
            font-size: 13px;
            margin-bottom: 8px;
        }

        .rest-stats {
            justify-content: center;
            font-size: 11px;
            flex-wrap: wrap;
            gap: 12px;
        }

        .menu-layout {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        /* Horizontal Sidebar */
        .menu-sidebar {
            position: static;
            display: flex;
            overflow-x: auto;
            padding: 10px 0;
            box-shadow: none;
            gap: 10px;
            border-bottom: 1px solid #f1f5f9;
            border-radius: 0;
            scrollbar-width: none;
        }

        .menu-sidebar::-webkit-scrollbar {
            display: none;
        }

        .cat-link {
            flex-shrink: 0;
            border-left: none;
            border-bottom: 2px solid transparent;
            padding: 8px 16px;
            background: #f8f9fa;
            border-radius: 20px;
            font-size: 13px;
        }

        .cat-link.active {
            border-bottom: none;
            background: #5b21b6;
            color: white;
        }

        .sidebar-promo {
            display: none;
        }

        /* Items Grid: compact 2-per-row cards on mobile */
        .menu-items-grid {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .menu-item-card {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            border: 1px solid #f1f5f9;
            box-shadow: 0 1px 6px rgba(0, 0, 0, 0.04);
            padding: 8px;
            border-radius: 12px;
            min-width: 0;
        }

        .menu-item-card .item-img {
            width: 100%;
            height: 88px;
        }

        .menu-item-card .item-details {
            min-width: 0;
        }

        .menu-item-card .item-header-row {
            flex-direction: column;
            align-items: stretch;
            gap: 2px;
        }

        .menu-item-card .item-title {
            min-width: 0;
            font-size: 12.5px;
        }

        .menu-item-card .item-name-text {
            min-width: 0;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            display: inline-block;
            vertical-align: middle;
            max-width: calc(100% - 20px);
        }

        .menu-item-card .item-price {
            font-size: 13px;
        }

        .menu-item-card .item-rating,
        .menu-item-card .item-desc {
            display: none;
        }

        .menu-item-card .item-bottom {
            margin-top: 2px;
        }

        .menu-item-card .btn-add {
            width: 100%;
            padding: 7px 0;
            font-size: 12px;
            text-align: center;
        }

        .menu-item-card .qty-ctrl {
            width: 100%;
            justify-content: center;
        }

        /* Hide Desktop Cart */
        .cart-sidebar {
            display: none !important;
        }

        /* Floating Mobile Cart */
        .mobile-floating-cart {
            display: none;
            /* toggled by JS */
            position: fixed;
            bottom: 80px;
            /* Above bottom nav */
            left: 15px;
            right: 15px;
            background: #5b21b6;
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
            z-index: 100;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px rgba(91, 33, 182, 0.4);
            text-decoration: none;
        }

        .mfc-left {
            font-weight: 700;
            font-size: 15px;
        }

        .mfc-mid {
            font-weight: 800;
            font-size: 16px;
        }

        .mfc-right {
            font-weight: 800;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    }
</style>

<div class="restaurant-detail-wrapper">
    <!-- Mobile Top Navigation (Over Image) -->
    <div class="rest-mobile-nav">
        <a href="{{ route('home') }}" class="rest-nav-btn"><i class="fa-solid fa-arrow-left"></i></a>
        <div class="rest-nav-right">
            <a href="#" class="rest-nav-btn"><i class="fa-solid fa-share-nodes"></i></a>
            <a href="#" class="rest-nav-btn"><i class="fa-regular fa-heart"></i></a>
        </div>
    </div>

    <!-- Floating Mobile Cart Button -->
    <a href="#" class="mobile-floating-cart" id="mobile-cart-btn" onclick="goToCheckout(event)">
        <div class="mfc-left">🛒 <span id="m-cart-count">0</span> <span id="m-cart-label">Items</span></div>
        <div class="mfc-mid" id="m-cart-total">₹0</div>
        <div class="mfc-right">VIEW CART <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i></div>
    </a>

    <div class="container" style="padding-top: 0;">
        <!-- Header -->
        <div class="rest-header-card">
            <div class="rest-cover" style="background-image: url('{{ $restaurant->banner ? asset('storage/' . $restaurant->banner) : 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80' }}');"></div>
            <div class="rest-info-bar">
                <div class="rest-logo-large">
                    <img src="{{ $restaurant->logo ? asset('storage/' . $restaurant->logo) : 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?auto=format&fit=crop&w=400&q=80' }}" alt="Logo">
                </div>
                <div class="rest-main-details">
                    <h1>{{ $restaurant->restaurant_name }}
                        <div class="rest-rating"><i class="fa-solid fa-star"></i> {{ number_format($restaurant->rating ?? 4.0, 1) }}</div>
                    </h1>
                    <p>{{ $restaurant->cuisines ?? 'Various Cuisines' }} &bull; {{ $restaurant->address ? explode(',', $restaurant->address)[0] : 'Near You' }}</p>
                    <div class="rest-stats">
                        <span><i class="fa-regular fa-clock"></i> {{ $restaurant->delivery_time ?? '20-30 mins' }}</span>
                        <span><i class="fa-solid fa-location-dot"></i> Live Tracking</span>
                        @if($restaurant->discount)
                        <span style="color: #1d4ed8; font-weight: 700;"><i class="fa-solid fa-tag"></i> {{ $restaurant->discount }}</span>
                        @endif
                    </div>

                    <div style="display: flex; gap: 15px; align-items: center; justify-content: center; margin-top: 15px;">

                        <!-- Veg -->
                        <span style="display:flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:#16a34a; background:#f0fdf4; padding:4px 8px; border-radius:4px; border:1px solid #bbf7d0;">
                            <span style="width:10px; height:10px; border:2px solid #16a34a; display:inline-flex; align-items:center; justify-content:center; border-radius:2px;">
                                <span style="width:5px; height:5px; background:#16a34a; border-radius:50%;"></span>
                            </span>
                            Veg
                        </span>

                        <!-- Non-Veg -->
                        <span style="display:flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:#dc2626; background:#fef2f2; padding:4px 8px; border-radius:4px; border:1px solid #fecaca;">
                            <span style="width:10px; height:10px; border:2px solid #dc2626; display:inline-flex; align-items:center; justify-content:center; border-radius:2px;">
                                <span style="width:5px; height:5px; background:#dc2626; border-radius:50%;"></span>
                            </span>
                            Non-Veg
                        </span>

                        <!-- FSSAI -->
                        <span style="font-size:11px; font-weight:700; color:#475569; border:1px solid #e2e8f0; padding:4px 8px; border-radius:4px;">
                            <i class="fa-solid fa-shield-halved" style="color:#0ea5e9;"></i>
                            FSSAI
                        </span>

                    </div>


                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="menu-layout">
            <!-- Left Sidebar -->
            <div>
                <div class="menu-sidebar">
                    @foreach($groupedMenus as $category => $items)
                    <a href="#category-{{ Str::slug($category) }}" class="cat-link {{ $loop->first ? 'active' : '' }}">{{ $category }} ({{ count($items) }})</a>
                    @endforeach
                </div>
            </div>

            <!-- Center Menu -->
            <div>
                <!-- Mobile Tabs -->
                <div class="rest-tabs">
                    <a href="javascript:void(0)" class="rest-tab active" onclick="switchTab('menu', this)">Menu</a>
                    <a href="javascript:void(0)" class="rest-tab" onclick="switchTab('reviews', this)">Reviews</a>
                </div>

                <div id="menu-content">
                    <div class="menu-filter-bar" style="position:sticky;top:0;z-index:20;background:#fff;padding:10px 0;">
                        <input type="text" id="menu-search" placeholder="Search dishes..." oninput="applyMenuFilters()" style="width:100%;padding:10px 14px;border:1px solid #e2e8f0;border-radius:10px;font-size:14px;margin-bottom:8px;">
                        <div style="display:flex;gap:8px;">
                            <button type="button" class="type-chip active" data-type="all" onclick="setTypeFilter('all', this)">All</button>
                            <button type="button" class="type-chip" data-type="veg" onclick="setTypeFilter('veg', this)">🟢 Veg</button>
                            <button type="button" class="type-chip" data-type="non_veg" onclick="setTypeFilter('non_veg', this)">🔴 Non-Veg</button>
                        </div>
                        <div class="cat-chip-row">
                            <button type="button" class="cat-chip active" data-cat="all" onclick="setCatFilter('all', this)">All</button>
                            @foreach($groupedMenus as $category => $items)
                            <button type="button" class="cat-chip" data-cat="{{ Str::slug($category) }}" onclick="setCatFilter('{{ Str::slug($category) }}', this)">{{ $category }}</button>
                            @endforeach
                        </div>
                    </div>
                    <style>
                        .cat-chip-row {
                            display: none;
                            gap: 8px;
                            overflow-x: auto;
                            padding: 8px 0 2px;
                            white-space: nowrap;
                            -webkit-overflow-scrolling: touch;
                        }

                        .cat-chip {
                            flex: 0 0 auto;
                            padding: 6px 14px;
                            border: 1px solid #e2e8f0;
                            border-radius: 20px;
                            background: #fff;
                            font-size: 13px;
                            font-weight: 600;
                            cursor: pointer;
                        }

                        .cat-chip.active {
                            background: #2563eb;
                            color: #fff;
                            border-color: #2563eb;
                        }

                        @media (max-width:768px) {
                            .cat-chip-row {
                                display: flex;
                            }
                        }

                        .type-chip {
                            padding: 6px 14px;
                            border: 1px solid #e2e8f0;
                            border-radius: 20px;
                            background: #fff;
                            font-size: 13px;
                            font-weight: 600;
                            cursor: pointer;
                        }

                        .type-chip.active {
                            background: #0f172a;
                            color: #fff;
                            border-color: #0f172a;
                        }
                    </style>

                    @forelse($groupedMenus as $category => $items)
                    <div class="menu-category" id="category-{{ Str::slug($category) }}" data-cat="{{ Str::slug($category) }}">
                        <div class="menu-category-title">{{ $category }} <a href="#" class="view-all">View All</a></div>
                        <div class="menu-items-grid">
                            @foreach($items as $menu)
                            <div class="menu-item-card" data-name="{{ strtolower($menu->item_name) }}" data-type="{{ $menu->food_type ?? 'veg' }}">
                                <img src="{{ $menu->image ? (Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80' }}" class="item-img" alt="Item Image">
                                <div class="item-details">
                                    <div class="item-header-row">
                                        <div class="item-title"><span style="display:inline-block;width:12px;height:12px;border:1.5px solid {{ ($menu->food_type ?? 'veg') === 'non_veg' ? '#dc2626' : '#16a34a' }};border-radius:2px;position:relative;margin-right:4px;vertical-align:middle;flex-shrink:0;"><span style="position:absolute;inset:2px;border-radius:50%;background:{{ ($menu->food_type ?? 'veg') === 'non_veg' ? '#dc2626' : '#16a34a' }};"></span></span><span class="item-name-text">{{ $menu->item_name }}</span></div>
                                        <div class="item-price">₹{{ number_format($menu->price, 0) }}</div>
                                    </div>
                                    @if($menu->rating)
                                    <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> {{ number_format($menu->rating, 1) }}</div>
                                    @else
                                    <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> {{ number_format(4.5, 1) }}</div>
                                    @endif
                                    <div class="item-desc">
                                        {{ $menu->description ?? 'Delicious ' . strtolower($menu->item_name) . ' prepared fresh. ' . ($menu->timing_type ? 'Available for ' . $menu->timing_type : '') }}
                                    </div>
                                    <div class="item-bottom" data-item-id="{{ $menu->id }}">
                                        <button class="btn-add" onclick="addToCart({{ $menu->id }}, {{ $menu->restaurant_id }}, '{{ addslashes($menu->item_name) }}', {{ $menu->price }}, '{{ $menu->image ? (Str::startsWith($menu->image, 'http') ? $menu->image : asset('storage/' . $menu->image)) : 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=400&q=80' }}', this)">Add</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @empty
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        No menu items available for this restaurant yet.
                    </div>
                    @endforelse
                </div>

                <!-- Reviews Content -->
                <div id="reviews-content" style="display: none; padding: 20px 0;">
                    @if(isset($reviews) && count($reviews) > 0)
                    @foreach($reviews as $review)
                    <div style="border-bottom: 1px solid #e2e8f0; padding-bottom: 15px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <div style="font-weight: 700; color: #1e293b;">
                                @php
                                $user = \App\Models\User::find($review->user_id);
                                @endphp
                                {{ $user ? $user->name : 'Customer' }}
                            </div>
                            <div style="color: #64748b; font-size: 12px;">{{ $review->created_at->diffForHumans() }}</div>
                        </div>
                        <div style="color: #22c55e; font-size: 12px; margin-bottom: 8px;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <=$review->rating)
                                <i class="fa-solid fa-star"></i>
                                @else
                                <i class="fa-regular fa-star"></i>
                                @endif
                                @endfor
                        </div>
                        <div style="color: #475569; font-size: 14px; line-height: 1.5;">
                            {{ $review->review }}
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div style="text-align: center; padding: 40px; color: #64748b;">
                        No reviews yet for this restaurant.
                    </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar: Cart -->
            <div>
                <div class="cart-sidebar">
                    <div class="cart-header">
                        Your Order <span id="cart-count">0 items</span>
                    </div>

                    <div id="cart-items-container" class="cart-items">
                        <div class="empty-cart" id="empty-cart-msg">
                            <i class="fa-solid fa-basket-shopping"></i>
                            <p>Your cart is empty.</p>
                            <p>Add items from the menu.</p>
                        </div>
                    </div>

                    <div class="cooking-instr">
                        <p>Add cooking instructions (optional)</p>
                        <input type="text" placeholder="e.g. Less spicy, no onion, etc.">
                    </div>

                    <div class="bill-details">
                        <h4>Bill Details</h4>
                        <div class="bd-row">
                            <span>Item Total</span>
                            <span id="item-total">₹0</span>
                        </div>

                        <div class="bd-row">
                            <span>Platform Fee</span>
                            <span>₹{{ \App\Models\SystemSetting::getSetting('platform_commission', 10) }}</span>
                        </div>
                        <div class="bd-row">
                            <span>GST (5%)</span>
                            <span id="gst-amount">₹0</span>
                        </div>
                        <div class="bd-total">
                            <span>Total Amount</span>
                            <span id="grand-total" style="color: #1d4ed8;">₹0</span>
                        </div>
                    </div>

                    <a href="#" class="btn-checkout" id="checkout-btn" style="pointer-events: none; opacity: 0.5;" onclick="goToCheckout(event)">
                        Proceed to Checkout <i class="fa-solid fa-arrow-right"></i>
                    </a>

                    <div class="est-delivery">
                        <i class="fa-solid fa-shield-halved"></i>
                        <div class="est-text">
                            <p>Estimated Delivery</p>
                            <span>Food will be delivered to your seat</span>
                        </div>
                        <div class="est-time">{{ $restaurant->delivery_time ?? '20-30 mins' }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Smooth scrolling for category links
    document.querySelectorAll('.cat-link').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href');
            if (targetId !== '#') {
                document.querySelector(targetId).scrollIntoView({
                    behavior: 'smooth'
                });

                document.querySelectorAll('.cat-link').forEach(a => a.classList.remove('active'));
                this.classList.add('active');
            }
        });
    });

    // Simple JS logic to simulate cart behavior
    let cart = JSON.parse(localStorage.getItem('quickdines_cart')) || [];

    // Initial render
    document.addEventListener('DOMContentLoaded', () => {
        if (cart.length > 0) {
            renderCart();

            // Sync buttons for items already in cart
            cart.forEach(item => {
                const container = document.querySelector(`.item-bottom[data-item-id="${item.id}"]`);
                if (container) {
                    const btnAdd = container.querySelector('.btn-add');
                    if (btnAdd) {
                        btnAdd.style.display = 'none';
                        const ctrl = document.createElement('div');
                        ctrl.className = 'qty-ctrl';
                        ctrl.style.display = 'flex';
                        ctrl.innerHTML = `
                            <button class="qty-btn minus" onclick="updateQty(${item.id}, -1, this)">-</button>
                            <span class="qty-num">${item.qty}</span>
                            <button class="qty-btn" onclick="updateQty(${item.id}, 1, this)">+</button>
                        `;
                        container.appendChild(ctrl);
                    }
                }
            });
        }
    });

    function saveCart() {
        localStorage.setItem('quickdines_cart', JSON.stringify(cart));
    }

    async function goToCheckout(event) {
        if (event) event.preventDefault();
        if (!Array.isArray(cart) || cart.length === 0) {
            alert('Your cart is empty.');
            return;
        }

        const totals = JSON.parse(localStorage.getItem('quickdines_cart_totals') || 'null');
        try {
            const response = await fetch("{{ route('checkout.cart') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cart_data: JSON.stringify({
                            items: cart,
                            totals: totals
                        })
                    })
                });
            const data = await response.json();
            if (!response.ok || !data.success) throw new Error(data.message || 'Unable to open checkout.');
            window.location.href = data.redirect_url;
        } catch (error) {
            alert(error.message || 'Unable to open checkout. Please try again.');
        }
    }


    // ---- Search + Veg/Non-Veg filter ----
    let typeFilter = 'all';
    let catFilter = 'all';

    function setCatFilter(cat, el) {
        catFilter = cat;
        document.querySelectorAll('.cat-chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        applyMenuFilters();
    }

    function setTypeFilter(type, el) {
        typeFilter = type;
        document.querySelectorAll('.type-chip').forEach(c => c.classList.remove('active'));
        el.classList.add('active');
        applyMenuFilters();
    }

    function applyMenuFilters() {
        const q = (document.getElementById('menu-search').value || '').trim().toLowerCase();
        document.querySelectorAll('.menu-category').forEach(cat => {
            if (catFilter !== 'all' && cat.dataset.cat !== catFilter) {
                cat.style.display = 'none';
                return;
            }
            let visible = 0;
            cat.querySelectorAll('.menu-item-card').forEach(card => {
                const ok = (typeFilter === 'all' || card.dataset.type === typeFilter) &&
                    (!q || card.dataset.name.includes(q));
                card.style.display = ok ? '' : 'none';
                if (ok) visible++;
            });
            cat.style.display = visible ? '' : 'none';
        });
    }

    // ---- Same-restaurant rule ----
    const CURRENT_RESTAURANT_ID = {{$restaurant->id}};
    (function purgeOtherRestaurantItems() {
        // cart from another restaurant must not silently mix
        if (cart.length && cart.some(i => Number(i.restaurant_id) !== CURRENT_RESTAURANT_ID)) {
            if (confirm('Your cart has items from another restaurant. Clear it and start a new order here?')) {
                cart = [];
                saveCart();
            }
        }
    })();

    function addToCart(id, restaurant_id, name, price, imgUrl, btnElement) {
        if (cart.length && Number(cart[0].restaurant_id) !== Number(restaurant_id)) {
            if (!confirm('Your cart has items from another restaurant. Clear it and add this item?')) return;
            cart = [];
            saveCart();
            renderCart();
        }
        let itemIndex = cart.findIndex(item => item.id === id);
        let currentQty = 1;

        if (itemIndex > -1) {
            cart[itemIndex].qty += 1;
            currentQty = cart[itemIndex].qty;
        } else {
            cart.push({
                id: id,
                restaurant_id: restaurant_id,
                name: name,
                price: price,
                imgUrl: imgUrl,
                qty: 1
            });
        }

        // visually update button
        if (btnElement) {
            const parent = btnElement.parentElement;
            btnElement.style.display = 'none';

            const ctrl = document.createElement('div');
            ctrl.className = 'qty-ctrl';
            ctrl.style.display = 'flex';
            ctrl.innerHTML = `
                <button class="qty-btn minus" onclick="updateQty(${id}, -1, this)">-</button>
                <span class="qty-num">${currentQty}</span>
                <button class="qty-btn" onclick="updateQty(${id}, 1, this)">+</button>
            `;
            parent.appendChild(ctrl);
        }

        saveCart();
        renderCart();
    }

    function updateQty(id, change, element) {
        let itemIndex = cart.findIndex(item => item.id === id);
        if (itemIndex > -1) {
            cart[itemIndex].qty += change;

            // Update the UI control
            if (element) {
                const numSpan = element.parentElement.querySelector('.qty-num');
                if (numSpan) numSpan.innerText = cart[itemIndex].qty;
            }

            // Sync with main menu list if visible
            const container = document.querySelector(`.item-bottom[data-item-id="${id}"]`);
            if (container) {
                const numSpan = container.querySelector('.qty-num');
                if (numSpan) numSpan.innerText = cart[itemIndex].qty;
            }

            if (cart[itemIndex].qty <= 0) {
                cart.splice(itemIndex, 1);
                // Reset UI control
                if (element && element.parentElement.parentElement.classList.contains('item-bottom')) {
                    const parent = element.parentElement.parentElement;
                    element.parentElement.remove(); // remove qty ctrl
                    const btnAdd = parent.querySelector('.btn-add');
                    if (btnAdd) btnAdd.style.display = 'block';
                }

                // Reset main menu UI
                if (container) {
                    const ctrl = container.querySelector('.qty-ctrl');
                    if (ctrl) ctrl.remove();
                    const btnAdd = container.querySelector('.btn-add');
                    if (btnAdd) btnAdd.style.display = 'block';
                }
            }
        }
        saveCart();
        renderCart();
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const emptyMsg = document.getElementById('empty-cart-msg');
        const countDisplay = document.getElementById('cart-count');
        const itemTotalDisplay = document.getElementById('item-total');
        const gstDisplay = document.getElementById('gst-amount');
        const grandTotalDisplay = document.getElementById('grand-total');
        const checkoutBtn = document.getElementById('checkout-btn');

        // Mobile elements
        const mCartBtn = document.getElementById('mobile-cart-btn');
        const mCartCount = document.getElementById('m-cart-count');
        const mCartTotal = document.getElementById('m-cart-total');

        // Remove existing items except empty message
        const items = container.querySelectorAll('.cart-item');
        items.forEach(i => i.remove());

        let itemTotal = 0;
        let totalItems = 0;

        if (cart.length === 0) {
            emptyMsg.style.display = 'block';
            checkoutBtn.style.pointerEvents = 'none';
            checkoutBtn.style.opacity = '0.5';

            if (mCartBtn) mCartBtn.style.display = 'none';
        } else {
            emptyMsg.style.display = 'none';
            checkoutBtn.style.pointerEvents = 'auto';
            checkoutBtn.style.opacity = '1';

            cart.forEach(item => {
                itemTotal += (item.price * item.qty);
                totalItems += item.qty;

                const itemDiv = document.createElement('div');
                itemDiv.className = 'cart-item';
                itemDiv.innerHTML = `
                    <div class="ci-info">
                        <img src="${item.imgUrl}" class="ci-img">
                        <div>
                            <div class="ci-name">${item.name}</div>
                            <div class="ci-price">₹${item.price}</div>
                        </div>
                    </div>
                    <div class="qty-ctrl" style="display: flex; height: fit-content;">
                        <button class="qty-btn minus" onclick="updateQty(${item.id}, -1, null)">-</button>
                        <span class="qty-num">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty(${item.id}, 1, null)">+</button>
                    </div>
                `;
                container.appendChild(itemDiv);
            });

            if (mCartBtn) {
                if (window.innerWidth <= 768) {
                    mCartBtn.style.display = 'flex';
                }
            }
        }

        countDisplay.innerText = `${totalItems} items`;
        itemTotalDisplay.innerText = `₹${itemTotal}`;

        if (mCartCount) mCartCount.innerText = totalItems;
        const mCartLabel = document.getElementById('m-cart-label');
        if (mCartLabel) mCartLabel.innerText = totalItems === 1 ? 'Item' : 'Items';

        if (totalItems > 0) {
            const platformFee = {{ \App\Models\SystemSetting::getSetting('platform_commission', 10) }};
            const gst = Math.round(itemTotal * 0.05);
            if (gstDisplay) gstDisplay.innerText = `₹${gst}`;
            const finalPrice = itemTotal + platformFee + gst;
            grandTotalDisplay.innerText = `₹${finalPrice}`;
            if (mCartTotal) mCartTotal.innerText = `₹${finalPrice}`;

            // save final price to localStorage for checkout
            localStorage.setItem('quickdines_cart_totals', JSON.stringify({
                itemTotal,
                platformFee,
                gst,
                finalPrice
            }));
        } else {
            if (gstDisplay) gstDisplay.innerText = `₹0`;
            grandTotalDisplay.innerText = `₹0`;
            localStorage.removeItem('quickdines_cart_totals');
        }
    }

    // Add window resize listener to handle display of mobile cart button
    window.addEventListener('resize', function() {
        const mCartBtn = document.getElementById('mobile-cart-btn');
        if (mCartBtn) {
            if (window.innerWidth <= 768 && cart.length > 0) {
                mCartBtn.style.display = 'flex';
            } else {
                mCartBtn.style.display = 'none';
            }
        }
    });

    function switchTab(tab, el) {
        document.querySelectorAll('.rest-tab').forEach(t => t.classList.remove('active'));
        el.classList.add('active');

        if (tab === 'menu') {
            document.getElementById('menu-content').style.display = 'block';
            document.getElementById('reviews-content').style.display = 'none';
        } else if (tab === 'reviews') {
            document.getElementById('menu-content').style.display = 'none';
            document.getElementById('reviews-content').style.display = 'block';
        }
    }
</script>
