@extends('layouts.frontend')
@section('content')

<style>
    /* Mobile-specific welcome page styles */
    @media (max-width: 768px) {
        .desktop-only {
            display: none !important;
        }

        .mobile-only {
            display: block !important;
        }

        .mobile-flex {
            display: flex !important;
        }

        .mobile-grid {
            display: grid !important;
        }

        .hero-content {
            padding: 0;
        }

        .hero-wrapper {
            padding-bottom: 0 !important;
        }

        .bus-banner-bg {
            background: #00175b !important;
        }

        .hero {
            position: relative;
            display: block !important;
            padding-top: 60px;
            /* space for navbar */
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .mobile-cards-row {
            display: flex !important;
            flex-direction: column;
            gap: 12px;
            margin-top: 15px;
            padding-bottom: 10px;
            align-items: stretch;
        }

        .mobile-cards-row>.floating-card {
            flex: 1 !important;
            max-width: none !important;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .mobile-features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: clamp(6px, 2vw, 10px);
            margin-top: clamp(12px, 4vw, 20px);
        }

        .mobile-feature-item {
            background: #111827;
            border: 1px solid #1f2937;
            border-radius: 12px;
            padding: clamp(8px, 2.5vw, 12px);
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: clamp(6px, 2vw, 10px);
        }

        .mobile-feature-icon {
            font-size: 16px;
        }

        .mobile-feature-text h4 {
            color: #fff;
            font-size: clamp(10px, 3.5vw, 12px);
            font-weight: 700;
            margin: 0 0 2px 0;
        }

        .mobile-feature-text p {
            color: #94a3b8;
            font-size: 10px;
            margin: 0;
        }

        .mobile-buttons-stack {
            display: flex;
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .mobile-btn-primary {
            background: #2e2a5d;
            color: white;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .mobile-btn-secondary {
            background: #0f172a;
            border: 1px solid #1e293b;
            color: white;
            text-align: center;
            padding: 14px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
        }

        .mobile-stats-card {
            background: white;
            border-radius: 16px;
            padding: 15px 10px;
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .mobile-stat-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 6px;
            flex: 1;
        }

        .mobile-stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .mobile-stat-info h3 {
            font-size: 13px;
            font-weight: 800;
            color: #0f172a;
            margin: 0;
        }

        .mobile-stat-info p {
            font-size: 9px;
            color: #64748b;
            margin: 0;
            line-height: 1.2;
        }

        /* Horizontal scrolls on mobile */
        .categories-carousel-container {
            display: grid !important;
            grid-template-rows: repeat(2, 1fr) !important;
            grid-auto-flow: column !important;
            grid-auto-columns: max-content;
            gap: 20px 25px !important;
            padding-bottom: 20px !important;
        }

        .category-circle img {
            width: clamp(55px, 16vw, 70px) !important;
            height: clamp(55px, 16vw, 70px) !important;
        }

        .category-circle span {
            font-size: clamp(10px, 3.5vw, 12px) !important;
        }

        .restaurants-grid {
            display: flex !important;
            overflow-x: auto;
            scrollbar-width: none;
            gap: 15px;
            padding-bottom: 15px;
        }

        .restaurants-grid>a {
            flex: 0 0 240px;
        }

        .nav-btn,
        .scroll-arrow,
        .nav-btn-vid {
            display: none !important;
        }

        .section-header-flex h2 {
            font-size: 18px !important;
        }

        /* Premium Promo Banner Responsive */
        .promo-banner-section {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 100%) !important;
            position: relative !important;
            overflow: hidden !important;
            padding: 0 !important;
            margin-top: 20px !important;
            width: 100% !important;
            max-width: 100vw !important;
        }

        .promo-banner-section::before {
            content: '';
            position: absolute;
            top: -50px;
            left: -50px;
            width: 250px;
            height: 250px;
            background: radial-gradient(circle, rgba(139, 92, 246, 0.25) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
        }

        .promo-banner {
            flex-direction: column !important;
            align-items: center !important;
            justify-content: center !important;
            text-align: center !important;
            padding: 50px 20px 0 20px !important;
            gap: 30px !important;
            background: transparent !important;
            position: relative !important;
            z-index: 1 !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .promo-content {
            flex: none !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }

        .promo-content h2 {
            font-size: 32px !important;
            font-weight: 800 !important;
            line-height: 1.3 !important;
            margin-bottom: 15px !important;
            background: linear-gradient(to right, #ffffff, #c7d2fe);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            letter-spacing: -0.5px;
            word-wrap: break-word !important;
            white-space: normal !important;
            width: 100% !important;
        }

        .promo-content h2 br {
            display: none !important;
        }

        .promo-content p {
            font-size: 15px !important;
            color: #94a3b8 !important;
            margin-bottom: 30px !important;
            line-height: 1.6 !important;
            padding: 0 10px !important;
            width: 100% !important;
            box-sizing: border-box !important;
        }

        .promo-content .btn-track {
            margin: 0 auto !important;
            display: inline-flex !important;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
            padding: 16px 32px !important;
            border-radius: 30px !important;
            font-size: 15px !important;
            font-weight: 700 !important;
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.35) !important;
            color: white !important;
            align-items: center !important;
            justify-content: center !important;
            gap: 8px !important;
            border: none !important;
            width: 80% !important;
            max-width: 300px !important;
        }

        .promo-graphics {
            flex-direction: column !important;
            padding-left: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            justify-content: center !important;
            align-items: center !important;
            margin-top: 10px !important;
            box-sizing: border-box !important;
        }

        .glowing-route {
            display: none !important;
        }

        #inlinePhoneMockup {
            margin: 0 auto !important;
            width: 280px !important;
            max-width: 100% !important;
            border-width: 10px !important;
            border-color: #0f172a !important;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.6), 0 0 60px rgba(139, 92, 246, 0.2) !important;
            transform: translateY(20px);
            background: linear-gradient(to bottom, #1e293b, #0f172a) !important;
            box-sizing: border-box !important;
        }
    }

    @media (min-width: 769px) {
        .mobile-only {
            display: none !important;
        }

        .bus-banner-bg {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        }

        .bus-spacer-bg {
            background: transparent !important;
        }

        /* No background needed if banner is green, or we can make spacer same color */
    }
</style>

<!-- Dark Section -->
<div class="hero-wrapper" style="background: var(--bg-dark); padding-bottom: 40px; position: relative;">
    <div class="container">
        <!-- Hero Section -->
        <div class="hero">
            <!-- Left Content -->
            <div class="hero-content">
                <div class="badge desktop-only">
                    <i class="fa-solid fa-link"></i> Smart Food Ordering For Bus Travelers
                </div>
                <h1 class="hero-title desktop-only">Delicious Food,<br>Anytime,<br>Anywhere</h1>
                <p class="hero-subtitle desktop-only" style="color: #cbd5e1;">Order from nearby restaurants<br>while traveling.</p>

                <div class="features-grid desktop-only">
                    <div class="feature-item">
                        <i class="fa-solid fa-location-dot feature-icon" style="color: #fbbf24;"></i>
                        <div class="feature-text">
                            <h4>Live Bus Tracking</h4>
                            <p>Real-time updates</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-utensils feature-icon" style="color: #fbbf24;"></i>
                        <div class="feature-text">
                            <h4>Nearby Restaurants</h4>
                            <p>Wide range of choices</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-motorcycle feature-icon" style="color: var(--primary);"></i>
                        <div class="feature-text">
                            <h4>Fast Delivery</h4>
                            <p>On-time at your seat</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="fa-solid fa-shield-halved feature-icon" style="color: #fbbf24;"></i>
                        <div class="feature-text">
                            <h4>Secure Payments</h4>
                            <p>Safe & reliable</p>
                        </div>
                    </div>
                </div>

                <div class="hero-actions desktop-only">
                    <a href="#" class="btn-primary">Explore Restaurants <i class="fa-solid fa-arrow-right"></i></a>
                    <a href="{{ route('track-bus') }}" class="btn-outline"><i class="fa-solid fa-location-dot"></i> Track Restaurant</a>
                </div>

                <!-- Mobile Specific Content Flow -->
                <div class="mobile-only" style="width: calc(100% + 30px); margin: 0 -15px; padding: 0;">
                    <img src="{{ asset('images/mobilehero.jpg') }}" style="width: 100%; height: auto; display: block;" alt="Welcome to QuickDines">
                </div>
                <!-- End Mobile Specific Content Flow -->
            </div>

            <!-- Right Image & Cards (Desktop Only) -->
            <div class="hero-image-wrapper desktop-only">

            </div>
        </div>
    </div>
</div>


<!-- Scanned Bus Banner -->
@if(isset($scannedBus) && $scannedBus)
<div class="bus-banner-bg" style="padding: 8px 15px; text-align: center; color: white; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px;">
    <div style="font-size: 16px; font-weight: 600;">
        <i class="fa-solid fa-bus-simple" style="margin-right: 8px;"></i> You are currently ordering for <span style="font-weight: 800; font-size: 18px;">Bus #{{ $scannedBus->bus_number }}</span>
        <div style="font-size: 12px; opacity: 0.9; margin-top: 4px;">Only restaurants available on your route are shown.</div>
    </div>
</div>
<div class="desktop-only" style="height: 40px; background: transparent;"></div>
@endif

<!-- Stats Section -->
<div class="stats-section desktop-only">
    <div class="container" style="position: relative;">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon" style="color: #8b5cf6;"><i class="fa-solid fa-store"></i></div>
                <div class="stat-info">
                    <h3>1000+</h3>
                    <p>Partner Restaurants</p>
                </div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-icon" style="color: var(--primary);"><i class="fa-regular fa-user"></i></div>
                <div class="stat-info">
                    <h3>50K+</h3>
                    <p>Happy Travelers</p>
                </div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-icon" style="color: #10b981;"><i class="fa-solid fa-bus"></i></div>
                <div class="stat-info">
                    <h3>200+</h3>
                    <p>Buses Onboard</p>
                </div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-icon" style="color: #3b82f6;"><i class="fa-solid fa-clipboard-check"></i></div>
                <div class="stat-info">
                    <h3>200K+</h3>
                    <p>Orders Delivered</p>
                </div>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <div class="stat-icon" style="color: #f43f5e;"><i class="fa-solid fa-star"></i></div>
                <div class="stat-info">
                    <h3>4.7</h3>
                    <p>Average Rating</p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Search Section -->
<!-- <div class="search-section-wrapper" style="background: white; padding: 30px 0 10px 0;">
    <div class="container">
        <form action="{{ route('search') }}" method="GET" style="display: flex; gap: 10px; max-width: 800px; margin: 0 auto; background: #fff; padding: 6px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid #f1f5f9;">
            <div style="flex: 1; display: flex; align-items: center; padding-left: 15px;">
                <i class="fa-solid fa-magnifying-glass" style="color: var(--primary); font-size: 18px;"></i>
                <input type="text" name="query" placeholder="Search for restaurants, cuisines, or dishes..." style="width: 100%; border: none; padding: 12px 15px; outline: none; font-size: 15px; font-weight: 500; color: #02060c; background: transparent;" required>
            </div>
            <button type="submit" style="background: var(--primary); color: white; border: none; padding: 0 25px; border-radius: 8px; font-weight: 700; cursor: pointer; transition: 0.3s;" onmouseover="this.style.background='#1d4ed8'" onmouseout="this.style.background='var(--primary)'">Search</button>
        </form>
    </div>
</div> -->




{{-- QR ordering: Restaurant Details -> Search -> Filters -> Categories -> Menu -> Cart --}}
@if(isset($scannedBus) && $scannedBus)
@if($restaurant)
@if(isset($busRestaurants) && $busRestaurants->count() > 1)
<div class="container" style="padding-top:10px;">
    <a href="{{ route('qr.change-restaurant') }}" style="display:inline-block;font-size:13px;font-weight:700;color:#2563eb;text-decoration:none;">
        <i class="fa-solid fa-arrow-left"></i> Change Restaurant
    </a>
</div>
@endif
@include('frontend.partials.restaurant-order')
@else
<div style="max-width:480px;margin:40px auto;padding:24px;text-align:center;">
    <div style="font-size:48px;">🍽️</div>
    <h2 style="margin:12px 0 8px;">No restaurants available</h2>
    <p style="color:#64748b;">No restaurants are available for bus {{ $scannedBus->bus_number ?? '' }} right now.</p>
</div>
@endif
@endif





<!-- Swiggy Filters Section Removed as requested -->

<!-- Popular Restaurants Section Removed as requested -->






<!-- Light Content Area -->
<div class="light-content">
    <div class="container">
        <!-- How it works -->
        <div class="section-header">
            <h2>How It Works</h2>
        </div>

        <div class="steps-wrapper">
            <div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-qrcode"></i></div>
                <div class="step-content">
                    <h3>1. Scan QR Code</h3>
                    <p>Scan the QR code available in your bus</p>
                </div>
            </div>
            <div class="step-connector"></div>

            <div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-location-dot"></i></div>
                <div class="step-content">
                    <h3>2. Choose Restaurant</h3>
                    <p>View nearby restaurants and check menus</p>
                </div>
            </div>
            <div class="step-connector"></div>

            <div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="step-content">
                    <h3>3. Place Your Order</h3>
                    <p>Add items to cart and place your order</p>
                </div>
            </div>
            <div class="step-connector"></div>

            <div class="step-card">
                <div class="step-icon"><i class="fa-solid fa-motorcycle"></i></div>
                <div class="step-content">
                    <h3>4. Enjoy Your Meal</h3>
                    <p>Fresh food delivered to your seat</p>
                </div>
            </div>
        </div>

    </div> <!-- End container -->
</div> <!-- End light-content -->


<!-- Exclusive Offers Section -->
<div class="offers-section">
    <div class="container">
        <div class="offers-header">
            <h2>Exclusive Offers For You</h2>
            <a href="#" class="view-all" style="color: #2563eb;">View All Offers <i class="fa-solid fa-arrow-right"></i></a>
        </div>

        <div class="offers-grid">
            <!-- Card 1 -->
            <div class="offer-card bg-orange">
                <div class="offer-text-top">
                    <div class="offer-title text-orange">FLAT</div>
                    <div class="offer-value text-orange">20% OFF</div>
                    <div class="offer-desc">On Your First Order</div>
                    <div class="offer-code text-orange">Use Code: FIRST20</div>
                </div>
                <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?q=80&w=200&auto=format&fit=crop" alt="Noodles" class="offer-img" style="border-radius: 50%;">
            </div>

            <!-- Card 2 -->
            <div class="offer-card bg-green">
                <div class="offer-text-top">
                    <div class="offer-title text-green">FREE</div>
                    <div class="offer-value text-green">DELIVERY</div>
                    <div class="offer-desc">On Orders Above ₹199</div>
                </div>
                <img src="images/delivery.png" alt="Delivery" class="offer-img" style="border-radius: 50%;">
            </div>

            <!-- Card 3 -->
            <div class="offer-card bg-blue">
                <div class="offer-text-top">
                    <div class="offer-value text-blue" style="font-size: 20px;">15% <span style="font-size: 14px; font-weight: 700;">CASHBACK</span></div>
                    <div class="offer-desc">On Online Payments</div>
                    <div class="offer-code text-blue" style="margin-top: 15px;">Use Code: PAY15</div>
                </div>
                <img src="images/cashback.png" alt="Card" class="offer-img" style="border-radius: 12px; right: 0;">
            </div>

            <!-- Card 4 -->
            <div class="offer-card bg-purple">
                <div class="offer-text-top">
                    <div class="offer-title text-purple" style="font-size: 16px;">MEGA<br>MEAL DEALS</div>
                    <div class="offer-desc" style="margin-top: 8px;">Best Combos at<br>Best Prices</div>
                </div>
                <img src="https://images.unsplash.com/photo-1547496502-affa22d38842?q=80&w=200&auto=format&fit=crop" alt="Meal" class="offer-img" style="border-radius: 12px; right: -5px;">
            </div>
        </div>
    </div>
</div>




<!-- Video Collections Section -->
<div class="collections-section-wrapper" style="background: white; padding: 50px 0;">
    <div class="container" style="position: relative;">
        <div class="section-header-flex" style="margin-bottom: 5px;">
            <h2 style="font-size: 32px; font-weight: 500; color: #1c1c1c; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Collections</h2>
        </div>
        <div style="font-size: 16px; color: #363636; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
            <p style="margin: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">Explore curated lists of top restaurants, cafes, pubs, and bars in Hyderabad, based on trends</p>
            <a href="#" style="color: #ef4f5f; text-decoration: none; font-weight: 400; font-size: 15px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">All collections in Hyderabad <i class="fa-solid fa-caret-right" style="font-size: 12px; margin-left: 2px;"></i></a>
        </div>

        <!-- Carousel Navigation Arrows -->
        <button class="nav-btn-vid" onclick="scrollVidCarousel(-1)" style="position: absolute; left: -20px; top: 55%; transform: translateY(-50%); z-index: 10; width: 40px; height: 40px; border-radius: 50%; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; color: #02060c; font-size: 14px;"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="nav-btn-vid" onclick="scrollVidCarousel(1)" style="position: absolute; right: -20px; top: 55%; transform: translateY(-50%); z-index: 10; width: 40px; height: 40px; border-radius: 50%; background: white; border: 1px solid #e2e8f0; box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; display: flex; align-items: center; justify-content: center; color: #02060c; font-size: 14px;"><i class="fa-solid fa-chevron-right"></i></button>

        <!-- Video Cards Container -->
        <div class="vid-carousel-container" id="vid-carousel" style="display: flex; gap: 12px; overflow-x: auto; scrollbar-width: none; scroll-behavior: smooth; padding-bottom: 15px;">

            <!-- Card 1 -->
            <div class="collection-card" style="position: relative; flex: 0 0 clamp(220px, 75vw, 265px); height: clamp(260px, 80vw, 320px); border-radius: 8px; overflow: hidden; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1514933651103-005eec06c04b?q=80&w=400&auto=format&fit=crop">
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4" type="video/mp4">
                </video>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 15px; pointer-events: none;">
                    <div style="color: white; font-size: 18px; font-weight: 500; font-family: 'Segoe UI', sans-serif; letter-spacing: 0.2px; margin-bottom: 5px;">Best pubs & bars</div>
                    <div style="color: white; font-size: 14px; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 5px;">35 Places <i class="fa-solid fa-caret-right" style="font-size: 11px;"></i></div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="collection-card" style="position: relative; flex: 0 0 clamp(220px, 75vw, 265px); height: clamp(260px, 80vw, 320px); border-radius: 8px; overflow: hidden; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=400&auto=format&fit=crop">
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4" type="video/mp4">
                </video>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 15px; pointer-events: none;">
                    <div style="color: white; font-size: 18px; font-weight: 500; font-family: 'Segoe UI', sans-serif; letter-spacing: 0.2px; margin-bottom: 5px;">Iconic restaurants</div>
                    <div style="color: white; font-size: 14px; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 5px;">15 Places <i class="fa-solid fa-caret-right" style="font-size: 11px;"></i></div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="collection-card" style="position: relative; flex: 0 0 clamp(220px, 75vw, 265px); height: clamp(260px, 80vw, 320px); border-radius: 8px; overflow: hidden; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1560624052-449f5ddf0c31?q=80&w=400&auto=format&fit=crop">
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4" type="video/mp4">
                </video>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 15px; pointer-events: none;">
                    <div style="color: white; font-size: 18px; font-weight: 500; font-family: 'Segoe UI', sans-serif; letter-spacing: 0.2px; margin-bottom: 5px;">Finest microbreweries</div>
                    <div style="color: white; font-size: 14px; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 5px;">13 Places <i class="fa-solid fa-caret-right" style="font-size: 11px;"></i></div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="collection-card" style="position: relative; flex: 0 0 clamp(220px, 75vw, 265px); height: clamp(260px, 80vw, 320px); border-radius: 8px; overflow: hidden; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1585937421612-70a008356fbe?q=80&w=400&auto=format&fit=crop">
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4" type="video/mp4">
                </video>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 15px; pointer-events: none;">
                    <div style="color: white; font-size: 18px; font-weight: 500; font-family: 'Segoe UI', sans-serif; letter-spacing: 0.2px; margin-bottom: 5px;">Regional flavours</div>
                    <div style="color: white; font-size: 14px; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 5px;">29 Places <i class="fa-solid fa-caret-right" style="font-size: 11px;"></i></div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="collection-card" style="position: relative; flex: 0 0 clamp(220px, 75vw, 265px); height: clamp(260px, 80vw, 320px); border-radius: 8px; overflow: hidden; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1551024601-bec78aea704b?q=80&w=400&auto=format&fit=crop">
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4" type="video/mp4">
                </video>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 15px; pointer-events: none;">
                    <div style="color: white; font-size: 18px; font-weight: 500; font-family: 'Segoe UI', sans-serif; letter-spacing: 0.2px; margin-bottom: 5px;">Trending Sweets</div>
                    <div style="color: white; font-size: 14px; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 5px;">18 Places <i class="fa-solid fa-caret-right" style="font-size: 11px;"></i></div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="collection-card" style="position: relative; flex: 0 0 clamp(220px, 75vw, 265px); height: clamp(260px, 80vw, 320px); border-radius: 8px; overflow: hidden; cursor: pointer; box-shadow: 0 4px 10px rgba(0,0,0,0.05); transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                <video autoplay loop muted playsinline style="width: 100%; height: 100%; object-fit: cover;" poster="https://images.unsplash.com/photo-1552566626-52f8b828add9?q=80&w=400&auto=format&fit=crop">
                    <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4" type="video/mp4">
                </video>
                <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 140px; background: linear-gradient(to top, rgba(0,0,0,0.95) 0%, rgba(0,0,0,0) 100%); display: flex; flex-direction: column; justify-content: flex-end; padding: 15px; pointer-events: none;">
                    <div style="color: white; font-size: 18px; font-weight: 500; font-family: 'Segoe UI', sans-serif; letter-spacing: 0.2px; margin-bottom: 5px;">Late Night Delivery</div>
                    <div style="color: white; font-size: 14px; font-family: 'Segoe UI', sans-serif; display: flex; align-items: center; gap: 5px;">42 Places <i class="fa-solid fa-caret-right" style="font-size: 11px;"></i></div>
                </div>
            </div>

        </div>
        <style>
            .vid-carousel-container::-webkit-scrollbar {
                display: none;
            }
        </style>
        <script>
            function scrollVidCarousel(dir) {
                const container = document.getElementById('vid-carousel');
                const scrollAmount = 277; // card width + gap
                container.scrollBy({
                    left: dir * scrollAmount,
                    behavior: 'smooth'
                });
            }
        </script>
    </div>
</div>

<!-- Container re-open for bottom content if any -->
<!-- (Closed previous section, no need for container here as we start full width wrappers) -->


@push('styles')
<style>
    .swiggy-card:hover {
        transform: scale(0.96) !important;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleFavorite(menuId, element) {
        const isAuthenticated = @json(auth()->check());
        if (!isAuthenticated) {
            window.location.href = "{{ route('customer.auth') }}";
            return;
        }

        fetch("{{ route('favorite.toggle') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    menu_id: menuId
                })
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
