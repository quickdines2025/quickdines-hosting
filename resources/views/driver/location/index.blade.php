@extends('layouts.driver')

@section('bus_number', $driver->bus->bus_number ?? 'Not Assigned')

@section('content')
<div class="card">
    <div class="card-header-title" style="display:flex;justify-content:space-between;align-items:center;gap:12px;">
        <h3 style="margin:0;">Live Location Tracking</h3>
        <span id="gps-status" style="padding:6px 12px;border-radius:20px;background:#fef3c7;color:#92400e;font-size:12px;font-weight:700;">Starting GPS…</span>
    </div>

    <div style="padding:24px;">
        @if($driver && $driver->bus)
            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:20px;">
                <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;">
                    <div style="font-size:12px;color:#64748b;">Bus</div>
                    <strong>{{ $driver->bus->bus_number }}</strong>
                </div>
                <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;">
                    <div style="font-size:12px;color:#64748b;">Latitude</div>
                    <strong id="lat">{{ $driver->bus->current_lat ?? '—' }}</strong>
                </div>
                <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;">
                    <div style="font-size:12px;color:#64748b;">Longitude</div>
                    <strong id="lng">{{ $driver->bus->current_lng ?? '—' }}</strong>
                </div>
                <div style="padding:16px;border:1px solid #e2e8f0;border-radius:10px;background:#f8fafc;">
                    <div style="font-size:12px;color:#64748b;">Last Update</div>
                    <strong id="updated">{{ optional($driver->bus->journey_updated_at)->format('h:i:s A') ?? '—' }}</strong>
                </div>
            </div>

            <div id="gps-help" style="padding:14px;border-radius:10px;background:#eff6ff;color:#1e40af;font-size:13px;">
                Keep this page open during the journey. Your browser will send GPS updates automatically every 10 seconds to update restaurant ETAs.
            </div>
        @else
            <div style="padding:60px 20px;text-align:center;color:#64748b;">
                <i class="fa-solid fa-bus-slash" style="font-size:48px;margin-bottom:16px;"></i>
                <h3>No bus assigned</h3>
                <p>Please contact admin to assign a bus before starting live tracking.</p>
            </div>
        @endif
    </div>
</div>

@if($driver && $driver->bus)
<script>
(() => {
    const status = document.getElementById('gps-status');
    const latEl = document.getElementById('lat');
    const lngEl = document.getElementById('lng');
    const updatedEl = document.getElementById('updated');
    const url = @json(route('driver.location.update'));
    const csrf = @json(csrf_token());

    function sendPosition(position) {
        const lat = Number(position.coords.latitude).toFixed(7);
        const lng = Number(position.coords.longitude).toFixed(7);
        fetch(url, {
            method: 'POST',
            headers: {'Content-Type':'application/json','Accept':'application/json','X-CSRF-TOKEN':csrf},
            body: JSON.stringify({lat, lng})
        }).then(r => r.json()).then(data => {
            if (!data.success) throw new Error(data.message || 'GPS update failed');
            latEl.textContent = lat;
            lngEl.textContent = lng;
            updatedEl.textContent = new Date().toLocaleTimeString();
            status.textContent = 'GPS Live';
            status.style.background = '#dcfce7';
            status.style.color = '#166534';
        }).catch(() => {
            status.textContent = 'Update failed';
            status.style.background = '#fee2e2';
            status.style.color = '#991b1b';
        });
    }

    function gpsError(error) {
        status.textContent = error.code === 1 ? 'GPS Permission Required' : 'GPS Unavailable';
        status.style.background = '#fee2e2';
        status.style.color = '#991b1b';
    }

    function requestLocation() {
        if (!navigator.geolocation) {
            status.textContent = 'GPS Not Supported';
            return;
        }
        navigator.geolocation.getCurrentPosition(sendPosition, gpsError, {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 5000
        });
    }

    requestLocation();
    setInterval(requestLocation, 10000);
})();
</script>
@endif
@endsection
