@extends('layouts.frontend')

@section('content')
<div class="light-content" style="min-height: 60vh; display: flex; align-items: center; justify-content: center; padding: 40px 15px;">
    <div class="container" style="text-align: center;">
        <div class="page-card">
            <div style="width: 80px; height: 80px; background: #fef2f2; color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 24px;">
                <i class="fa-solid fa-headset"></i>
            </div>
            <h1 style="font-size: 28px; font-weight: 800; color: #000; margin-bottom: 16px;">24/7 Customer Support</h1>
            <p style="color: #64748b; margin-bottom: 30px; font-size: 15px; line-height: 1.6;">Need help with your order? Our support team is always available to assist you.</p>
            
            <div class="page-form" style="display: flex; flex-direction: column; gap: 15px; max-width: 300px; margin: 0 auto;">
                <a href="{{ route('customer.support') }}" class="btn-primary" style="width: 100%; text-align: center; padding: 14px;"><i class="fa-solid fa-comment-dots" style="margin-right: 8px;"></i> Chat with us</a>
                <a href="{{ route('customer.tickets') }}" style="color: #64748b; text-decoration: none; font-weight: 600;"><i class="fa-solid fa-envelope" style="margin-right: 8px;"></i> Support Ticket</a>
            </div>
        </div>
    </div>
</div>
@endsection
