
<style>
    .mobile-bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: #ffffff;
        display: flex;
        justify-content: space-around;
        align-items: center;
        padding: 10px 0;
        box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
        z-index: 1000;
        padding-bottom: calc(10px + env(safe-area-inset-bottom));
    }
    
    .nav-item-m {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-decoration: none;
        color: #94a3b8;
        font-size: 10px;
        gap: 4px;
        transition: 0.2s;
        width: 20%;
    }
    
    .nav-item-m i {
        font-size: 20px;
        margin-bottom: 2px;
    }
    
    .nav-item-m.active {
        color: #2563eb;
        font-weight: 600;
    }
    
    /* Spacer for the bottom nav so content is not hidden */
    .bottom-nav-spacer {
        height: 70px;
        width: 100%;
    }
</style>

<div class="bottom-nav-spacer"></div>
<div class="mobile-bottom-nav">
    <a href="{{ route('home') }}" class="nav-item-m {{ ($activeTab ?? '') == 'home' ? 'active' : '' }}">
        <i class="{{ ($activeTab ?? '') == 'home' ? 'fa-solid' : 'fa-solid' }} fa-house"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('favourites') }}" class="nav-item-m {{ ($activeTab ?? '') == 'favourites' ? 'active' : '' }}">
        <i class="{{ ($activeTab ?? '') == 'favourites' ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
        <span>Browse</span>
    </a>
    <a href="{{ route('dashboard') }}" class="nav-item-m {{ ($activeTab ?? '') == 'orders' ? 'active' : '' }}">
        <i class="fa-solid fa-receipt"></i>
        <span>My Orders</span>
    </a>
    <a href="{{ route('track-order') }}" class="nav-item-m {{ ($activeTab ?? '') == 'track' ? 'active' : '' }}">
        <i class="fa-solid fa-location-dot"></i>
        <span>Track Order</span>
    </a>
    <a href="#" class="nav-item-m {{ ($activeTab ?? '') == 'profile' ? 'active' : '' }}">
        <i class="{{ ($activeTab ?? '') == 'profile' ? 'fa-solid' : 'fa-regular' }} fa-user"></i>
        <span>Profile</span>
    </a>
</div>

