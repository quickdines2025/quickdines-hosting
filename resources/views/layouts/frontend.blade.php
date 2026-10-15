<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickDines - Food on Your Journey</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* CSS reset & variables */
        :root {
            --bg-dark: #0a0e17;
            --bg-darker: #05080f;
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --text-light: #fafcfb;
            --text-muted: #94a3b8;
            --card-bg: rgba(255, 255, 255, 0.03);
            --card-border: rgba(255, 255, 255, 0.08);
            --bg-light: #f8f9fa;
            --text-dark: #04112e;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        html {
            overflow-x: hidden;
            max-width: 100vw;
        }
        body {
            background-color: var(--bg-dark);
            color: var(--text-light);
            overflow-x: hidden;
            max-width: 100vw;
            width: 100%;
            box-sizing: border-box;
        }

        /* Container */
        .container {
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
            padding: 0 40px;
            box-sizing: border-box;
        }

        /* Navbar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            
        }
        .logo i {
            font-size: 32px;
            color: var(--primary);
        }
        .logo-text {
            font-size: 24px;
            font-weight: 700;
            color: #202a5d;
        }
        .logo-text span {
            color: #202a5d;
        }
        .logo-sub {
            font-size: 12px;
            color: var(--text-muted);
            margin-top: 2px;
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }
        .nav-links a {
            color: var(--text-light);
            text-decoration: none;
            font-weight: 500;
            font-size: 15px;
            transition: color 0.3s;
        }
        .nav-links a:hover, .nav-links a.active {
            color: var(--primary);
        }
        .nav-links a.active {
            border-bottom: 2px solid var(--primary);
            padding-bottom: 5px;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .theme-toggle {
            display: flex;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 20px;
            padding: 5px;
            gap: 5px;
        }
        .theme-toggle button {
            background: transparent;
            border: none;
            color: var(--text-muted);
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            transition: 0.3s;
        }
        .theme-toggle button.active {
            background: var(--primary);
            color: white;
        }

        .btn-primary {
            background: #202a5d;
            color: #fafcfb;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-primary:hover {
            background: #151c40;
        }
        .btn-outline {
            background: transparent;
            color: #fafcfb;
            border: 1px solid rgba(250, 252, 251, 0.3);
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-outline:hover {
            background: rgba(250, 252, 251, 0.1);
        }

        /* Hero Section */
        .hero {
            display: grid;
            grid-template-columns: 0.85fr 1.15fr; /* Give more space to the image */
            gap: 30px;
            padding: 20px 0;
            align-items: center;
        }

        .hero-content {
            max-width: 550px;
        }

        .badge {
            background: rgba(139, 92, 246, 0.15); /* Purple tint */
            color: #b794f4;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 20px;
            border: 1px solid rgba(139, 92, 246, 0.3);
        }

        .hero-title {
            font-size: 42px;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 16px;
        }
        .hero-title span {
            color: white;
        }

        .hero-subtitle {
            font-size: 16px;
            color: var(--text-muted);
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 30px;
        }
        .feature-item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            padding: 12px;
            border-radius: 10px;
        }
        .feature-icon {
            color: var(--primary);
            font-size: 18px;
        }
        .feature-text h4 {
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 2px;
        }
        .feature-text p {
            font-size: 12px;
            color: var(--text-muted);
        }

        .hero-actions {
            display: flex;
            gap: 16px;
        }

        /* Hero Right (Image & Cards) */
        .hero-image-wrapper {
            position: relative;
            height: 480px;
            border-radius: 24px;
            overflow: hidden;
            background-image: url('{{ asset("images/hero-bg.jpg") }}');
            background-size: cover;
            background-position: center center;
        }
        .hero-image-wrapper::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, var(--bg-dark) 0%, transparent 40%);
        }

        /* Floating Cards Container */
        .floating-cards-container {
            position: absolute;
            top: 50%;
            right: 20px;
            transform: translateY(-50%) scale(0.85);
            transform-origin: right center;
            display: flex;
            flex-direction: column;
            gap: 16px;
            z-index: 10;
        }

        .cards-group {
            display: flex;
            flex-direction: column;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }

        /* Floating Cards */
        .floating-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 16px;
            color: var(--text-dark);
        }
        .card-dark {
            background: rgba(45, 27, 105, 0.95); /* Match reference purple */
            color: white;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .live-bus-card {
            width: 260px;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .status-badge {
            background: #d1fae5;
            color: #059669;
            padding: 3px 6px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 600;
        }
        .bus-info {
            display: flex;
            gap: 12px;
            align-items: center;
            margin-bottom: 12px;
        }
        .bus-icon {
            font-size: 20px;
            color: var(--text-dark);
        }
        .bus-details h3 {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 2px;
        }
        .bus-details p {
            font-size: 12px;
            color: #64748b;
        }
        .map-visual {
            height: 50px;
            background: #f1f5f9;
            border-radius: 8px;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
        }
        .map-visual::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 15px;
            right: 15px;
            height: 2px;
            background: var(--primary);
            z-index: 1;
        }
        .map-point {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            z-index: 2;
        }
        .map-bus {
            position: absolute;
            left: 60%;
            top: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 4px;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 3;
            color: var(--primary);
            font-size: 12px;
        }

        .next-stop-card {
            width: 260px;
            padding: 12px 16px;
        }
        .next-stop-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .stop-info {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        .stop-info i {
            font-size: 18px;
            opacity: 0.8;
        }
        .stop-details p {
            font-size: 11px;
            opacity: 0.8;
            margin-bottom: 2px;
        }
        .stop-details h4 {
            font-size: 15px;
            font-weight: 600;
        }
        .time-left {
            font-size: 15px;
            font-weight: 700;
        }

        .order-card {
            width: 260px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .order-item {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }
        .order-item img {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
        }
        .order-progress {
            height: 5px;
            background: #f1f5f9;
            border-radius: 3px;
            margin-bottom: 8px;
            overflow: hidden;
        }
        .progress-bar {
            height: 100%;
            width: 70%;
            background: #059669;
            border-radius: 3px;
        }
        .order-status {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            font-weight: 600;
            color: var(--primary);
        }

        /* Stats Section */
        .stats-section {
            background: #fafcfb;
            padding: 15px 0;
            margin: -40px 0 60px 0;
            position: relative;
            z-index: 20;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            border-top: 1px solid rgba(0,0,0,0.05);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }
        .stats-grid {
            display: flex;
            justify-content: space-around;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .stat-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .stat-icon {
            width: 48px;
            height: 48px;
            background: rgba(32,42,93,0.05);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: var(--primary);
        }
        .stat-info h3 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 2px;
            color: #202a5d;
        }
        .stat-info p {
            font-size: 14px;
            color: #202a5d;
            opacity: 0.8;
        }
        .stat-divider {
            width: 1px;
            height: 40px;
            background: rgba(32,42,93,0.1);
        }

        /* Light Content Area */
        .light-content {
            background: var(--bg-light);
            color: var(--text-dark);
            padding: 80px 0;
        }

        /* How it works */
        .section-header {
            margin-bottom: 40px;
            text-align: center;
        }
        .section-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #000;
        }
        
        .steps-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            padding: 20px 0;
            flex-wrap: wrap;
        }
        .step-card {
            display: flex;
            align-items: center;
            gap: 15px;
            background: transparent;
        }
        .step-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: #fff0e6;
            color: #2563eb;
            flex-shrink: 0;
        }
        .step-content h3 {
            font-size: 15px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #000;
        }
        .step-content p {
            font-size: 12px;
            color: #64748b;
            line-height: 1.4;
            max-width: 150px;
        }
        .step-connector {
            flex-grow: 1;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M0,10 Q25,0 50,10 T95,10' fill='none' stroke='%23ffcc99' stroke-width='2' stroke-dasharray='4,4'/%3E%3Cpolygon points='100,10 90,5 90,15' fill='%23ffcc99'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            min-width: 40px;
            max-width: 80px;
            background-size: contain;
        }

        /* Restaurants Section Wrapper */
        .restaurants-section-wrapper {
            background: #fffaf0;
            padding: 60px 0;
            position: relative;
        }
        .section-header-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .section-header-flex h2 {
            font-size: 20px;
            font-weight: 700;
            color: #000;
        }
        .view-all {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .restaurants-grid-container {
            position: relative;
        }
        .restaurants-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .scroll-arrow {
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 40px;
            height: 40px;
            background: white;
            border: 1px solid #eee;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            z-index: 10;
            color: #000;
        }
        .restaurant-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            transition: 0.3s;
        }
        .restaurant-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }
        .rest-image {
            height: 160px;
            overflow: hidden;
        }
        .rest-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .rest-content {
            padding: 16px;
        }
        .rest-title-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }
        .rest-title {
            font-size: 15px;
            font-weight: 700;
            color: #000;
        }
        .rest-rating {
            background: #22c55e;
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .rest-rating i {
            font-size: 9px;
        }
        .rest-cuisine {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 16px;
        }
        .rest-bottom-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .rest-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 11px;
            color: #475569;
            font-weight: 500;
        }
        .rest-meta i {
            color: #94a3b8;
        }
        .discount-badge {
            background: #2563eb;
            color: white;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: 700;
        }

        /* Footer Features Wrapper */
        .footer-features-wrapper {
            background: #ffffff;
            padding: 40px 0;
            border-top: 1px solid #f1f5f9;
        }
        .footer-grid {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }
        .footer-feature {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .ff-icon-wrap {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #fff0e6;
            color: #2563eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }
        .ff-content h4 {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
            color: #000;
        }
        .ff-content p {
            font-size: 12px;
            color: #64748b;
        }

        /* Promo Banner Section */
        .promo-banner-section {
            padding: 40px 0;
            background: #fff;
            
        }
        .promo-banner {
            background: linear-gradient(135deg, #16123a, #110e28);
            border-radius: 20px;
            padding: 40px 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
            
        }
        .promo-banner::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: radial-gradient(#2d2668 1px, transparent 1px);
            background-size: 40px 40px;
            opacity: 0.3;
            z-index: 0;
        }
        .promo-content {
            position: relative;
            z-index: 2;
            max-width: 40%;
        }
        .promo-content h2 {
            color: white;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 15px;
            line-height: 1.2;
        }
        .promo-content p {
            color: #cbd5e1;
            font-size: 16px;
            margin-bottom: 25px;
            line-height: 1.5;
        }
        .btn-track {
            background: #2563eb;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .promo-graphics {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            width: 60%;
        }
        .promo-graphics img {
            max-height: 250px;
            object-fit: contain;
        }

        /* Exclusive Offers Section */
        .offers-section {
            padding: 40px 0;
            background: #fff;
        }
        .offers-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .offers-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #000;
        }
        .offers-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }
        .offer-card {
            border-radius: 16px;
            padding: 20px;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
            min-height: 180px;
        }
        .offer-card.bg-orange { background: linear-gradient(to right bottom, #fff5eb, #ffe8d6); }
        .offer-card.bg-green { background: linear-gradient(to right bottom, #f0fdf4, #dcfce7); }
        .offer-card.bg-blue { background: linear-gradient(to right bottom, #eff6ff, #dbeafe); }
        .offer-card.bg-purple { background: linear-gradient(to right bottom, #faf5ff, #f3e8ff); }
        
        .offer-text-top {
            position: relative;
            z-index: 2;
            max-width: 65%;
        }
        .offer-title {
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 4px;
        }
        .offer-title.text-orange { color: #1d4ed8; }
        .offer-title.text-green { color: #16a34a; }
        .offer-title.text-blue { color: #2563eb; }
        .offer-title.text-purple { color: #9333ea; }
        
        .offer-value {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .offer-value.text-orange { color: #1d4ed8; }
        .offer-value.text-green { color: #16a34a; }
        .offer-value.text-blue { color: #2563eb; }
        .offer-value.text-purple { color: #9333ea; }

        .offer-desc {
            font-size: 12px;
            color: #475569;
            margin-bottom: 15px;
        }
        .offer-code {
            font-size: 12px;
            font-weight: 700;
            background: rgba(255,255,255,0.7);
            padding: 6px 12px;
            border-radius: 20px;
            display: inline-block;
        }
        .offer-code.text-orange { color: #1d4ed8; }
        .offer-code.text-green { color: #16a34a; }
        .offer-code.text-blue { color: #2563eb; }
        .offer-code.text-purple { color: #9333ea; }
        
        .offer-img {
            position: absolute;
            bottom: -10px;
            right: -10px;
            width: 120px;
            height: 120px;
            object-fit: contain;
            z-index: 1;
        }

        @keyframes pulse {
            0% { transform: translate(-50%, -50%) scale(1); opacity: 1; }
            100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container { padding: 0 15px; }
            .hero-title { font-size: 32px; }
            
            /* Hide desktop header */
            .desktop-header { display: none !important; }
            
            /* Body padding for bottom nav */
            body { padding-bottom: 70px; }
            
            /* Mobile Top Bar */
            .mobile-header {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                padding: 8px 15px;
                background: #00175b;
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                z-index: 50;
            }
            .mobile-header-scrolled {
                background: #00175b;
                position: fixed;
            }
            .mobile-header .logo-group {
                display: flex;
                align-items: center;
                gap: 10px;
            }
            .mobile-header .logo-img {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: #2563eb;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 18px;
            }
            .mobile-header .brand-text {
                font-size: 18px;
                font-weight: 800;
                color: white;
                line-height: 1;
            }
            .mobile-header .sub-text {
                font-size: 11px;
                color: rgba(255,255,255,0.7);
                margin-top: 2px;
            }
            .mobile-actions {
                display: flex;
                gap: 12px;
            }
            .mobile-icon-btn {
                width: 36px;
                height: 36px;
                border-radius: 50%;
                background: rgba(255,255,255,0.1);
                color: white;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
                font-size: 16px;
            }
            .badge-dot {
                position: absolute;
                top: 0;
                right: 0;
                width: 12px;
                height: 12px;
                background: red;
                border-radius: 50%;
                border: 2px solid var(--bg-dark);
                font-size: 8px;
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-weight: bold;
            }

            /* Bottom Navigation */
            .bottom-nav {
                display: flex !important;
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                background: white;
                box-shadow: 0 -4px 15px rgba(0,0,0,0.05);
                z-index: 100;
                padding: 12px 15px;
                justify-content: space-between;
                border-top-left-radius: 20px;
                border-top-right-radius: 20px;
            }
            .bottom-nav-item {
                display: flex;
                flex-direction: column;
                align-items: center;
                gap: 5px;
                color: #64748b;
                text-decoration: none;
                font-size: 10px;
                font-weight: 600;
                flex: 1;
            }
            .bottom-nav-item i {
                font-size: 20px;
                margin-bottom: 2px;
            }
            .bottom-nav-item.active {
                color: #5b21b6; /* Deep Purple */
            }
            .bottom-nav-item.active i {
                color: #5b21b6;
            }

            /* How It Works Mobile Redesign */
            .steps-wrapper {
                flex-direction: column !important;
                align-items: stretch !important;
                gap: 15px !important;
                padding: 10px 0 !important;
            }
            .step-card {
                background: white;
                padding: 15px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0,0,0,0.03);
                width: 100%;
            }
            .step-connector {
                display: none !important;
            }

            /* Exclusive Offers Mobile Redesign */
            .offers-grid {
                display: flex !important;
                flex-wrap: nowrap !important;
                overflow-x: auto !important;
                scroll-snap-type: x mandatory;
                padding-bottom: 20px;
                gap: 15px !important;
                -webkit-overflow-scrolling: touch;
                margin: 0 -15px;
                padding: 10px 15px 20px 15px;
            }
            .offers-grid > div {
                flex: 0 0 260px !important;
                scroll-snap-align: start;
            }

            /* Inner Page Header Styles */
            .inner-page-header {
                position: sticky !important;
                background: #00175b !important;
                box-shadow: 0 4px 20px rgba(0, 23, 91, 0.15) !important;
            }
            .offers-grid::-webkit-scrollbar {
                display: none;
            }
            .offer-card {
                flex: 0 0 85% !important;
                scroll-snap-align: center;
            }

            /* Footer Mobile Redesign */
            .footer-features-wrapper {
                padding: 30px 15px !important;
            }
            .footer-grid {
                display: grid !important;
                grid-template-columns: 1fr 1fr !important;
                gap: 20px 10px !important;
                align-items: start !important;
            }
            .footer-grid .footer-feature {
                flex-direction: column !important;
                align-items: center !important;
                text-align: center !important;
                gap: 10px !important;
            }
            .footer-grid .ff-icon-wrap {
                margin: 0 auto !important;
            }
            .main-footer {
                padding: 30px 15px 90px 15px !important; 
            }
            .main-footer .container {
                grid-template-columns: repeat(3, 1fr) !important;
                gap: 25px 10px !important;
            }
            .main-footer .container > div:first-child {
                grid-column: span 3;
            }
            .main-footer .container > div:nth-child(2) {
                grid-column: span 1;
            }
            .main-footer .container > div:last-child {
                grid-column: span 3;
            }
            .main-footer .container > div:last-child > div {
                flex-direction: row !important;
                gap: 10px !important;
            }
            .main-footer .container > div:last-child > div a {
                padding: 8px !important;
                flex: 1;
                justify-content: center;
            }
            .main-footer .container > div:last-child > div a i {
                font-size: 20px !important;
            }
            .main-footer h4 {
                font-size: 14px !important;
                margin-bottom: 12px !important;
            }
            .main-footer ul {
                gap: 8px !important;
            }
            .main-footer ul li a {
                font-size: 12px !important;
            }
            .main-footer > div:last-child .container {
                flex-direction: column !important;
                text-align: center;
                gap: 10px !important;
                font-size: 12px !important;
            }
        }

        /* Default hide mobile elements on desktop */
        .mobile-header, .bottom-nav {
            display: none;
        }

        /* Mobile Login Bottom Sheet */
        .mobile-login-overlay {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(2px);
            z-index: 10000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .mobile-login-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        .mobile-login-sheet {
            position: fixed;
            bottom: -100%;
            left: 0; right: 0;
            background: white;
            border-top-left-radius: 24px;
            border-top-right-radius: 24px;
            padding: 30px 20px 40px 20px;
            z-index: 10001;
            transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);
            box-shadow: 0 -10px 40px rgba(0,0,0,0.15);
        }
        .mobile-login-sheet.active {
            bottom: 0;
        }
        .sheet-close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            color: #64748b;
            cursor: pointer;
            background: none; border: none;
            padding: 5px;
        }
        .sheet-header {
            text-align: center;
            margin-bottom: 25px;
        }
        .sheet-header h2 {
            color: #0f172a;
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 8px;
        }
        .sheet-header p {
            color: #64748b;
            font-size: 14px;
        }
        .sheet-form-group {
            margin-bottom: 16px;
        }
        .sheet-input {
            width: 100%;
            padding: 16px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            background: #f8fafc;
            color: #0f172a;
            transition: 0.3s;
        }
        .sheet-input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
        }
        .sheet-btn {
            width: 100%;
            padding: 16px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            margin-top: 10px;
            cursor: pointer;
        }
        .sheet-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
        }
        .sheet-footer a {
            color: #2563eb;
            font-weight: 700;
            text-decoration: none;
        }

        /* Mobile Side Navigation Drawer */
        .mobile-sidenav-overlay {
            position: fixed; top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(0,0,0,0.6); backdrop-filter: blur(2px);
            z-index: 10000; opacity: 0; visibility: hidden; transition: all 0.3s ease;
        }
        .mobile-sidenav-overlay.active { opacity: 1; visibility: visible; }

        .mobile-sidenav {
            position: fixed; top: 0; right: -280px; width: 260px; height: 100%;
            background: white; z-index: 10001; transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1);
            box-shadow: -10px 0 40px rgba(0,0,0,0.15); display: flex; flex-direction: column;
        }
        .mobile-sidenav.active { right: 0; }
        .sidenav-header {
            padding: 20px; display: flex; justify-content: space-between; align-items: center;
            border-bottom: 1px solid #f1f5f9;
        }
        .sidenav-header h3 { font-size: 18px; font-weight: 800; color: #0f172a; margin: 0; }
        .sidenav-close { background: none; border: none; font-size: 20px; color: #64748b; cursor: pointer; }
        .sidenav-links { padding: 20px 0; display: flex; flex-direction: column; overflow-y: auto; }
        .sidenav-links a {
            padding: 15px 20px; color: #334155; text-decoration: none; font-size: 15px; font-weight: 600;
            display: flex; align-items: center; gap: 15px; border-bottom: 1px solid #f8fafc; transition: 0.2s;
        }
        .sidenav-links a:hover { background: #f8fafc; color: #2563eb; }
        .sidenav-links a i { width: 20px; text-align: center; color: #94a3b8; }
        .sidenav-links a:hover i { color: #2563eb; }

        /* Generic Page Responsiveness */
        .page-card {
            background: white; border-radius: 20px; padding: 60px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); max-width: 600px; margin: 0 auto;
        }
        .page-form {
            display: flex; gap: 10px; max-width: 400px; margin: 0 auto;
        }
        .section-header-flex {
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;
        }
        .steps-wrapper {
            display: flex; flex-wrap: wrap; justify-content: center; gap: 20px;
        }

        @media (max-width: 768px) {
            .container { padding: 0 15px !important; }
            .hero { display: block !important; }
            .restaurants-grid {
                grid-template-columns: 1fr 1fr !important;
                gap: 10px !important;
            }
            .restaurants-grid .rest-image { height: 120px !important; }
            .restaurants-grid .rest-content { padding: 10px !important; }
            .restaurants-grid .rest-title { font-size: 13px !important; }
            .restaurants-grid .rest-cuisine { font-size: 10px !important; margin-bottom: 8px !important; }
            .restaurants-grid .rest-meta span { font-size: 10px !important; }
            .restaurants-grid .discount-badge { font-size: 9px !important; padding: 2px 4px !important; }
            .restaurants-grid .rest-rating { font-size: 10px !important; padding: 2px 4px !important; }
            
            .page-card { padding: 30px 20px !important; }
            .page-card h1 { font-size: 24px !important; }
            .page-form { flex-direction: column !important; }
            .section-header-flex { flex-direction: column !important; align-items: stretch !important; gap: 15px !important; }
            .section-header-flex input { width: 100% !important; }
            .section-header-flex > div { width: 100%; display: flex; flex-direction: column; gap: 10px !important; }
            .section-header-flex button { width: 100%; }
            .steps-wrapper { flex-direction: column; align-items: center; }
            .steps-wrapper .step-connector { display: none !important; }
            .steps-wrapper .step-card { width: 100% !important; max-width: 100% !important; }
        }
    </style>
</head>
<body>

    <!-- Mobile Header (Hidden on Desktop) -->
    <div class="mobile-header {{ request()->routeIs('home') ? '' : 'inner-page-header' }}">
        <div class="logo-group">
            <a href="{{ route('home') }}" style="display: flex; align-items: center;">
                <img src="{{ asset('images/updatedlogo.jpg') }}" style="height: 50px; width: 120px; border-radius: 8px; object-fit: cover;" alt="QuickDines">
            </a>
        </div>
        <div class="mobile-actions">
            @auth
            <a href="{{ route('dashboard') }}" style="text-decoration: none; background: #f1f5f9; border: 1px solid #e2e8f0; color: #0f172a; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                <i class="fa-regular fa-user"></i> Dashboard
            </a>
            @else
            <a href="javascript:void(0)" onclick="openLoginSheet()" class="mobile-icon-btn" style="text-decoration: none;">
                <i class="fa-regular fa-user"></i>
            </a>
            @endauth
            <a href="javascript:void(0)" onclick="openSidenav()" class="mobile-icon-btn" style="text-decoration: none;">
                <i class="fa-solid fa-bars"></i>
            </a>
        </div>
    </div>

    <!-- Header (Full Width - Desktop) -->
    <header class="desktop-header" style="background-color: #00175b; width: 100%;">
        <div class="container">
            <!-- Navbar -->
            <nav>
                <div class="logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('images/updatedlogo.jpg') }}" style="width:140px;height:65px; border-radius: 12px; object-fit: cover;" alt="QuickDines">
                    </a>
                </div>
                <div class="nav-links">
                    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                    <a href="{{ route('restaurants.index') }}" class="{{ request()->routeIs('restaurants.*') ? 'active' : '' }}">All Dishes</a>
                    <a href="{{ route('track-bus') }}" class="{{ request()->routeIs('track-bus') ? 'active' : '' }}">Track Restaurant</a>
                    <a href="{{ route('how-it-works') }}" class="{{ request()->routeIs('how-it-works') ? 'active' : '' }}">How It Works</a>
                    <a href="{{ route('support') }}" class="{{ request()->routeIs('support') ? 'active' : '' }}">Support</a>
                </div>
                <div class="nav-actions">
                    @auth
                    <a href="{{ route('dashboard') }}" class="btn-primary" style="gap: 10px; padding-left: 10px;">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" style="width: 24px; height: 24px; border-radius: 50%; object-fit: cover; border: 2px solid rgba(255,255,255,0.4);" alt="Avatar">
                        @else
                            <div style="width: 24px; height: 24px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; text-transform: uppercase;">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                        @endif
                        Dashboard
                    </a>
                    @else
                    <a href="{{ route('login') }}" class="btn-primary"><i class="fa-regular fa-user"></i> Login / Sign Up</a>
                    @endauth
                </div>
            </nav>
        </div>
    </header>

    @yield('content')
    <!-- Footer Features Wrapper -->
    <div class="footer-features-wrapper">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-feature">
                    <div class="ff-icon-wrap"><i class="fa-solid fa-store"></i></div>
                    <div class="ff-content">
                        <h4>100+ Restaurants</h4>
                        <p>Wide variety of cuisines</p>
                    </div>
                </div>
                <div class="footer-feature">
                    <div class="ff-icon-wrap"><i class="fa-solid fa-location-crosshairs"></i></div>
                    <div class="ff-content">
                        <h4>Live Order Tracking</h4>
                        <p>Track your order in real-time</p>
                    </div>
                </div>
                <div class="footer-feature">
                    <div class="ff-icon-wrap"><i class="fa-regular fa-credit-card"></i></div>
                    <div class="ff-content">
                        <h4>Multiple Payment Options</h4>
                        <p>UPI, Cards, Wallets & COD</p>
                    </div>
                </div>
                <div class="footer-feature">
                    <div class="ff-icon-wrap"><i class="fa-solid fa-headset"></i></div>
                    <div class="ff-content">
                        <h4>24/7 Customer Support</h4>
                        <p>We're here to help you</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Footer -->
    <footer class="main-footer" style="background: #02060c; color: #cbd5e1; padding: 60px 0 20px 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        <div class="container" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 40px; margin-bottom: 40px;">
            
            <!-- Brand Section -->
            <div>
                <a href="{{ url('/') }}" style="display: flex; align-items: center; gap: 10px; color: white; text-decoration: none; font-size: 24px; font-weight: 800; font-family: 'Arial Black', Impact, sans-serif; letter-spacing: -1px; margin-bottom: 20px;">
                    <i class="fa-solid fa-utensils" style="color: #2563eb;"></i>
                    QuickDines
                </a>
                <p style="font-size: 14px; line-height: 1.6; color: #94a3b8; margin-bottom: 20px;">
                    Experience the fastest food delivery directly to your bus seat. Track live, eat fresh!
                </p>
                <div style="display: flex; gap: 10px;">
                    <a href="#" style="background: #1e293b; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#1e293b'"><i class="fa-brands fa-facebook-f"></i></a>
                    <a href="#" style="background: #1e293b; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#1e293b'"><i class="fa-brands fa-twitter"></i></a>
                    <a href="#" style="background: #1e293b; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#1e293b'"><i class="fa-brands fa-instagram"></i></a>
                    <a href="#" style="background: #1e293b; color: white; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: 0.3s;" onmouseover="this.style.background='#2563eb'" onmouseout="this.style.background='#1e293b'"><i class="fa-brands fa-linkedin-in"></i></a>
                </div>
            </div>

            <!-- Company -->
            <div>
                <h4 style="color: white; font-size: 16px; font-weight: 600; margin-bottom: 20px;">Company</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">About Us</a></li>
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Careers</a></li>
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Team</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 style="color: white; font-size: 16px; font-weight: 600; margin-bottom: 20px;">Contact</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Help & Support</a></li>
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Partner with us</a></li>
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Ride with us</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 style="color: white; font-size: 16px; font-weight: 600; margin-bottom: 20px;">Legal</h4>
                <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 12px;">
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Terms & Conditions</a></li>
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Cookie Policy</a></li>
                    <li><a href="#" style="color: #94a3b8; text-decoration: none; font-size: 14px; transition: 0.3s;" onmouseover="this.style.color='white'" onmouseout="this.style.color='#94a3b8'">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Download App -->
            <div>
                <h4 style="color: white; font-size: 16px; font-weight: 600; margin-bottom: 20px;">Available on</h4>
                <div style="display: flex; flex-direction: column; gap: 15px;">
                    <a href="#" style="background: white; border-radius: 8px; padding: 10px 15px; display: flex; align-items: center; gap: 12px; text-decoration: none; color: black; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fa-brands fa-google-play" style="font-size: 24px;"></i>
                        <div style="line-height: 1;">
                            <div style="font-size: 10px; color: #64748b;">GET IT ON</div>
                            <div style="font-size: 14px; font-weight: 700;">Google Play</div>
                        </div>
                    </a>
                    <a href="#" style="background: white; border-radius: 8px; padding: 10px 15px; display: flex; align-items: center; gap: 12px; text-decoration: none; color: black; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                        <i class="fa-brands fa-apple" style="font-size: 24px;"></i>
                        <div style="line-height: 1;">
                            <div style="font-size: 10px; color: #64748b;">Download on the</div>
                            <div style="font-size: 14px; font-weight: 700;">App Store</div>
                        </div>
                    </a>
                </div>
            </div>

        </div>

        <div style="border-top: 1px solid #1e293b; padding-top: 25px; margin-top: 20px;">
            <div class="container" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; font-size: 14px; color: #64748b;">
                <div>
                    &copy; 2026 QuickDines Technologies Pvt. Ltd
                </div>
                <div style="text-align: inherit;">
                    <span style="font-size: 12px;">Designed and developed by <a href="https://quickdines.com/" target="_blank" style="color: white; font-weight: 800; text-decoration: none; transition: color 0.3s;" onmouseover="this.style.color='#2563eb'" onmouseout="this.style.color='white'">QuickDines Pvt Ltd</a></span>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bottom Navigation Bar (Mobile Only) -->
    <nav class="bottom-nav">
        <a href="{{ route('home') }}" class="bottom-nav-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fa-solid fa-house"></i>
            <span>Home</span>
        </a>
        <a href="{{ route('restaurants.index') }}" class="bottom-nav-item {{ request()->routeIs('restaurants.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bowl-food"></i>
            <span>All Dishes</span>
        </a>
        <a href="{{ route('track-bus') }}" class="bottom-nav-item {{ request()->routeIs('track-bus') ? 'active' : '' }}">
            <i class="fa-solid fa-utensils"></i>
            <span>Track Restaurant</span>
        </a>
        @auth
        <a href="{{ route('dashboard') }}" class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Orders</span>
        </a>
        <a href="{{ route('profile') }}" class="bottom-nav-item {{ request()->routeIs('profile') ? 'active' : '' }}">
            <div style="width: 24px; height: 24px; border-radius: 50%; background: {{ request()->routeIs('profile') ? '#5b21b6' : '#f1f5f9' }}; color: {{ request()->routeIs('profile') ? 'white' : '#475569' }}; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; text-transform: uppercase; margin-bottom: 2px;">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
            <span>Profile</span>
        </a>
        @else
        <a href="javascript:void(0)" onclick="openLoginSheet()" class="bottom-nav-item">
            <i class="fa-solid fa-bag-shopping"></i>
            <span>Orders</span>
        </a>
        <a href="javascript:void(0)" onclick="openLoginSheet()" class="bottom-nav-item">
            <i class="fa-regular fa-user"></i>
            <span>Profile</span>
        </a>
        @endauth
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const trackBtn = document.querySelector('.btn-track');
            const inlineForm = document.getElementById('inlinePhoneForm');
            const inlineMap = document.getElementById('inlinePhoneMap');
            const inlineBusForm = document.getElementById('inlineBusForm');
            
            const busInput = document.getElementById('busNumberInput');
            const busDisplay = document.getElementById('liveBusNumberDisplay');
            const mapImg = document.getElementById('mapImage');
            const mapOverlay = document.getElementById('mapOverlay');
            const busMarker = document.getElementById('busLiveMarker');

            if(trackBtn && inlineForm && inlineMap) {
                trackBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Hide map layout, show the tracking form inside the phone
                    inlineMap.style.display = 'none';
                    inlineForm.style.display = 'block';
                });
            }

            if(inlineBusForm) {
                inlineBusForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    // Update bus number dynamically
                    if (busInput && busDisplay && busInput.value.trim() !== '') {
                        busDisplay.textContent = busInput.value.trim().toUpperCase();
                    }
                    
                    // Show map layout
                    inlineForm.style.display = 'none';
                    inlineMap.style.display = 'block';
                    
                    // Zoom the map inside the phone and show live marker
                    if(mapImg && mapOverlay && busMarker) {
                        mapImg.style.transform = 'scale(2.5)';
                        mapOverlay.style.opacity = '0';
                        mapOverlay.style.pointerEvents = 'none';
                        busMarker.style.opacity = '1';
                    }
                });
            }
        });
    </script>

    <!-- Mobile Login Bottom Sheet -->
    <div class="mobile-login-overlay" id="loginOverlay" onclick="closeLoginSheet()"></div>
    <div class="mobile-login-sheet" id="loginSheet">
        <button class="sheet-close" onclick="closeLoginSheet()"><i class="fa-solid fa-xmark"></i></button>
        <div class="sheet-header">
            <h2>Welcome Back!</h2>
            <p>Login to order your favorite food on the go</p>
        </div>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 12px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="sheet-form-group">
                <input type="text" name="mobile" class="sheet-input" placeholder="Mobile Number" value="{{ old('mobile') }}" required>
            </div>
            <div class="sheet-form-group">
                <input type="password" name="password" class="sheet-input" placeholder="Password" required>
            </div>
            <button type="submit" class="sheet-btn">Login Securely</button>
        </form>
        <div class="sheet-footer">
            Don't have an account? <a href="{{ route('register') }}">Sign Up</a>
        </div>
    </div>

    <script>
        function openLoginSheet() {
            document.getElementById('loginOverlay').classList.add('active');
            document.getElementById('loginSheet').classList.add('active');
            document.body.style.overflow = 'hidden'; // Prevent background scrolling
        }

        function closeLoginSheet() {
            document.getElementById('loginOverlay').classList.remove('active');
            document.getElementById('loginSheet').classList.remove('active');
            document.body.style.overflow = ''; // Restore scrolling
        }

        @if($errors->any() && !request()->routeIs('register'))
            document.addEventListener('DOMContentLoaded', function() {
                openLoginSheet();
            });
        @endif

        // Mobile Sidenav JS
        function openSidenav() {
            document.getElementById('sidenavOverlay').classList.add('active');
            document.getElementById('mobileSidenav').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        function closeSidenav() {
            document.getElementById('sidenavOverlay').classList.remove('active');
            document.getElementById('mobileSidenav').classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>

    <!-- Mobile Side Navigation Drawer -->
    <div class="mobile-sidenav-overlay" id="sidenavOverlay" onclick="closeSidenav()"></div>
    <div class="mobile-sidenav" id="mobileSidenav">
        <div class="sidenav-header">
            <h3>Menu</h3>
            <button class="sidenav-close" onclick="closeSidenav()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="sidenav-links">
            <a href="{{ route('home') }}"><i class="fa-solid fa-house"></i> Home</a>
            <a href="{{ route('restaurants.index') }}"><i class="fa-solid fa-utensils"></i> All Dishes</a>
            <a href="{{ route('track-bus') }}"><i class="fa-solid fa-utensils"></i> Track Restaurant</a>
            <a href="{{ route('how-it-works') }}"><i class="fa-solid fa-circle-question"></i> How It Works</a>
            <a href="{{ route('support') }}"><i class="fa-solid fa-headset"></i> Support</a>
        </div>
    </div>
    @stack('scripts')
</body>
</html>
