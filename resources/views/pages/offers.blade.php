@extends('layouts.frontend')

@section('content')
<div class="light-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 40px 15px;">
    <div class="container" style="text-align: center;">
        <div class="page-card">
            <div style="width: 80px; height: 80px; background: #fff0e6; color: #2563eb; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 24px;">
                <i class="fa-solid fa-tags"></i>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: #000; margin-bottom: 16px;">Exclusive Offers</h1>
            <p style="color: #64748b; margin-bottom: 30px; font-size: 15px; line-height: 1.6;">Check back soon for the latest deals, cashback offers, and combo meals exclusively for bus travelers!</p>
            <a href="{{ route('restaurants.index') }}" class="btn-primary">Browse Restaurants</a>
        </div>
    </div>
</div>
@endsection
