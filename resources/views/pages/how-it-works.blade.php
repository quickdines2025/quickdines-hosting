@extends('layouts.frontend')

@section('content')
<div class="light-content" style="padding-top: 40px; min-height: 70vh;">
    <div class="container">
        <!-- Re-use the How it works section from the homepage -->
        <div class="section-header" style="margin-bottom: 50px;">
            <h2 style="font-size: 32px;">How QuickDines Works</h2>
            <p style="color: #64748b; max-width: 600px; margin: 10px auto 0;">Getting delicious food on your journey is now easier than ever. Just follow these simple steps.</p>
        </div>
        
        <div class="steps-wrapper" style="gap: 20px;">
            <div class="step-card" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); flex-direction: column; text-align: center; max-width: 250px;">
                <div class="step-icon" style="margin: 0 auto 20px; width: 80px; height: 80px; font-size: 32px;"><i class="fa-solid fa-qrcode"></i></div>
                <div class="step-content">
                    <h3 style="font-size: 18px;">1. Scan QR Code</h3>
                    <p style="font-size: 14px; max-width: none;">Scan the QR code available in your bus seat.</p>
                </div>
            </div>
            
            <div class="step-connector" style="min-width: 60px;"></div>
            
            <div class="step-card" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); flex-direction: column; text-align: center; max-width: 250px;">
                <div class="step-icon" style="margin: 0 auto 20px; width: 80px; height: 80px; font-size: 32px;"><i class="fa-solid fa-location-dot"></i></div>
                <div class="step-content">
                    <h3 style="font-size: 18px;">2. Choose Restaurant</h3>
                    <p style="font-size: 14px; max-width: none;">View nearby restaurants and check menus.</p>
                </div>
            </div>
            
            <div class="step-connector" style="min-width: 60px;"></div>
            
            <div class="step-card" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); flex-direction: column; text-align: center; max-width: 250px;">
                <div class="step-icon" style="margin: 0 auto 20px; width: 80px; height: 80px; font-size: 32px;"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="step-content">
                    <h3 style="font-size: 18px;">3. Place Your Order</h3>
                    <p style="font-size: 14px; max-width: none;">Add items to cart, enter seat details and order.</p>
                </div>
            </div>
            
            <div class="step-connector" style="min-width: 60px;"></div>
            
            <div class="step-card" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); flex-direction: column; text-align: center; max-width: 250px;">
                <div class="step-icon" style="margin: 0 auto 20px; width: 80px; height: 80px; font-size: 32px;"><i class="fa-solid fa-motorcycle"></i></div>
                <div class="step-content">
                    <h3 style="font-size: 18px;">4. Enjoy Your Meal</h3>
                    <p style="font-size: 14px; max-width: none;">Fresh food delivered directly to your seat.</p>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 60px;">
            <a href="{{ route('restaurants.index') }}" class="btn-primary" style="font-size: 16px; padding: 16px 32px;">Order Now</a>
        </div>
    </div>
</div>
@endsection
