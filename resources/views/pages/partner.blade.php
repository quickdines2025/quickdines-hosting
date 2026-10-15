@extends('layouts.frontend')

@section('content')
<div class="light-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 40px 15px;">
    <div class="container" style="text-align: center;">
        <div class="page-card">
            <div style="width: 80px; height: 80px; background: #f0fdf4; color: #22c55e; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 24px;">
                <i class="fa-solid fa-handshake"></i>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: #000; margin-bottom: 16px;">Partner With Us</h1>
            <p style="color: #64748b; margin-bottom: 30px; font-size: 15px; line-height: 1.6;">Grow your business by reaching thousands of bus travelers every day. Join our network of premium restaurants.</p>
            
            <form class="page-form">
                <input type="text" placeholder="Restaurant Name" style="flex-grow: 1; padding: 14px 20px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 15px;">
                <input type="email" placeholder="Email Address" style="padding: 14px 20px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 15px;">
                <input type="tel" placeholder="Mobile Number" style="padding: 14px 20px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 15px;">
                <button type="button" class="btn-primary" style="padding: 14px 24px; white-space: nowrap;">Apply Now</button>
            </form>
        </div>
    </div>
</div>
@endsection
