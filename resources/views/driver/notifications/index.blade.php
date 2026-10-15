@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card">
    <div class="card-header-title">
        <h3>Order Notifications</h3>
    </div>
    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
        <i class="fa-regular fa-bell" style="font-size: 48px; margin-bottom: 16px; color: #cbd5e1;"></i>
        <h2>No New Notifications</h2>
        <p>You're all caught up! New order alerts will appear here.</p>
    </div>
</div>
@endsection
