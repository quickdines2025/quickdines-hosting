@extends('layouts.frontend')

@section('content')
<div style="max-width:480px;margin:80px auto;padding:24px;text-align:center;">
    <div style="font-size:48px;">🍽️</div>
    <h2 style="margin:12px 0 8px;">No restaurants available</h2>
    <p style="color:#64748b;">No restaurants are available for bus {{ $bus->bus_number ?? '' }} right now.</p>
</div>
@endsection
