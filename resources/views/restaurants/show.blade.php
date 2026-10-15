@extends('layouts.frontend')

@section('content')
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
        overflow: hidden;
    }
    .rest-cover {
        height: 180px;
        background-image: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80');
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
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        margin-top: -50px;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    }
    .rest-logo-large img {
        width: 80%;
        height: 80%;
        object-fit: contain;
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
        box-shadow: 0 4px 15px rgba(0,0,0,0.03);
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
    .cat-link:hover, .cat-link.active {
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
    }
    .menu-category-title .view-all {
        font-size: 12px;
        color: #5b21b6;
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
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
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
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .btn-add:hover {
        background: #f8f9fa;
        border-color: #5b21b6;
    }
    
    /* Cart Quantity Controller */
    .qty-ctrl {
        display: none; /* hidden by default, shown when added */
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
    .qty-btn.minus { color: #ef4444; }
    .qty-num { font-weight: 700; font-size: 13px; width: 16px; text-align: center; }

    /* Cart Sidebar */
    .cart-sidebar {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
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
    .ci-info { display: flex; gap: 10px; align-items: flex-start; }
    .ci-img { width: 40px; height: 40px; border-radius: 6px; object-fit: cover; }
    .ci-name { font-size: 13px; font-weight: 600; color: #000; margin-bottom: 2px; }
    .ci-price { font-size: 12px; color: #475569; }
    
    .cooking-instr {
        margin-bottom: 20px;
    }
    .cooking-instr p { font-size: 12px; color: #475569; margin-bottom: 8px; font-weight: 500; }
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
    .bill-details h4 { font-size: 14px; font-weight: 700; margin-bottom: 10px; color: #000; }
    .bd-row { display: flex; justify-content: space-between; font-size: 13px; color: #475569; margin-bottom: 8px; }
    .bd-total { display: flex; justify-content: space-between; font-size: 16px; font-weight: 800; color: #000; margin-top: 15px; border-top: 1px solid #e2e8f0; padding-top: 15px; }
    
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
    .btn-checkout:hover { background: #1d4ed8; }
    
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
    .est-delivery i { color: #16a34a; font-size: 20px; }
    .est-text p { font-size: 11px; color: #16a34a; font-weight: 600; margin-bottom: 2px;}
    .est-text span { font-size: 10px; color: #15803d; }
    .est-time { font-weight: 700; color: #16a34a; font-size: 13px; margin-left: auto; }

    /* Empty Cart State */
    .empty-cart {
        text-align: center;
        padding: 20px 0;
        color: #94a3b8;
    }
    .empty-cart i { font-size: 30px; margin-bottom: 10px; opacity: 0.5; }
    .empty-cart p { font-size: 13px; }


    /* Mobile Styles Moved to Bottom */
    @media (max-width: 768px) {
        .restaurant-detail-wrapper { padding: 0; background: #fff; }
        .container { padding: 0 15px; }
        
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
            width: 40px; height: 40px;
            border-radius: 50%;
            background: rgba(0,0,0,0.5);
            color: white;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px;
            text-decoration: none;
        }
        .rest-nav-right { display: flex; gap: 10px; }

        /* Overlapping Info Card */
        .rest-info-bar {
            background: white;
            margin: -60px 15px 0 15px;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            z-index: 5;
            position: relative;
        }
        .rest-logo-large { display: none; }
        
        .rest-main-details { width: 100%; text-align: center; }
        .rest-main-details h1 { justify-content: center; font-size: 20px; }
        .rest-main-details p { font-size: 13px; margin-bottom: 8px; }
        .rest-stats { justify-content: center; font-size: 11px; flex-wrap: wrap; gap: 12px; }
        
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
        .menu-sidebar::-webkit-scrollbar { display: none; }
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
            background: #5b21b6; /* Match mobile mockup purple */
            color: white;
        }
        .sidebar-promo { display: none; }
        
        /* Items Grid */
        .menu-items-grid { grid-template-columns: 1fr; gap: 20px; }
        .menu-item-card { 
            border: none; 
            box-shadow: none; 
            padding: 0; 
            border-bottom: 1px dashed #e2e8f0; 
            padding-bottom: 20px;
            border-radius: 0;
        }
        
        /* Hide Desktop Cart */
        .cart-sidebar { display: none !important; }

        /* Floating Mobile Cart */
        .mobile-floating-cart {
            display: none; /* toggled by JS */
            position: fixed;
            bottom: 80px; /* Above bottom nav */
            left: 15px;
            right: 15px;
            background: #5b21b6;
            color: white;
            padding: 14px 20px;
            border-radius: 12px;
            z-index: 100;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 10px 25px rgba(91,33,182,0.4);
            text-decoration: none;
        }
        .mfc-left { font-weight: 700; font-size: 15px; }
        .mfc-right { font-weight: 800; font-size: 16px; display: flex; align-items: center; gap: 8px; }
    }


    /* Mobile Styles Moved to Bottom */
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
        <div class="mfc-left">View Cart (<span id="m-cart-count">0</span>)</div>
        <div class="mfc-right"><span id="m-cart-total">₹0</span> <i class="fa-solid fa-chevron-right" style="font-size: 12px;"></i></div>
    </a>

    <div class="container" style="padding-top: 0;">
        <!-- Header -->
        <div class="rest-header-card">
            <div class="rest-cover">
                <!-- Inner circle logo for mobile mock style if needed, but we'll use info bar -->
            </div>
            <div class="rest-info-bar">
                <div class="rest-logo-large">
                    <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=200&auto=format&fit=crop" alt="Logo">
                </div>
                <div class="rest-main-details">
                    <h1>Spice Kitchen <div class="rest-rating"><i class="fa-solid fa-star"></i> 4.5</div></h1>
                    <p>North Indian, Chinese, Biryani</p>
                    <div class="rest-stats">
                        <span><i class="fa-regular fa-clock"></i> 20-30 mins</span>
                        <span><i class="fa-solid fa-location-dot"></i> 1.2 KM Away</span>
                    </div>
                    <div style="display: flex; gap: 15px; align-items: center; justify-content: center; margin-top: 15px;">
                        <span style="display:flex; align-items:center; gap:5px; font-size:11px; font-weight:700; color:#16a34a; background:#f0fdf4; padding:4px 8px; border-radius:4px; border:1px solid #bbf7d0;"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:12px;"> Pure Veg</span>
                        <span style="font-size:11px; font-weight:700; color:#475569; border:1px solid #e2e8f0; padding:4px 8px; border-radius:4px;"><i class="fa-solid fa-shield-halved" style="color:#0ea5e9;"></i> FSSAI</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Layout -->
        <div class="menu-layout">
            <!-- Left Sidebar -->
            <div>
                <div class="menu-sidebar">
                    <a href="#" class="cat-link active">Recommended</a>
                    <a href="#" class="cat-link">Biryani</a>
                    <a href="#" class="cat-link">North Indian</a>
                    <a href="#" class="cat-link">Chinese</a>
                    <a href="#" class="cat-link">Combos</a>
                    <a href="#" class="cat-link">Snacks</a>
                    <a href="#" class="cat-link">Beverages</a>
                    <a href="#" class="cat-link">Desserts</a>
                </div>
            </div>

            <!-- Center Menu -->
            <div>
                <!-- Mobile Tabs -->
                <div class="rest-tabs">
                    <a href="#" class="rest-tab active" onclick="switchTab('menu', this); return false;">Menu</a>
                    <a href="#" class="rest-tab" onclick="switchTab('reviews', this); return false;">Reviews</a>
                    <a href="#" class="rest-tab" onclick="switchTab('info', this); return false;">Info</a>
                </div>

                <!-- Menu Section -->
                <div id="menu-section">

                <!-- Category 1 -->
                <div class="menu-category">
                    <div class="menu-category-title">Recommended for You</div>
                    <div class="menu-items-grid">
                        <!-- Item 1 -->
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=200&q=80" class="item-img" alt="Paneer">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Paneer Butter Masala</div>
                                    <div class="item-price">₹190</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.5</div>
                                <div class="item-desc">Cottage cheese cooked in rich tomato & butter gravy.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Paneer Butter Masala', 190, this)">Add</button>
                                </div>
                            </div>
                        </div>
                        <!-- Item 2 -->
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1589301760014-d929f39ce9b0?w=200&q=80" class="item-img" alt="Biryani">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Veg Biryani</div>
                                    <div class="item-price">₹150</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.6</div>
                                <div class="item-desc">Aromatic basmati rice cooked with mixed vegetables.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Veg Biryani', 150, this)">Add</button>
                                </div>
                            </div>
                        </div>
                        <!-- Item 3 -->
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1626082927389-6cd097cdc6ec?w=200&q=80" class="item-img" alt="Chole">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Chole Bhature</div>
                                    <div class="item-price">₹120</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.3</div>
                                <div class="item-desc">Spicy chickpeas served with soft bhature.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Chole Bhature', 120, this)">Add</button>
                                </div>
                            </div>
                        </div>
                        <!-- Item 4 -->
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=200&q=80" class="item-img" alt="Rice">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Veg Fried Rice</div>
                                    <div class="item-price">₹130</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.4</div>
                                <div class="item-desc">Stir fried rice with mix vegetables & spices.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Veg Fried Rice', 130, this)">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category 2 -->
                <div class="menu-category">
                    <div class="menu-category-title">North Indian <a href="#" class="view-all">View All</a></div>
                    <div class="menu-items-grid">
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=200&q=80" class="item-img" alt="Dal">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Dal Tadka</div>
                                    <div class="item-price">₹120</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.5</div>
                                <div class="item-desc">Yellow lentils cooked with Indian spices.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Dal Tadka', 120, this)">Add</button>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1631452180519-c014fe946bc0?w=200&q=80" class="item-img" alt="Kadai">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Kadai Paneer</div>
                                    <div class="item-price">₹180</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.7</div>
                                <div class="item-desc">Cottage cheese cooked with bell peppers & kadai masala.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Kadai Paneer', 180, this)">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Category 3 -->
                <div class="menu-category">
                    <div class="menu-category-title">Chinese <a href="#" class="view-all">View All</a></div>
                    <div class="menu-items-grid">
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1585032226651-759b368d7246?w=200&q=80" class="item-img" alt="Manchurian">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Veg Manchurian</div>
                                    <div class="item-price">₹130</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.2</div>
                                <div class="item-desc">Fried vegetable balls in manchurian sauce.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Veg Manchurian', 130, this)">Add</button>
                                </div>
                            </div>
                        </div>
                        <div class="menu-item-card">
                            <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=200&q=80" class="item-img" alt="Noodles">
                            <div class="item-details">
                                <div class="item-header-row">
                                    <div class="item-title"><img src="https://upload.wikimedia.org/wikipedia/commons/b/b2/Veg_symbol.svg" style="width:14px;"> Hakka Noodles</div>
                                    <div class="item-price">₹120</div>
                                </div>
                                <div class="item-rating"><i class="fa-solid fa-star" style="color: #22c55e;"></i> 4.4</div>
                                <div class="item-desc">Stir fried noodles with vegetables.</div>
                                <div class="item-bottom">
                                    <button class="btn-add" onclick="addToCart('Hakka Noodles', 120, this)">Add</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </div> <!-- End Menu Section -->

                <!-- Reviews Section (Hidden by default) -->
                <div id="reviews-section" style="display: none; padding: 20px; background: white; border-radius: 12px; margin-top: 15px;">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 15px;">Customer Reviews</h3>
                    <div style="display: flex; gap: 15px; border-bottom: 1px solid #f1f5f9; padding-bottom: 15px; margin-bottom: 15px;">
                        <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #475569;">S</div>
                        <div>
                            <div style="font-weight: 600; font-size: 14px;">Sai Kumar</div>
                            <div style="font-size: 12px; color: #94a3b8; margin-bottom: 5px;">2 days ago</div>
                            <div style="color: #22c55e; font-size: 12px; margin-bottom: 5px;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></div>
                            <div style="font-size: 14px; color: #475569;">The food was really hot and fresh. Loved the biryani!</div>
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px;">
                        <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; color: #475569;">R</div>
                        <div>
                            <div style="font-weight: 600; font-size: 14px;">Rahul Sharma</div>
                            <div style="font-size: 12px; color: #94a3b8; margin-bottom: 5px;">1 week ago</div>
                            <div style="color: #22c55e; font-size: 12px; margin-bottom: 5px;"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                            <div style="font-size: 14px; color: #475569;">Amazing packaging and fast delivery right to my seat.</div>
                        </div>
                    </div>
                </div>

                <!-- Info Section (Hidden by default) -->
                <div id="info-section" style="display: none; padding: 20px; background: white; border-radius: 12px; margin-top: 15px;">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 15px;">Restaurant Information</h3>
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 13px; color: #94a3b8; margin-bottom: 2px;">FSSAI License</div>
                        <div style="font-size: 14px; font-weight: 500;">12345678901234</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 13px; color: #94a3b8; margin-bottom: 2px;">Address</div>
                        <div style="font-size: 14px; font-weight: 500;">Dhaba Express, Highway Road, Hyderabad 500032</div>
                    </div>
                    <div style="margin-bottom: 15px;">
                        <div style="font-size: 13px; color: #94a3b8; margin-bottom: 2px;">Opening Hours</div>
                        <div style="font-size: 14px; font-weight: 500;">10:00 AM - 11:30 PM (Mon-Sun)</div>
                    </div>
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
                            <span>Delivery Fee</span>
                            <span>₹20</span>
                        </div>
                        <div class="bd-row">
                            <span>Packaging Fee</span>
                            <span>₹10</span>
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
                        <div class="est-time">25-30 mins</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabId, element) {
        // Update active class on tabs
        document.querySelectorAll('.rest-tab').forEach(tab => tab.classList.remove('active'));
        element.classList.add('active');

        // Hide all sections
        document.getElementById('menu-section').style.display = 'none';
        document.getElementById('reviews-section').style.display = 'none';
        document.getElementById('info-section').style.display = 'none';

        // Show selected section
        document.getElementById(tabId + '-section').style.display = 'block';
    }

    // Simple JS logic to simulate cart behavior
    let cart = JSON.parse(localStorage.getItem('quickdines_cart')) || [];
    
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
            const response = await fetch('{{ route('checkout.cart') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    cart_data: JSON.stringify({ items: cart, totals: totals })
                })
            });
            const data = await response.json();
            if (!response.ok || !data.success) throw new Error(data.message || 'Unable to open checkout.');
            window.location.href = data.redirect_url;
        } catch (error) {
            alert(error.message || 'Unable to open checkout. Please try again.');
        }
    }
    
    function addToCart(name, price, btnElement) {
        const card = btnElement.closest('.menu-item-card');
        const imgUrl = card ? card.querySelector('.item-img').src : '';
        
        let itemIndex = cart.findIndex(item => item.name === name);
        if (itemIndex > -1) {
            cart[itemIndex].qty += 1;
        } else {
            cart.push({ name: name, price: price, qty: 1, imgUrl: imgUrl });
            
            // visually update button
            const parent = btnElement.parentElement;
            btnElement.style.display = 'none';
            
            const ctrl = document.createElement('div');
            ctrl.className = 'qty-ctrl';
            ctrl.style.display = 'flex';
            ctrl.innerHTML = `
                <button class="qty-btn minus" onclick="updateQty('${name}', -1, this)">-</button>
                <span class="qty-num">1</span>
                <button class="qty-btn" onclick="updateQty('${name}', 1, this)">+</button>
            `;
            parent.appendChild(ctrl);
        }
        
        renderCart();
    }
    
    function updateQty(name, change, element) {
        let itemIndex = cart.findIndex(item => item.name === name);
        if (itemIndex > -1) {
            cart[itemIndex].qty += change;
            
            // Update the UI control
            if (element) {
                const numSpan = element.parentElement.querySelector('.qty-num');
                numSpan.innerText = cart[itemIndex].qty;
            }
            
            if (cart[itemIndex].qty <= 0) {
                cart.splice(itemIndex, 1);
                // Reset UI control
                if (element) {
                    const parent = element.closest('.item-bottom');
                    const qtyCtrl = parent.querySelector('.qty-ctrl');
                    if (qtyCtrl) qtyCtrl.remove();
                    const addBtn = parent.querySelector('.btn-add');
                    if (addBtn) addBtn.style.display = 'block';
                }
            }
        }
        saveCart();
        renderCart();
        showToast("Cart updated successfully");
    }

    function showToast(message) {
        const toast = document.createElement('div');
        toast.innerText = message;
        toast.style.position = 'fixed';
        toast.style.bottom = '20px';
        toast.style.left = '50%';
        toast.style.transform = 'translateX(-50%)';
        toast.style.background = '#22c55e';
        toast.style.color = '#fff';
        toast.style.padding = '10px 20px';
        toast.style.borderRadius = '30px';
        toast.style.boxShadow = '0 4px 12px rgba(0,0,0,0.15)';
        toast.style.zIndex = '9999';
        toast.style.fontSize = '14px';
        toast.style.fontWeight = '600';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2500);
    }

    function renderCart() {
        const container = document.getElementById('cart-items-container');
        const emptyMsg = document.getElementById('empty-cart-msg');
        const countDisplay = document.getElementById('cart-count');
        const itemTotalDisplay = document.getElementById('item-total');
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
            
            if(mCartBtn) mCartBtn.style.display = 'none';
        } else {
            emptyMsg.style.display = 'none';
            checkoutBtn.style.pointerEvents = 'auto';
            checkoutBtn.style.opacity = '1';
            
            cart.forEach(item => {
                itemTotal += (item.price * item.qty);
                totalItems += item.qty;
                
                const div = document.createElement('div');
                div.className = 'cart-item';
                div.innerHTML = `
                    <div class="ci-info">
                        <div class="ci-name">${item.name}</div>
                        <div class="ci-price">₹${item.price}</div>
                    </div>
                    <div class="qty-ctrl">
                        <button class="qty-btn minus" onclick="updateQty('${item.name}', -1, null)">-</button>
                        <span class="qty-num">${item.qty}</span>
                        <button class="qty-btn" onclick="updateQty('${item.name}', 1, null)">+</button>
                    </div>
                `;
                container.appendChild(div);
            });
            
            // Calculate final totals
            const deliveryFee = 20;
            const platformFee = 10;
            const gst = Math.round(itemTotal * 0.05); // 5% GST
            const finalTotal = itemTotal + deliveryFee + platformFee + gst;
            
            itemTotalDisplay.innerText = '₹' + itemTotal;
            grandTotalDisplay.innerText = '₹' + finalTotal;
            
            // Save totals for checkout
            localStorage.setItem('quickdines_cart_totals', JSON.stringify({
                itemTotal: itemTotal,
                deliveryFee: deliveryFee,
                platformFee: platformFee,
                gst: gst,
                finalPrice: finalTotal
            }));
            
            if(mCartBtn) mCartBtn.style.display = 'flex';
            
            if(mCartBtn) {
                // Ensure it displays as flex when shown
                if(window.innerWidth <= 768) {
                    mCartBtn.style.display = 'flex';
                }
            }
        }
        
        countDisplay.innerText = `${totalItems} items`;
        itemTotalDisplay.innerText = `₹${itemTotal}`;
        
        if(mCartCount) mCartCount.innerText = totalItems;
        
        if(totalItems > 0) {
            const finalPrice = itemTotal + 20 + 10;
            grandTotalDisplay.innerText = `₹${finalPrice}`;
            if(mCartTotal) mCartTotal.innerText = `₹${finalPrice}`;
        } else {
            grandTotalDisplay.innerText = `₹0`;
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
</script>
@endsection
