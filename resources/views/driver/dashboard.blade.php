@extends('layouts.driver')

@section('title', 'Driver Dashboard')

@php
    $assignedRestaurantsCount = 0;
    if (isset($driver) && $driver->bus_id) {
        $assignedRestaurantsCount = \Illuminate\Support\Facades\DB::table('bus_restaurant')
            ->where('bus_id', $driver->bus_id)
            ->count();
    }
@endphp

@section('content')
<style>
    /* Hide layout's default header since we have a custom one */
    .top-header { display: none !important; }
    .dashboard-container { padding: 0 !important; }
    
    :root {
        --primary: #0a192f;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --text-main: #1e293b;
        --text-muted: #64748b;
        --border: #e2e8f0;
        --card-bg: #ffffff;
        --bg-main: #f8fafc;
    }
    
    body { background-color: var(--bg-main); font-family: 'Inter', sans-serif; }
    
    /* Topbar */
    .driver-topbar { display: flex; justify-content: space-between; align-items: center; padding: 15px 30px; background: var(--card-bg); border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 9997;}
    .profile-left { display: flex; align-items: center; gap: 15px; }
    .profile-left img { width: 48px; height: 48px; border-radius: 50%; object-fit: cover; border: 2px solid #e2e8f0; }
    .profile-left h1 { margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-main); }
    .profile-left p { margin: 2px 0 0; color: var(--text-muted); font-size: 0.85rem; }
    
    .topbar-right { display: flex; align-items: center; gap: 20px; }
    .bus-info { text-align: right; margin-right: 15px; }
    .bus-info .label { display: block; font-size: 0.75rem; color: var(--text-muted); }
    .bus-info .value { font-size: 1rem; font-weight: 700; color: var(--primary); }
    
    .status-badge { display: flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; border: 1px solid #d1fae5; background: #ecfdf5; color: var(--success); font-weight: 600; font-size: 0.85rem; cursor: pointer;}
    .status-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--success); }
    
    .notification-bell { position: relative; font-size: 1.2rem; color: var(--text-main); cursor: pointer; }
    .notification-bell .badge { position: absolute; top: -5px; right: -5px; background: var(--danger); color: white; font-size: 0.65rem; padding: 2px 5px; border-radius: 10px; border: 2px solid white; font-weight: bold; }
    
    .dashboard-wrapper { padding: 24px 30px; max-width: 1600px; margin: 0 auto; }
    
    /* Current Route Card */
    .route-summary-card { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border); padding: 20px; margin-bottom: 24px; }
    .route-summary-card h2 { margin: 0 0 5px; font-size: 0.85rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase; }
    .route-summary-card .route-title { font-size: 1.4rem; font-weight: 700; color: var(--text-main); margin-bottom: 20px; }
    .stats-flex { display: flex; gap: 40px; align-items: center; flex-wrap: wrap; }
    .stat-item { display: flex; align-items: center; gap: 12px; }
    .stat-item .icon { width: 36px; height: 36px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.1rem; }
    .stat-item .label { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 3px; }
    .stat-item .val { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
    
    /* 3 Column Grid */
    .grid-container { display: grid; grid-template-columns: 350px 1fr 320px; gap: 24px; align-items: start; }
    @media (max-width: 1400px) { .grid-container { grid-template-columns: 300px 1fr 300px; } }
    @media (max-width: 1200px) { .grid-container { grid-template-columns: 1fr; } }
    
    .card { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; }
    .card-header { padding: 15px 20px; border-bottom: 1px solid var(--border); font-weight: 700; font-size: 1.05rem; display: flex; justify-content: space-between; align-items: center; }
    
    .mobile-menu-toggle-custom { display: none; background: none; border: none; font-size: 22px; color: var(--text-main); cursor: pointer; padding: 5px; }
    @media (max-width: 1024px) {
        .mobile-menu-toggle-custom { display: block; }
        .driver-topbar { padding: 15px 20px; }
    }
    
    /* Left: Route Timeline */
    .timeline-wrap { padding: 20px; position: relative; max-height: 700px; overflow-y: auto; }
    .tl-line { position: absolute; left: 34px; top: 30px; bottom: 30px; width: 2px; background: #e2e8f0; z-index: 1; }
    .tl-stop { position: relative; z-index: 2; display: flex; gap: 15px; margin-bottom: 25px; cursor: pointer; }
    .tl-stop:last-child { margin-bottom: 0; }
    .tl-marker { width: 30px; height: 30px; border-radius: 50%; background: white; border: 2px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: #64748b; background: white; }
    
    .tl-stop.completed .tl-marker { background: var(--success); border-color: var(--success); color: white; }
    .tl-stop.current .tl-marker { background: var(--primary); border-color: var(--primary); color: white; }
    .tl-stop.destination .tl-marker { background: var(--danger); border-color: var(--danger); color: white; }
    
    .tl-content { flex: 1; }
    .tl-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px; }
    .tl-name { font-weight: 600; font-size: 0.95rem; color: var(--text-main); }
    .tl-status-badge { font-size: 0.7rem; font-weight: 600; padding: 2px 8px; border-radius: 12px; }
    .status-completed { background: #dcfce7; color: #16a34a; }
    .status-current { background: #1e40af; color: white; }
    .status-upcoming { color: var(--text-muted); }
    
    .tl-times { display: flex; justify-content: space-between; font-size: 0.8rem; color: var(--text-muted); }
    .tl-eta { color: var(--success); font-weight: 600; }
    .tl-dist { font-size: 0.75rem; color: var(--text-muted); }
    
    .btn-outline { display: block; width: calc(100% - 40px); margin: 0 20px 20px; padding: 10px; border: 1px solid var(--border); border-radius: 8px; text-align: center; color: var(--text-main); font-weight: 600; text-decoration: none; font-size: 0.9rem; }
    
    /* Center: Map */
    .map-card { padding: 0; position: relative; z-index: 1; height: 500px; min-height: 500px; overflow: hidden; }
    #routeMap { width: 100%; height: 100%; position: absolute; top: 0; left: 0; z-index: 1; }
    .map-legend { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); background: white; padding: 10px 20px; border-radius: 30px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); display: flex; gap: 15px; font-size: 0.75rem; font-weight: 600; z-index: 400; }
    .legend-item { display: flex; align-items: center; gap: 6px; }
    .legend-dot { width: 10px; height: 10px; border-radius: 50%; }
    
    /* Right: Actions */
    .right-col { display: flex; flex-direction: column; gap: 24px; }
    
    .next-stop-card { padding: 20px; }
    .ns-header { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 15px; }
    .ns-tag { background: #dcfce7; color: #16a34a; padding: 2px 8px; border-radius: 10px; font-size: 0.7rem; }
    .ns-location { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
    .ns-icon { width: 40px; height: 40px; border-radius: 50%; background: #eff6ff; color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    .ns-name { font-size: 1.2rem; font-weight: 700; color: var(--text-main); }
    
    .ns-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px; }
    .ns-stat-box .label { font-size: 0.75rem; color: var(--text-muted); margin-bottom: 4px; }
    .ns-stat-box .val { font-size: 0.95rem; font-weight: 700; color: var(--text-main); }
    .ns-stat-box .val.eta { color: var(--success); }
    
    .btn-primary { width: 100%; padding: 12px; background: #0a192f; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
    .btn-primary:hover { background: #1e293b; }
    
    .delay-card { background: #fffbeb; border: 1px solid #fde68a; border-radius: 12px; padding: 20px; }
    .delay-header { display: flex; gap: 15px; margin-bottom: 15px; }
    .delay-icon { font-size: 2rem; color: #f59e0b; }
    .delay-text h3 { margin: 0 0 4px; font-size: 1rem; color: #92400e; font-weight: 700; }
    .delay-text p { margin: 0; font-size: 0.8rem; color: #b45309; line-height: 1.4; }
    .btn-danger { width: 100%; padding: 12px; background: #dc2626; color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; }
    
    .summary-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding: 20px; }
    .sum-box { border: 1px solid var(--border); border-radius: 10px; padding: 15px; display: flex; align-items: center; gap: 12px; }
    .sum-icon { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1rem; }
    .sum-box h4 { margin: 0; font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }
    .sum-box .val { margin-top: 2px; font-size: 1.1rem; font-weight: 700; color: var(--text-main); }
    .sum-icon.blue { background: #eff6ff; color: #3b82f6; }
    .sum-icon.green { background: #dcfce7; color: #10b981; }
    .sum-icon.red { background: #fef2f2; color: #ef4444; }
    .sum-icon.yellow { background: #fef3c7; color: #f59e0b; }
    
    /* Delay Modal Styles */
    .modal-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); z-index: 1000; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
    .modal-overlay.show { display: flex; }
    .delay-modal { background: white; border-radius: 16px; width: 90%; max-width: 450px; padding: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); transform: translateY(20px); opacity: 0; transition: all 0.3s ease; }
    .modal-overlay.show .delay-modal { transform: translateY(0); opacity: 1; }
    .dm-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .dm-header h3 { margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text-main); display: flex; align-items: center; gap: 10px; }
    .dm-header h3 i { color: #f59e0b; }
    .dm-close { background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer; }
    .dm-close:hover { color: var(--danger); }
    .dm-form-group { margin-bottom: 15px; }
    .dm-form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: var(--text-main); margin-bottom: 8px; }
    .dm-form-group input, .dm-form-group textarea { width: 100%; padding: 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.95rem; font-family: inherit; }
    .dm-form-group input:focus, .dm-form-group textarea:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(10, 25, 47, 0.1); }
    .dm-actions { display: flex; gap: 10px; margin-top: 24px; }
    .dm-actions button { flex: 1; padding: 12px; border-radius: 8px; font-weight: 600; cursor: pointer; border: none; font-size: 0.95rem; transition: all 0.2s; }
    .dm-btn-cancel { background: #f1f5f9; color: var(--text-main); }
    .dm-btn-cancel:hover { background: #e2e8f0; }
    .dm-btn-submit { background: var(--danger); color: white; }
    .dm-btn-submit:hover { background: #dc2626; }

    /* Auto Tracker Pulse */
    @keyframes pulse-signal { 0% { opacity: 1; } 50% { opacity: 0.5; transform: scale(0.9); } 100% { opacity: 1; } }
    .auto-track-indicator { background: #f0fdf4; border: 1px solid #bbf7d0; color: #15803d; padding: 12px; border-radius: 8px; text-align: center; margin-bottom: 12px; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; }
    .auto-track-indicator i { animation: pulse-signal 2s infinite; color: #22c55e; }

    /* Mobile Responsiveness Enhancements */
    @media (max-width: 768px) {
        .dashboard-wrapper { padding: 15px; }
        
        /* Topbar Mobile */
        .driver-topbar { flex-direction: column; align-items: stretch; gap: 15px; padding: 15px; }
        .topbar-right { flex-wrap: wrap; justify-content: space-between; gap: 10px; border-top: 1px solid var(--border); padding-top: 15px; }
        .real-time-clock { border-right: none !important; padding-right: 0 !important; margin-right: 0 !important; text-align: left; flex: 1 1 40%; }
        .bus-info { text-align: right; flex: 1 1 40%; margin-right: 0; }
        .status-badge { flex: 1 1 100%; justify-content: center; padding: 10px; }
        .notification-bell { position: absolute; top: 20px; right: 20px; }
        .profile-left h1 { font-size: 1rem; }
        .profile-left img { width: 40px; height: 40px; }
        
        /* Route Summary Cards */
        .route-summary-card .route-title { font-size: 1.1rem; }
        .stats-flex { grid-template-columns: 1fr 1fr; display: grid; gap: 15px; }
        .stat-item { flex-direction: column; align-items: flex-start; gap: 5px; background: #f8fafc; padding: 10px; border-radius: 8px; }
        .stat-item .icon { width: 28px; height: 28px; font-size: 0.9rem; }
        
        /* Layout Grid */
        .grid-container { gap: 15px; }
        
        /* Next Stop Card & Summary Grid */
        .ns-stats { grid-template-columns: 1fr; gap: 10px; }
        .ns-stat-box { display: flex; justify-content: space-between; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; }
        .summary-grid { grid-template-columns: 1fr; }
        
        /* Map Adjustments */
        .map-card { height: 350px; min-height: 350px; }
        .map-legend { width: 90%; flex-wrap: wrap; justify-content: center; bottom: 10px; padding: 8px 12px; gap: 10px; }
        
        /* Delay Modal */
        .delay-modal { width: 95%; padding: 20px; }
    }
</style>

@php
    $bus = $driver->bus;
    $route = $bus ? $bus->busRoute : null;
    $stops = $route ? $route->stops : collect([]);
    
    // Journey state
    $hasStarted = $bus
    && $bus->journey_status !== 'Completed'
    && ($bus->journey_status === 'Started' || $bus->journey_start_time);
    $delayMins = $bus ? $bus->journey_delay_minutes : 0;
    
    // Figure out current stop and completed stops
    $passedCurrent = false;
    $completedCount = 0;
    $nextStop = null;
    $nextStopIndex = -1;
    $isDestination = false;
    
    foreach($stops as $idx => $stop) {
        $isCurrent = $bus && $bus->current_stop_id == $stop->id;
        if ($isCurrent) $passedCurrent = true;
        
        $isCompleted = !$passedCurrent && !$isCurrent && $bus && $bus->current_stop_id;
        if ($isCompleted) $completedCount++;
    }
    
    if ($bus && $bus->journey_status === 'Completed') {
        $completedCount = count($stops);
    }
    
    // Find current stop index based on database
    $currIdx = -1;
    foreach($stops as $idx => $stop) {
        if ($bus && $bus->current_stop_id == $stop->id) {
            $currIdx = $idx;
            break;
        }
    }

    if ($currIdx !== -1) {
        // A current stop is set
        if ($currIdx + 1 < count($stops)) {
            $nextStop = $stops[$currIdx + 1];
            $nextStopIndex = $currIdx + 2;
        } else {
            $nextStop = $stops[$currIdx]; // Reached destination
            $nextStopIndex = $currIdx + 1;
            $isDestination = true;
        }
    } else {
        // No current stop set
        // If journey is completed, do not restart from the first stop
        if ($bus && $bus->journey_status === 'Completed') {
            $nextStop = null;
            $nextStopIndex = -1;
            $isDestination = true;
        } elseif (count($stops) > 0) {
            $nextStop = $stops[0];
            $nextStopIndex = 1;
        }
    }
@endphp

<!-- Topbar -->
<div class="driver-topbar">
   <div style="display: flex; align-items: center; gap: 15px;">
       <button class="mobile-menu-toggle-custom">
           <i class="fa-solid fa-bars"></i>
       </button>
       <div class="profile-left">
          <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name ?? 'Driver') }}&background=1e293b&color=fff&size=128" alt="Profile" />
          <div>
             <h1>Hello, {{ $driver->name }}</h1>
             <p>Have a safe trip!</p>
          </div>
       </div>
   </div>
   <div class="topbar-right">
      <div class="real-time-clock" style="margin-right: 20px; text-align: right; border-right: 1px solid var(--border); padding-right: 20px;">
          <div class="label" style="font-size: 0.75rem; color: var(--text-muted); margin-bottom: 2px;">Current Time</div>
          <div class="value" id="liveClock" style="font-size: 1rem; font-weight: 700; color: var(--text-main);">--:-- --</div>
      </div>
      <div class="bus-info">
         <span class="label">Bus Number</span>
         <span class="value">{{ $bus ? $bus->bus_number : 'Not Assigned' }}</span>
      </div>
      <div class="status-badge" onclick="updateJourney('{{ $hasStarted ? 'Completed' : 'Started' }}')">
          <div class="status-dot"></div> {{ $hasStarted ? 'On Duty' : 'Start Duty' }}
      </div>
        <div style="position: relative;">
          <div class="notification-bell" onclick="window.toggleDropdown('notificationDropdownDashboard')">
              <i class="fa-regular fa-bell"></i>
              @if($assignedRestaurantsCount > 0)
                  <div class="badge">{{ $assignedRestaurantsCount }}</div>
              @endif
          </div>
          <div class="dropdown-menu" id="notificationDropdownDashboard">
              <div class="dropdown-header">
                  Notifications
                  <a href="{{ route('driver.notifications.index') }}">View All</a>
              </div>
              <div class="dropdown-list">
                  @if($assignedRestaurantsCount > 0)
                      <a href="{{ route('driver.orders.index') }}" class="dropdown-item">
                          <div class="dropdown-item-icon" style="background:#dcfce7; color:#16a34a;"><i class="fa-solid fa-utensils"></i></div>
                          <div class="dropdown-item-content">
                              <h4>{{ $assignedRestaurantsCount }} Restaurant(s) Assigned</h4>
                              <p>You have {{ $assignedRestaurantsCount }} restaurants assigned to your route.</p>
                              <span class="dropdown-item-time">Just now</span>
                          </div>
                      </a>
                  @else
                      <div class="dropdown-empty">
                          <i class="fa-solid fa-bell-slash" style="font-size:24px; margin-bottom:10px; color:#cbd5e1;"></i>
                          <p>No new notifications</p>
                      </div>
                  @endif
              </div>
          </div>
      </div>
   </div>
</div>

<div class="dashboard-wrapper">

    <!-- Current Route -->
    <div class="route-summary-card">
        <h2>Current Route</h2>
        <div class="route-title">
            {{ $route ? $route->source . ' → ' . $route->destination : 'No Route Assigned' }}
        </div>
        <div class="stats-flex">
            <div class="stat-item">
                <div class="icon"><i class="fa-solid fa-route"></i></div>
                <div>
                    <div class="label">Total Distance</div>
                    <div class="val">{{ $route ? $route->distance : '0 km' }}</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="icon"><i class="fa-solid fa-map-pin"></i></div>
                <div>
                    <div class="label">Total Stops</div>
                    <div class="val">{{ count($stops) }}</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="icon"><i class="fa-regular fa-clock"></i></div>
                <div>
                    <div class="label">Estimated Duration</div>
                    <div class="val">1 hr 20 mins</div> <!-- Placeholder for duration -->
                </div>
            </div>
            <div class="stat-item">
                <div class="icon"><i class="fa-solid fa-hourglass-start"></i></div>
                <div>
                    <div class="label">Scheduled Departure</div>
                    <div class="val">{{ $bus && $bus->departure_time ? \Carbon\Carbon::parse($bus->departure_time)->format('h:i A') : 'N/A' }}</div>
                </div>
            </div>
            <div class="stat-item">
                <div class="icon"><i class="fa-solid fa-flag-checkered"></i></div>
                <div>
                    <div class="label">ETA at Destination</div>
                    <div class="val" style="color:var(--success);">
                        {{ count($stops) > 0 ? \Carbon\Carbon::parse($stops->last()->arrival_time)->addMinutes($delayMins)->format('h:i A') : 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($route)
    <div class="grid-container">
        
        <!-- Left: Route Timeline -->
        <div class="card">
            <div class="card-header">Route & Stops</div>
            <div class="timeline-wrap">
                <div class="tl-line"></div>
                
                @php
                    $passedCurrentTL = false;
                @endphp
                @foreach($stops as $index => $stop)
                    @php
                        $isCurrentTL = $bus && $bus->current_stop_id == $stop->id;
                        if ($isCurrentTL) $passedCurrentTL = true;
                        
                        $isCompletedTL = ($bus && $bus->journey_status === 'Completed')
                            || (!$passedCurrentTL && $bus && $bus->current_stop_id && !$isCurrentTL);
                        $isDestTL = ($index === count($stops) - 1);
                        
                        $statusClass = '';
                        $statusText = '';
                        $statusBadgeClass = '';
                        
                        if ($isCompletedTL) {
                            $statusClass = 'completed';
                            $statusText = 'Completed';
                            $statusBadgeClass = 'status-completed';
                        } elseif ($isCurrentTL) {
                            $statusClass = 'current';
                            $statusText = 'Current Stop';
                            $statusBadgeClass = 'status-current';
                        } elseif ($isDestTL) {
                            $statusClass = 'destination';
                            $statusText = 'Destination';
                            $statusBadgeClass = 'status-upcoming'; // Red dot but text is greyish
                        } else {
                            $statusClass = 'upcoming';
                            $statusText = 'Upcoming';
                            $statusBadgeClass = 'status-upcoming';
                        }
                    @endphp
                    <div class="tl-stop {{ $statusClass }}">
                        <div class="tl-marker">{{ $index + 1 }}</div>
                        <div class="tl-content">
                            <div class="tl-header">
                                <div class="tl-name">{{ $stop->stop_name }}</div>
                                <div class="tl-status-badge {{ $statusBadgeClass }}">{{ $statusText }}</div>
                            </div>
                            <div class="tl-times">
                                <div>
                                    <div style="font-size:0.7rem;">{{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-' }}</div>
                                </div>
                                <div class="tl-dist">
                                    @if($isCompletedTL)
                                        {{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-' }}
                                    @else
                                        <span class="tl-eta">{{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->addMinutes($delayMins)->format('h:i A') : '-' }}</span>
                                    @endif
                                    &nbsp;&nbsp;{{ $stop->distance_from_start ?? '0' }} km
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="#" class="btn-outline"><i class="fa-regular fa-calendar-days"></i> View Full Timeline</a>
        </div>

        <!-- Center: Map -->
        <div class="card map-card">
            <div id="routeMap"></div>
            <div class="map-legend">
                <div class="legend-item"><div class="legend-dot" style="background:var(--success);"></div> Completed</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--primary);"></div> Current Stop</div>
                <div class="legend-item"><div class="legend-dot" style="background:#cbd5e1;"></div> Upcoming</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--danger);"></div> Destination</div>
            </div>
        </div>

        <!-- Right: Actions -->
        <div class="right-col">
            
            <!-- Next Stop -->
            <div class="card next-stop-card">
                <div class="ns-header">
                    Next Stop
                    @if($nextStop && !($bus && $bus->journey_status === 'Completed'))
                        <span class="ns-tag">{{ $nextStopIndex }}{{ $nextStopIndex == 1 ? 'st' : ($nextStopIndex == 2 ? 'nd' : ($nextStopIndex == 3 ? 'rd' : 'th')) }} Stop</span>
                    @endif
                </div>
                <div class="ns-location">
                    <div class="ns-icon"><i class="fa-solid fa-location-dot"></i></div>
                    <div class="ns-name">{{ $nextStop ? $nextStop->stop_name : 'N/A' }}</div>
                </div>
                <div class="ns-stats">
                    <div class="ns-stat-box">
                        <div class="label">Scheduled Arrival</div>
                        <div class="val">{{ $nextStop && $nextStop->arrival_time ? \Carbon\Carbon::parse($nextStop->arrival_time)->format('h:i A') : '-' }}</div>
                    </div>
                    <div class="ns-stat-box">
                        <div class="label">ETA</div>
                        <div class="val eta">{{ $nextStop && $nextStop->arrival_time ? \Carbon\Carbon::parse($nextStop->arrival_time)->addMinutes($delayMins)->format('h:i A') : '-' }}</div>
                    </div>
                    <div class="ns-stat-box">
                        <div class="label">Distance</div>
                        <div class="val">{{ $nextStop ? $nextStop->distance_from_start : '0' }} km</div>
                    </div>
                    <div class="ns-stat-box">
                        <div class="label">Stay Duration</div>
                        <div class="val">{{ $nextStop ? $nextStop->stay_duration_mins : '0' }} mins</div>
                    </div>
                </div>
                @if($nextStop)
                    @if($hasStarted)
                        <div class="auto-track-indicator" style="flex-direction: column; padding: 15px;">
                            <div><i class="fa-solid fa-satellite-dish"></i> GPS Auto-Tracking Active</div>
                            <div style="font-size:0.7rem; font-weight:normal; margin-top:4px; color: #166534;">System will automatically {{ $isDestination ? 'complete journey' : 'reach stop' }} at ETA</div>
                        </div>
                    @else
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; padding: 15px; border-radius: 8px; text-align: center; margin-bottom: 12px; font-size: 0.85rem; font-weight: 600;">
                            <i class="fa-solid fa-power-off"></i> Start Duty to begin tracking
                        </div>
                    @endif
                @endif
            </div>
            <!-- Assigned Restaurants -->
            <div class="card">
                <div class="card-header">
                    Restaurant Orders
                </div>
                <div style="padding: 15px;">
                    @forelse($assignedHotels as $hotel)
                        <div style="border: 1px solid var(--border); border-radius: 8px; padding: 12px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h4 style="margin: 0 0 4px; font-size: 0.95rem; color: var(--text-main);">{{ $hotel->restaurant_name }}</h4>
                                <div style="font-size: 0.75rem; color: var(--text-muted);"><i class="fa-solid fa-clock"></i> ETA: <span style="color: var(--success); font-weight: 600;">{{ $hotel->reaching_time }}</span></div>
                            </div>
                            <div style="background: #eff6ff; color: #2563eb; padding: 8px 12px; border-radius: 6px; text-align: center;">
                                <div style="font-size: 1.1rem; font-weight: 700;">{{ $hotel->active_orders_count }}</div>
                                <div style="font-size: 0.65rem; text-transform: uppercase; font-weight: 600;">Orders</div>
                            </div>
                        </div>
                    @empty
                        <div style="text-align: center; color: var(--text-muted); font-size: 0.85rem; padding: 10px 0;">No restaurants assigned.</div>
                    @endforelse
                </div>
            </div>
            <!-- Delay Management -->
            <div class="delay-card">
                <div class="delay-header">
                    <div class="delay-icon"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div class="delay-text">
                        @if($delayMins > 0)
                            <h3 style="color: #dc2626;">Delayed by {{ $delayMins }} mins</h3>
                            <p>Arrival times have been adjusted. Need to update this delay?</p>
                        @elseif($delayMins < 0)
                            <h3 style="color: #16a34a;">Running Early by {{ abs($delayMins) }} mins</h3>
                            <p>Arrival times have been adjusted. Need to update your status?</p>
                        @else
                            <h3>Facing Delay / Early?</h3>
                            <p>Let us know to update arrival times for customers and vendors.</p>
                        @endif
                    </div>
                </div>
                <button class="btn-danger" style="background: {{ $delayMins < 0 ? '#16a34a' : ($delayMins > 0 ? '#dc2626' : '#0a192f') }}" onclick="openDelayModal({{ $delayMins }})">{{ $delayMins != 0 ? 'Update Status' : 'Report Time Change' }}</button>
            </div>
            
            <!-- Today's Summary -->
            <div class="card">
                <div class="card-header">
                    Today's Summary
                </div>
                <div class="summary-grid">
                    <div class="sum-box">
                        <div class="sum-icon blue"><i class="fa-solid fa-location-dot"></i></div>
                        <div>
                            <h4>Total Stops</h4>
                            <div class="val">{{ count($stops) }}</div>
                        </div>
                    </div>
                    <div class="sum-box">
                        <div class="sum-icon green"><i class="fa-solid fa-check-circle"></i></div>
                        <div>
                            <h4>Completed</h4>
                            <div class="val">{{ $completedCount }}</div>
                        </div>
                    </div>
                    <div class="sum-box">
                        <div class="sum-icon green"><i class="fa-regular fa-clock"></i></div>
                        <div>
                            <h4>On Time</h4>
                            <div class="val">{{ $delayMins > 0 ? 'No' : 'Yes' }}</div>
                        </div>
                    </div>
                    <div class="sum-box">
                        <div class="sum-icon {{ $delayMins > 0 ? 'red' : ($delayMins < 0 ? 'green' : 'blue') }}"><i class="fa-solid fa-stopwatch"></i></div>
                        <div>
                            <h4>Time Offset</h4>
                            <div class="val" style="color:{{ $delayMins > 0 ? 'var(--danger)' : ($delayMins < 0 ? 'var(--success)' : 'var(--text-main)') }};">
                                {{ $delayMins == 0 ? 'On Time' : ($delayMins > 0 ? $delayMins . ' mins late' : abs($delayMins) . ' mins early') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
    
    <div style="background: #e0e7ff; color: #3730a3; padding: 15px 20px; border-radius: 10px; margin-top: 24px; display: flex; align-items: center; gap: 15px;">
        <div style="width: 30px; height: 30px; border-radius: 50%; background: #3730a3; color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">i</div>
        <div style="font-size: 0.9rem; font-weight: 500;">Follow the schedule and ensure timely delivery of orders. Your customers and vendors are tracking your journey.</div>
    </div>
    @else
    <div class="card" style="padding: 60px; text-align: center;">
        <i class="fa-solid fa-route" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px;"></i>
        <h2>No Route Assigned</h2>
        <p style="color: var(--text-muted);">You are currently not assigned to any active route.</p>
    </div>
    @endif
</div>

<!-- Delay Modal -->
<div class="modal-overlay" id="delayModalOverlay">
    <div class="delay-modal">
        <div class="dm-header">
            <h3><i class="fa-solid fa-clock-rotate-left"></i> Report Time Change</h3>
            <button class="dm-close" onclick="closeDelayModal()"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px;">
            Reporting a time change will automatically update the estimated arrival times for all upcoming stops and notify customers.
        </p>
        <div class="dm-form-group">
            <label>Type of Time Change</label>
            <div style="display: flex; gap: 15px;">
                <label style="font-weight: 500;"><input type="radio" name="delayType" value="delay" checked> Running Late</label>
                <label style="font-weight: 500;"><input type="radio" name="delayType" value="early"> Running Early</label>
            </div>
        </div>
        <div class="dm-form-group">
            <label for="delayMinutes">Duration (in minutes)</label>
            <input type="number" id="delayMinutes" placeholder="e.g. 15" min="1" required>
        </div>
        <div class="dm-form-group">
            <label for="delayReason">Reason for Delay (Optional)</label>
            <textarea id="delayReason" rows="3" placeholder="e.g. Heavy traffic, Vehicle issue, etc."></textarea>
        </div>
        <div class="dm-actions">
            <button class="dm-btn-cancel" onclick="closeDelayModal()">Cancel</button>
            <button class="dm-btn-submit" onclick="submitDelay()">Submit Update</button>
        </div>
    </div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // AJAX Functions
    function updateJourney(status) {
        if(!confirm('Update journey status to "' + status + '"?')) return;
        
        fetch('{{ route('driver.journey.status') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload(); 
            } else {
                alert(data.message || 'Failed to update status.');
            }
        })
        .catch(err => console.error(err));
    }

    function markStopReached(stopId, stopName) {
        if(!confirm('Mark "' + stopName + '" as reached?')) return;
        
        fetch('{{ route('driver.route.reach-stop') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ stop_id: stopId })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to update stop.');
            }
        })
        .catch(err => console.error(err));
    }

    // Modal Logic
    function openDelayModal(currentDelay = 0) {
        document.getElementById('delayModalOverlay').classList.add('show');
        
        // Pre-fill the modal with the current state if a delay/early already exists
        if (currentDelay < 0) {
            document.querySelector('input[name="delayType"][value="early"]').checked = true;
            document.getElementById('delayMinutes').value = Math.abs(currentDelay);
        } else if (currentDelay > 0) {
            document.querySelector('input[name="delayType"][value="delay"]').checked = true;
            document.getElementById('delayMinutes').value = currentDelay;
        } else {
            document.querySelector('input[name="delayType"][value="delay"]').checked = true;
            document.getElementById('delayMinutes').value = '';
        }
    }

    function closeDelayModal() {
        document.getElementById('delayModalOverlay').classList.remove('show');
        document.getElementById('delayMinutes').value = '';
        document.getElementById('delayReason').value = '';
    }

    function submitDelay() {
        let minutes = document.getElementById('delayMinutes').value;
        let reason = document.getElementById('delayReason').value;
        let type = document.querySelector('input[name="delayType"]:checked').value;
        
        if (!minutes || minutes <= 0) {
            alert('Please enter a valid number of minutes.');
            return;
        }

        fetch('{{ route('driver.route.report-delay') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ minutes: minutes, reason: reason, type: type })
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                location.reload();
            } else {
                alert(data.message || 'Failed to report delay.');
            }
        })
        .catch(err => console.error(err));
    }

    // Automated ETA Tracking
    @if($hasStarted && $nextStop)
        const nextStopId = {{ $nextStop->id }};
        const isDest = {{ $isDestination ? 'true' : 'false' }};
        const nextStopEta = {{ \Carbon\Carbon::parse($nextStop->arrival_time)->addMinutes($delayMins)->timestamp * 1000 }};
        let isAutoReaching = false;

        setInterval(() => {
            if (isAutoReaching) return;
            if (Date.now() >= nextStopEta) {
                isAutoReaching = true;
                
                if (isDest) {
                    console.log("Auto-tracking: Reached final destination");
                    fetch('{{ route('driver.journey.status') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ status: 'Completed' })
                    }).then(r => r.json()).then(d => { if(d.success) location.reload(); else isAutoReaching = false; });
                } else {
                    console.log("Auto-tracking triggered: Reached stop " + nextStopId);
                    fetch('{{ route('driver.route.reach-stop') }}', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        body: JSON.stringify({ stop_id: nextStopId, auto: true })
                    }).then(r => r.json()).then(d => { if(d.success) location.reload(); else isAutoReaching = false; });
                }
            }
        }, 10000); // Check every 10 seconds
    @endif

    // Live Clock
    function updateLiveClock() {
        const now = new Date();
        const clockEl = document.getElementById('liveClock');
        if (clockEl) {
            clockEl.innerText = now.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', second:'2-digit'});
        }
    }
    setInterval(updateLiveClock, 1000);
    updateLiveClock();

    // Leaflet Map Initialization
    document.addEventListener('DOMContentLoaded', function() {
        @if($route && count($stops) > 0)
            const map = L.map('routeMap').setView([17.6868, 83.2185], 11); 
            
              L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                  attribution: '&copy; OpenStreetMap contributors',
                  maxZoom: 19
              }).addTo(map);

            const stopsData = [
                @php
                    $passedCurrentMap = false;
                @endphp
                @foreach($stops as $index => $stop)
                    @php
                        $isCurrentMap = $bus && $bus->current_stop_id == $stop->id;
                        if ($isCurrentMap) $passedCurrentMap = true;
                        
                        $isCompletedMap = !$passedCurrentMap && $bus && $bus->current_stop_id && !$isCurrentMap;
                        $isDestMap = ($index === count($stops) - 1);
                        
                        $mapStatus = 'upcoming';
                        if ($isCompletedMap) $mapStatus = 'completed';
                        elseif ($isCurrentMap) $mapStatus = 'current';
                        elseif ($isDestMap) $mapStatus = 'destination';
                    @endphp
                {
                    name: '{{ $stop->stop_name }}',
                    status: '{{ $mapStatus }}' 
                },
                @endforeach
            ];

            // Fetch real coordinates dynamically using OpenStreetMap Nominatim
            async function getRealCoordinates() {
                let latlngs = [];
                let mapMarkers = [];

                for (let i = 0; i < stopsData.length; i++) {
                    let stop = stopsData[i];
                    try {
                        let resp = await fetch(`https://nominatim.openstreetmap.org/search?format=json&limit=1&q=${encodeURIComponent(stop.name + ', Visakhapatnam, Andhra Pradesh, India')}`);
                        let data = await resp.json();
                        
                        let lat, lng;
                        if (data && data.length > 0) {
                            lat = parseFloat(data[0].lat);
                            lng = parseFloat(data[0].lon);
                        } else {
                            // Fallback to dummy offset from center if not found
                            let coord = [17.6868, 83.2185];
                            lat = coord[0] + (i * 0.02);
                            lng = coord[1] - (i * 0.015);
                        }
                        
                        latlngs.push([lat, lng]);
                        mapMarkers.push({ lat, lng, stop, index: i });
                        
                    } catch(e) {
                        // Fallback
                        let coord = [17.6868, 83.2185];
                        latlngs.push([coord[0] + (i * 0.02), coord[1] - (i * 0.015)]);
                        mapMarkers.push({ lat: coord[0] + (i * 0.02), lng: coord[1] - (i * 0.015), stop, index: i });
                    }
                }

                // Plot markers
                mapMarkers.forEach(m => {
                    let color = '#94a3b8'; // Default grey for upcoming
                    if (m.stop.status === 'completed') color = '#10b981';
                    else if (m.stop.status === 'current') color = '#2563eb';
                    else if (m.stop.status === 'destination') color = '#ef4444';
                    
                    let markerHtml = '';
                    if (m.stop.status === 'current') {
                        markerHtml = `
                            <div style="position: relative; width: 32px; height: 60px; display: flex; flex-direction: column; align-items: center; justify-content: flex-end;">
                                <div style="background: white; color: #2563eb; width: 32px; height: 32px; border-radius: 50%; border: 2px solid #2563eb; display: flex; align-items: center; justify-content: center; font-size: 14px; box-shadow: 0 4px 6px rgba(0,0,0,0.2); position: absolute; top: 0; z-index: 10;">
                                    <i class="fa-solid fa-bus"></i>
                                </div>
                                <div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.3); z-index: 5;">
                                    ${m.index + 1}
                                </div>
                            </div>
                        `;
                    } else {
                        markerHtml = `
                            <div style="background-color: ${color}; width: 24px; height: 24px; border-radius: 50%; border: 2px solid white; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.3);">
                                ${m.index + 1}
                            </div>
                        `;
                    }
                    
                    let iconSize = m.stop.status === 'current' ? [32, 60] : [24, 24];
                    let iconAnchor = m.stop.status === 'current' ? [16, 60] : [12, 12];

                    let customIcon = L.divIcon({
                        html: markerHtml,
                        className: '',
                        iconSize: iconSize,
                        iconAnchor: iconAnchor
                    });
                    
                    L.marker([m.lat, m.lng], {icon: customIcon}).addTo(map).bindPopup(m.stop.name);
                });

                // Draw a styled polyline
                if (latlngs.length > 1) {
                    let completedLatLngs = [];
                    let upcomingLatLngs = [];

                    stopsData.forEach((stop, i) => {
                        if (stop.status === 'completed' || stop.status === 'current') {
                            completedLatLngs.push(latlngs[i]);
                        }
                        if (stop.status === 'current' || stop.status === 'upcoming' || stop.status === 'destination') {
                            upcomingLatLngs.push(latlngs[i]);
                        }
                    });

                    if (completedLatLngs.length > 1) {
                        L.polyline(completedLatLngs, {color: '#10b981', weight: 4, opacity: 0.8}).addTo(map);
                    }
                    if (upcomingLatLngs.length > 1) {
                        L.polyline(upcomingLatLngs, {color: '#94a3b8', weight: 4, opacity: 0.8, dashArray: '5, 10'}).addTo(map);
                    }

                    map.fitBounds(L.polyline(latlngs).getBounds(), {padding: [50, 50]});
                }
            }
            
            getRealCoordinates();
        @endif
    });
</script>
@endsection
