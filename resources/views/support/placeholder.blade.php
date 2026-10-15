@extends('layouts.support')

@section('title', $title)

@section('content')
<div class="card" style="text-align: center; padding: 60px 20px;">
    <i class="fa-solid fa-person-digging fa-4x" style="color: #cbd5e1; margin-bottom: 20px;"></i>
    <h2 style="color: #0f172a; margin-bottom: 10px;">{{ $title }}</h2>
    <p style="color: #64748b; font-size: 16px;">This module is currently under construction. Please check back later.</p>
</div>
@endsection
