@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card">
    <div class="card-header-title">
        <h3>All Assigned Restaurants</h3>
    </div>
    
    @if(isset($restaurants) && count($restaurants) > 0)
    <div class="table-responsive">
        <table class="table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid #e2e8f0;">
                    <th style="padding: 12px;">Restaurant ID</th>
                    <th style="padding: 12px;">Restaurant Name</th>
                    <th style="padding: 12px;">Location</th>
                    <th style="padding: 12px;">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($restaurants as $restaurant)
                <tr style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 12px; font-weight: 600;">#RES{{ $restaurant->id }}</td>
                    <td style="padding: 12px; font-weight: 600; color: var(--primary);">{{ $restaurant->restaurant_name }}</td>
                    <td style="padding: 12px; color: var(--text-muted); line-height: 1.4;">
                        {{ $restaurant->address ?? 'N/A' }}
                        @if(!empty($restaurant->city))
                            <br><small style="color: #64748b; font-weight: 500;">{{ ucfirst($restaurant->city) }}</small>
                        @endif
                    </td>
                    <td style="padding: 12px;">
                        <span style="padding: 4px 8px; border-radius: 12px; font-size: 11px; font-weight: 600; background: #e0e7ff; color: #4338ca;">
                            Assigned
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
        <i class="fa-solid fa-store-slash" style="font-size: 48px; margin-bottom: 16px; color: #cbd5e1;"></i>
        <h2>No Restaurants Assigned</h2>
        <p>You have no restaurants assigned to this journey yet.</p>
    </div>
    @endif
</div>
@endsection
