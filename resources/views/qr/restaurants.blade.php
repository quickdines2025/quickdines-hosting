@extends('layouts.frontend')

@section('content')
<div style="max-width:560px;margin:0 auto;padding:20px 16px;">
    <h2 style="margin-bottom:16px;font-size:22px;font-weight:800;">Choose Restaurant</h2>
    @foreach($restaurants as $restaurant)
        <a href="{{ route('qr.select-restaurant', $restaurant->id) }}" style="display:block;text-decoration:none;color:inherit;background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:18px;margin-bottom:14px;box-shadow:0 1px 3px rgba(0,0,0,.05);">
            <div style="font-size:18px;font-weight:700;">{{ $restaurant->restaurant_name }}</div>
            @if($restaurant->cuisines)
                <div style="color:#64748b;font-size:13px;margin-top:4px;">{{ $restaurant->cuisines }}</div>
            @endif
            <div style="margin-top:12px;background:#0f172a;color:#fff;text-align:center;padding:12px;border-radius:10px;font-weight:600;">View Menu</div>
        </a>
    @endforeach
</div>
@endsection
