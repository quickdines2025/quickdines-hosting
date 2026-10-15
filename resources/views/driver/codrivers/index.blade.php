@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card">
    <div class="card-header-title">
        <h3>Co-Drivers</h3>
    </div>
    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
        <i class="fa-solid fa-users" style="font-size: 48px; margin-bottom: 16px; color: #cbd5e1;"></i>
        <h2>Bus Crew</h2>
        <p>Other drivers assigned to Bus {{ $driver->bus->bus_number ?? '' }} will appear here.</p>
    </div>
</div>
@endsection
