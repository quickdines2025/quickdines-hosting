@extends('layouts.admin')
@section('title', 'Route Details - ' . $route->name)

@section('content')
<style>
    :root {
        --primary: #2563eb;
        --secondary: #64748b;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --border: #e2e8f0;
        --text-dark: #0f172a;
        --text-muted: #64748b;
        --bg-light: #f8fafc;
        --card-bg: #ffffff;
    }
    
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .page-header h1 { font-size: 1.25rem; font-weight: 700; color: var(--text-dark); margin: 0; display: flex; align-items: center; gap: 12px; }
    .page-header .date-badge { display: flex; align-items: center; gap: 8px; font-size: 0.85rem; font-weight: 600; color: var(--text-dark); border: 1px solid var(--border); padding: 8px 16px; border-radius: 8px; background: white; }
    
    .card-custom { background: var(--card-bg); border-radius: 12px; border: 1px solid var(--border); box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow: hidden; margin-bottom: 24px; }
    .card-title { font-size: 1rem; font-weight: 700; color: var(--text-dark); padding: 16px 20px; margin: 0; border-bottom: 1px solid var(--border); }
    
    /* Route Info */
    .route-info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; padding: 20px; }
    .info-label { font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-bottom: 4px; }
    .info-value { font-size: 0.95rem; font-weight: 600; color: var(--text-dark); }
    
    .driver-box { display: flex; align-items: center; gap: 12px; }
    .driver-avatar { width: 36px; height: 36px; border-radius: 50%; object-fit: cover; }
    
    /* Stats */
    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; padding: 20px; }
    .stat-box { display: flex; align-items: center; gap: 15px; border: 1px solid var(--border); border-radius: 10px; padding: 16px; }
    .stat-icon { width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; }
    
    /* Table */
    .table-custom { width: 100%; border-collapse: collapse; }
    .table-custom th { font-size: 0.75rem; font-weight: 700; color: var(--text-dark); padding: 12px 20px; border-bottom: 1px solid var(--border); text-align: left; }
    .table-custom td { font-size: 0.85rem; font-weight: 500; color: var(--text-muted); padding: 12px 20px; border-bottom: 1px solid var(--border); }
    .table-custom tr:last-child td { border-bottom: none; }
    .status-badge { padding: 4px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; }
    .status-completed { color: var(--success); background: #ecfdf5; border: 1px solid #a7f3d0; }
    .status-current { color: var(--primary); background: #eff6ff; border: 1px solid #bfdbfe; }
    .status-upcoming { color: var(--primary); background: #f8fafc; border: 1px solid var(--border); }
    .status-delayed { color: var(--danger); background: #fef2f2; border: 1px solid #fecaca; }
    
    /* Delay Management */
    .delay-form { padding: 20px; }
    .delay-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-label { font-size: 0.8rem; font-weight: 600; color: var(--text-dark); margin-bottom: 8px; display: block; }
    .form-control-custom { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 8px; font-size: 0.85rem; color: var(--text-dark); }
    
    /* Map Tracker (Visual Mockup) */
    .map-container { position: relative; width: 100%; height: 400px; background: url('https://cdn-icons-png.flaticon.com/512/854/854866.png') no-repeat center center; background-size: cover; background-color: #f0fdf4; border-bottom: 1px solid var(--border); }
    .map-overlay { position: absolute; inset: 0; background: rgba(255,255,255,0.7); }
    .route-line { position: absolute; top: 50%; left: 10%; right: 10%; height: 4px; background: #cbd5e1; transform: translateY(-50%); z-index: 2; }
    .route-line-progress { position: absolute; top: 0; left: 0; height: 100%; background: var(--success); width: 60%; }
    .map-stops { position: absolute; top: 50%; left: 10%; right: 10%; transform: translateY(-50%); z-index: 3; display: flex; justify-content: space-between; }
    .m-stop { position: relative; }
    .m-stop-dot { width: 24px; height: 24px; border-radius: 50%; background: white; border: 3px solid #cbd5e1; display: flex; align-items: center; justify-content: center; font-size: 0.6rem; font-weight: bold; color: var(--text-dark); }
    .m-stop.completed .m-stop-dot { border-color: var(--success); background: var(--success); color: white; }
    .m-stop.current .m-stop-dot { border-color: var(--primary); background: var(--primary); color: white; width: 32px; height: 32px; transform: translate(-4px, -4px); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.2); }
    .m-stop.destination .m-stop-dot { border-color: var(--danger); background: var(--danger); color: white; }
    .m-stop-label { position: absolute; top: 100%; left: 50%; transform: translateX(-50%); margin-top: 8px; font-size: 0.75rem; font-weight: 600; color: var(--text-dark); white-space: nowrap; }
    
    .map-legend { display: flex; gap: 20px; justify-content: center; padding: 15px; background: white; }
    .legend-item { display: flex; align-items: center; gap: 8px; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); }
    .legend-dot { width: 8px; height: 8px; border-radius: 50%; }
    
    /* Route Timeline */
    .timeline-wrapper { display: flex; justify-content: space-between; padding: 30px 20px; position: relative; }
    .timeline-line { position: absolute; top: 35px; left: 40px; right: 40px; height: 2px; background: var(--border); z-index: 1; }
    .timeline-point { position: relative; z-index: 2; text-align: center; }
    .tl-dot { width: 12px; height: 12px; border-radius: 50%; background: white; border: 2px solid var(--border); margin: 0 auto 10px; }
    .timeline-point.on-time .tl-dot { border-color: var(--success); }
    .timeline-point.delayed .tl-dot { border-color: var(--danger); }
    .timeline-point.arrived .tl-dot { background: var(--text-dark); border-color: var(--text-dark); }
    
    /* Quick Actions */
    .quick-actions-grid { display: flex; flex-direction: column; gap: 12px; padding: 20px; }
    .btn-quick { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border: 1px solid var(--border); border-radius: 8px; background: white; color: var(--text-dark); font-weight: 600; font-size: 0.85rem; text-decoration: none; transition: all 0.2s; }
    .btn-quick:hover { border-color: var(--primary); background: #f8fafc; }
</style>

<div class="page-header">
    <h1>
        <a href="{{ route('admin.bus-routes.index') }}" class="text-dark"><i class="fa-solid fa-arrow-left"></i></a>
        Route Details
    </h1>
    <div style="display: flex; gap: 15px;">
        <div class="date-badge">
            <i class="fa-regular fa-calendar"></i> {{ date('10 M Y') }}
        </div>
        <button class="btn btn-primary" style="border-radius: 8px;"><i class="fa-solid fa-pen"></i> Edit Route</button>
        <button class="btn btn-light border"><i class="fa-solid fa-ellipsis-vertical"></i></button>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-7">
        
        <!-- Route Information -->
        <div class="card-custom">
            <h3 class="card-title">Route Information</h3>
            <div class="route-info-grid">
                <div>
                    <div class="info-label">Route Name</div>
                    <div class="info-value">{{ $route->name }}</div>
                </div>
                <div>
                    <div class="info-label">Total Distance</div>
                    <div class="info-value">{{ $route->distance ?? '0 km' }}</div>
                </div>
                <div>
                    <div class="info-label">Estimated Duration</div>
                    @php
                        $firstStop = $route->stops->first();
                        $lastStop = $route->stops->last();
                        $durationStr = 'N/A';
                        if($firstStop && $lastStop && $firstStop->arrival_time && $lastStop->arrival_time) {
                            $start = \Carbon\Carbon::parse($firstStop->arrival_time);
                            $end = \Carbon\Carbon::parse($lastStop->arrival_time);
                            $diff = $start->diff($end);
                            $durationStr = $diff->format('%h hr %i mins');
                        }
                    @endphp
                    <div class="info-value">{{ $durationStr }}</div>
                </div>
                
                <div style="grid-column: 1 / 2;">
                    <div class="info-label">Driver(s)</div>
                    @if($activeBus && $activeBus->drivers->count() > 0)
                        <div style="display: flex; flex-direction: column; gap: 8px; margin-top: 5px;">
                            @foreach($activeBus->drivers as $driver)
                                @if($driver->user)
                                <div class="driver-box">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->user->name ?? 'N A') }}&background=f1f5f9" class="driver-avatar">
                                    <div>
                                        <div class="info-value" style="font-size: 0.85rem;">{{ $driver->user->name ?? 'Unassigned' }}</div>
                                        <div class="info-label mb-0" style="font-size: 0.7rem;">{{ $driver->user->mobile ?? 'N/A' }}</div>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <div class="driver-box mt-1">
                            <img src="https://ui-avatars.com/api/?name=N+A&background=f1f5f9" class="driver-avatar">
                            <div>
                                <div class="info-value" style="font-size: 0.85rem;">Unassigned</div>
                                <div class="info-label mb-0" style="font-size: 0.7rem;">N/A</div>
                            </div>
                        </div>
                    @endif
                </div>
                <div style="display:flex; align-items:center;">
                    <div>
                        <div class="info-label">Bus Number</div>
                        <div class="info-value">{{ $activeBus->bus_number ?? 'N/A' }}</div>
                    </div>
                </div>
                <div style="display:flex; align-items:center;">
                    <div>
                        <div class="info-label">Status</div>
                        <div class="status-badge status-completed mt-1" style="display:inline-block;">Active</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Route Stops & Schedule -->
        <div class="card-custom">
            <div class="card-title d-flex justify-content-between align-items-center">
                Route Stops & Schedule
            </div>
            <div style="overflow-x: auto;">
                <table class="table-custom">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Stop Name</th>
                            <th>Arrival Time</th>
                            <th>Departure Time</th>
                            <th>Stay Duration</th>
                            <th>Distance</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $passedCurrent = false;
                        @endphp
                        @forelse($route->stops as $index => $stop)
                                @php
                                    $isCurrent = $activeBus && $activeBus->current_stop_id == $stop->id;
                                    if ($isCurrent) $passedCurrent = true;
                                    
                                    if ($isCurrent) {
                                        $statusClass = 'status-current';
                                        $statusText = 'Current Stop';
                                    } elseif (!$passedCurrent && $activeBus && $activeBus->current_stop_id) {
                                        $statusClass = 'status-completed';
                                        $statusText = 'Completed';
                                    } else {
                                        $statusClass = 'status-upcoming';
                                        $statusText = 'Upcoming';
                                    }
                                    
                                    $delayMins = $activeBus && $activeBus->journey_delay_minutes > 0 ? $activeBus->journey_delay_minutes : 0;
                                    
                                    if ($statusText == 'Completed' || $delayMins == 0) {
                                        $arrTimeDisplay = $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-';
                                        $depTimeDisplay = $stop->departure_time ? \Carbon\Carbon::parse($stop->departure_time)->format('h:i A') : '-';
                                    } else {
                                        $arrTimeDisplay = $stop->arrival_time ? '<del class="text-muted" style="font-size:0.75rem;">' . \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') . '</del><br><span class="text-danger">' . \Carbon\Carbon::parse($stop->arrival_time)->addMinutes($delayMins)->format('h:i A') . '</span>' : '-';
                                        $depTimeDisplay = $stop->departure_time ? '<del class="text-muted" style="font-size:0.75rem;">' . \Carbon\Carbon::parse($stop->departure_time)->format('h:i A') . '</del><br><span class="text-danger">' . \Carbon\Carbon::parse($stop->departure_time)->addMinutes($delayMins)->format('h:i A') . '</span>' : '-';
                                    }
                                @endphp
                                <tr>
                                    <td style="color:var(--text-dark);">{{ $stop->stop_sequence }}</td>
                                    <td style="color:var(--text-dark);">{{ $stop->stop_name }}</td>
                                    <td style="{{ $isCurrent ? 'color:var(--primary); font-weight:700;' : '' }}">{!! $arrTimeDisplay !!}</td>
                                    <td style="{{ $isCurrent ? 'color:var(--primary); font-weight:700;' : '' }}">{!! $depTimeDisplay !!}</td>
                                <td>{{ $stop->stay_duration_mins ? $stop->stay_duration_mins . ' mins' : '-' }}</td>
                                <td>{{ $stop->distance_from_start ? $stop->distance_from_start . ' km' : '0 km' }}</td>
                                <td>
                                    @if($isCurrent)
                                        <button class="btn btn-sm btn-primary" style="font-size: 0.7rem; padding: 4px 10px; border-radius: 6px;">Delay</button>
                                    @endif
                                    <form action="{{ route('admin.bus-routes.stops.destroy', $stop->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this stop?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm" style="color: #ef4444; background: rgba(239, 68, 68, 0.1); border: none; padding: 4px 8px; border-radius: 6px; font-size: 0.75rem;" title="Delete Stop">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center py-4">No stops found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="padding: 15px 20px; border-top: 1px solid var(--border);">
                <button class="btn btn-outline-primary btn-sm" style="font-weight:600; border-radius:8px;" data-bs-toggle="modal" data-bs-target="#addStopModal">
                    <i class="fa-solid fa-plus me-1"></i> Add Stop
                </button>
            </div>
        </div>
        
        <!-- Delay Management -->
        <div class="card-custom">
            <h3 class="card-title">Delay Management</h3>
            <form class="delay-form" action="{{ route('admin.bus-routes.delay', $route->id) }}" method="POST">
                @csrf
                @if($activeBus)
                    <input type="hidden" name="bus_id" value="{{ $activeBus->id }}">
                @endif
                <div class="delay-grid">
                    <div>
                        @php
                            $currentStopObj = null;
                            if ($activeBus) {
                                if ($activeBus->current_stop_id) {
                                    $currentStopObj = collect($route->stops)->firstWhere('id', $activeBus->current_stop_id);
                                } else {
                                    $currentStopObj = collect($route->stops)->sortBy('stop_sequence')->first();
                                }
                            }
                            
                            $scheduledArrival = $currentStopObj && $currentStopObj->arrival_time ? \Carbon\Carbon::parse($currentStopObj->arrival_time)->format('h:i A') : 'N/A';
                            
                            $actualArrival = $scheduledArrival !== 'N/A' && $activeBus && $activeBus->journey_delay_minutes != 0 
                                ? \Carbon\Carbon::parse($currentStopObj->arrival_time)->addMinutes($activeBus->journey_delay_minutes)->format('h:i A') 
                                : 'N/A';
                        @endphp
                        <div class="info-label">Current Stop</div>
                        <div class="info-value" style="margin-bottom:15px;">{{ $currentStopObj->stop_name ?? 'N/A' }}</div>
                        
                        <label class="form-label">Time Status</label>
                        <div style="display: flex; gap: 15px; margin-bottom: 15px;">
                            <label style="font-weight: 500;">
                                <input type="radio" name="time_type" value="delay" required {{ $activeBus && $activeBus->journey_delay_minutes > 0 ? 'checked' : '' }}> Running Late
                            </label>
                            <label style="font-weight: 500;">
                                <input type="radio" name="time_type" value="early" required {{ $activeBus && $activeBus->journey_delay_minutes < 0 ? 'checked' : '' }}> Running Early
                            </label>
                        </div>

                        <label class="form-label">Reason</label>
                        <select name="delay_reason" class="form-control-custom" required {{ !$activeBus ? 'disabled' : '' }}>
                            <option value="Traffic">Traffic</option>
                            <option value="Weather">Weather</option>
                            <option value="Mechanical Issue">Mechanical Issue</option>
                            <option value="Passenger Delay">Passenger Delay</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div>
                        <div style="display:flex; justify-content:space-between; margin-bottom:15px;">
                            <div>
                                <div class="info-label">Scheduled Arrival</div>
                                <div class="info-value">{{ $scheduledArrival }}</div>
                            </div>
                            <div>
                                <div class="info-label">Actual Arrival</div>
                                <div class="info-value">{{ $actualArrival }}</div>
                            </div>
                        </div>
                        
                        <label class="form-label">Time Offset (Minutes)</label>
                        <input type="number" name="delay_minutes" class="form-control-custom" placeholder="e.g., 15" required {{ !$activeBus ? 'disabled' : '' }} min="0" value="{{ $activeBus && $activeBus->journey_delay_minutes != 0 ? abs($activeBus->journey_delay_minutes) : '' }}">
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Notes (Optional)</label>
                    <textarea name="notes" class="form-control-custom" rows="2" {{ !$activeBus ? 'disabled' : '' }} placeholder="Heavy traffic due to accident near highway..."></textarea>
                </div>
                
                <div style="display: flex; gap: 10px; justify-content: space-between;">
                    <button type="reset" class="btn btn-light" style="width:48%; font-weight:600;">Cancel</button>
                    <button type="submit" class="btn btn-primary" style="width:48%; background:#0a3b9a; font-weight:600;" {{ !$activeBus ? 'disabled' : '' }}>Submit Update</button>
                </div>
            </form>
        </div>

    </div>

    <!-- Right Column -->
    <div class="col-lg-5">
        
        <!-- Route Performance -->
        <div class="card-custom">
            <h3 class="card-title">Route Performance</h3>
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fa-solid fa-map-location-dot"></i></div>
                    <div>
                        <div class="info-label">Total Stops</div>
                        <div class="info-value fs-5">{{ str_pad($totalStops, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon" style="background:#ecfdf5; color:#10b981;"><i class="fa-solid fa-clock-rotate-left"></i></div>
                    <div>
                        <div class="info-label">On Time Arrival</div>
                        <div class="info-value fs-5">{{ $onTimeArrival }}%</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon" style="background:#fffbeb; color:#f59e0b;"><i class="fa-regular fa-clock"></i></div>
                    <div>
                        <div class="info-label">Delay Instances</div>
                        <div class="info-value fs-5">{{ str_pad($delayInstances, 2, '0', STR_PAD_LEFT) }}</div>
                    </div>
                </div>
                <div class="stat-box">
                    <div class="stat-icon" style="background:#fef2f2; color:#ef4444;"><i class="fa-solid fa-stopwatch"></i></div>
                    <div>
                        <div class="info-label">Avg Delay</div>
                        <div class="info-value fs-5">{{ $avgDelay }} mins</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Route Tracking -->
        <div class="card-custom">
            <h3 class="card-title">Live Route Tracking</h3>
            <div id="routeMap" style="height: 350px; width: 100%; border-radius: 12px; margin-bottom: 15px; z-index: 1;"></div>
            
            <div class="map-legend">
                <div class="legend-item"><div class="legend-dot" style="background:var(--success);"></div> Completed</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--primary);"></div> Current Stop</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--secondary);"></div> Upcoming</div>
                <div class="legend-item"><div class="legend-dot" style="background:var(--danger);"></div> Destination</div>
            </div>
        </div>
        
        <!-- Route Timeline -->
        <div class="card-custom">
            <h3 class="card-title">Route Timeline</h3>
            <div class="timeline-wrapper">
                <div class="timeline-line"></div>
                @php
                    $passedCurrentTL = false;
                @endphp
                @foreach($route->stops as $index => $stop)
                    @php
                        $isCurrentTL = $activeBus && $activeBus->current_stop_id == $stop->id;
                        if ($isCurrentTL) $passedCurrentTL = true;
                        
                        $isCompletedTL = !$passedCurrentTL && $activeBus && $activeBus->current_stop_id && !$isCurrentTL;
                        $offsetMins = $activeBus ? $activeBus->journey_delay_minutes : 0;
                        
                        $isDelayed = !$isCompletedTL && $offsetMins > 0;
                        $isEarly = !$isCompletedTL && $offsetMins < 0;
                        
                        $statusClass = 'on-time';
                        if ($isCompletedTL) $statusClass = 'arrived';
                        elseif ($isDelayed) $statusClass = 'delayed';
                        elseif ($isEarly) $statusClass = 'early';
                    @endphp
                    <div class="timeline-point {{ $statusClass }}">
                        <div class="tl-dot" style="{{ $isEarly ? 'border-color: var(--success);' : '' }}"></div>
                        <div style="font-size:0.75rem; font-weight:600; color:var(--text-dark);">{{ $stop->stop_name }}</div>
                        @if($isCompletedTL)
                            <div style="font-size:0.7rem; color:var(--text-muted);">{{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-' }}</div>
                            <div style="font-size:0.75rem; color:var(--text-dark); font-weight:700;">Arrived</div>
                        @elseif($isDelayed)
                            <div style="font-size:0.7rem; color:var(--text-muted);"><del>{{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-' }}</del></div>
                            <div style="font-size:0.7rem; color:var(--danger); font-weight:600;">
                                {{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->addMinutes($offsetMins)->format('h:i A') : '-' }}
                                <br>+{{ $offsetMins }} mins
                            </div>
                        @elseif($isEarly)
                            <div style="font-size:0.7rem; color:var(--text-muted);"><del>{{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-' }}</del></div>
                            <div style="font-size:0.7rem; color:var(--success); font-weight:600;">
                                {{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->addMinutes($offsetMins)->format('h:i A') : '-' }}
                                <br>-{{ abs($offsetMins) }} mins
                            </div>
                        @else
                            <div style="font-size:0.7rem; color:var(--text-muted);">{{ $stop->arrival_time ? \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') : '-' }}</div>
                            <div style="font-size:0.7rem; color:var(--success);">On Time</div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Route Summary -->
        <div class="card-custom">
            <h3 class="card-title">Route Summary</h3>
            <div style="display: flex; justify-content: space-between; padding: 20px;">
                <div>
                    <div class="info-label">Total Distance</div>
                    <div class="info-value">{{ $route->distance ?? '0 km' }}</div>
                </div>
                <div>
                    <div class="info-label">Estimated Duration</div>
                    <div class="info-value">{{ $durationStr }}</div>
                </div>
                <div>
                    <div class="info-label">Total Stops</div>
                    <div class="info-value">{{ str_pad($totalStops, 2, '0', STR_PAD_LEFT) }}</div>
                </div>
                <div>
                    <div class="info-label">Time Offset</div>
                    @php
                        $offset = $activeBus ? $activeBus->journey_delay_minutes : 0;
                    @endphp
                    <div class="info-value {{ $offset > 0 ? 'text-danger' : ($offset < 0 ? 'text-success' : '') }}">
                        {{ $offset == 0 ? 'On Time' : ($offset > 0 ? $offset . ' mins late' : abs($offset) . ' mins early') }}
                    </div>
                </div>
                <div>
                    <div class="info-label">Route Status</div>
                    <div class="info-value text-success">Active</div>
                </div>
            </div>
        </div>



    </div>
</div>

<!-- Add Stop Modal (Updated for new fields) -->
<div class="modal fade" id="addStopModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title">Add New Stop</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.bus-routes.stops.store', $route->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Stop Name</label>
                        <input type="text" name="stop_name" class="form-control" required>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Arrival Time</label>
                            <input type="text" name="arrival_time" class="form-control" placeholder="e.g. 10:30 AM">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Departure Time</label>
                            <input type="text" name="departure_time" class="form-control" placeholder="e.g. 10:45 AM">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Stay Duration (mins)</label>
                            <input type="number" name="stay_duration_mins" class="form-control" min="0">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Distance from Start (km)</label>
                            <input type="number" step="0.1" name="distance_from_start" class="form-control" min="0">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Stop</button>
                </div>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var map = L.map('routeMap').setView([17.6868, 83.2185], 11); // Default Visakhapatnam
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var stops = @json($route->stops);
    var currentStopId = {!! $activeBus && $activeBus->current_stop_id ? $activeBus->current_stop_id : 'null' !!};
    var currentFound = false;
    
    var waypoints = [];
    var markers = L.featureGroup();
    
    // Fallback coordinates in case geocoding fails (creates a small path near Vizag)
    var fallbackCoords = [
        {lat: 17.6868, lon: 83.2185},
        {lat: 17.7000, lon: 83.1800},
        {lat: 17.7200, lon: 83.1500},
        {lat: 17.7500, lon: 83.1000},
        {lat: 17.8000, lon: 83.0000}
    ];
    
    async function geocode(stopName, index) {
        let cacheKey = 'geo_v2_' + stopName;
        if (localStorage.getItem(cacheKey)) {
            return JSON.parse(localStorage.getItem(cacheKey));
        }
        try {
            // Append India context for better accuracy of small local stops
            let query = encodeURIComponent(stopName + ', Andhra Pradesh, India');
            let res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${query}`);
            let data = await res.json();
            if (data && data.length > 0) {
                let coords = { lat: parseFloat(data[0].lat), lon: parseFloat(data[0].lon) };
                localStorage.setItem(cacheKey, JSON.stringify(coords));
                return coords;
            }
        } catch (e) {
            console.error('Geocoding error for', stopName, e);
        }
        
        // If it fails, return a fallback coordinate so the map doesn't break or zoom out to the world
        return fallbackCoords[index % fallbackCoords.length];
    }

    async function loadMap() {
        if (stops.length === 0) return;
        
        for (let i = 0; i < stops.length; i++) {
            let stop = stops[i];
            let coords = await geocode(stop.stop_name, i);
            
            waypoints.push([coords.lat, coords.lon]);
            
            let isCurrent = (currentStopId == stop.id);
            if (isCurrent) currentFound = true;
            
            let isCompleted = currentStopId ? !currentFound && !isCurrent : true;
            let isDest = (i === stops.length - 1);
            
            let bgColor = '#64748b'; // upcoming
            if (isCurrent) bgColor = '#2563eb'; // current
            else if (isCompleted) bgColor = '#10b981'; // completed
            if (isDest && !isCurrent && !isCompleted) bgColor = '#ef4444'; // destination
            
            let markerHtml = `
                <div style="background-color: ${bgColor}; 
                            color: white; width: 24px; height: 24px; border-radius: 50%; display: flex; 
                            align-items: center; justify-content: center; font-weight: bold; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                    ${isCurrent ? '<i class="fa-solid fa-bus" style="font-size:10px;"></i>' : stop.stop_sequence}
                </div>
            `;
            
            let icon = L.divIcon({
                className: 'custom-leaflet-icon',
                html: markerHtml,
                iconSize: [24, 24],
                iconAnchor: [12, 12]
            });
            
            let arr = stop.arrival_time ? stop.arrival_time.substring(0, 5) : '-';
            let dep = stop.departure_time ? stop.departure_time.substring(0, 5) : '-';
            
            L.marker([coords.lat, coords.lon], {icon: icon})
                .bindPopup(`<b>${stop.stop_name}</b><br>Arrival: ${arr} <br>Departure: ${dep}`)
                .addTo(markers);
            
            // Delay for rate limits
            if(!localStorage.getItem('geo_v2_' + stop.stop_name)) {
                await new Promise(r => setTimeout(r, 1000));
            }
        }
        
        markers.addTo(map);
        if (waypoints.length > 1) {
            L.polyline(waypoints, {color: '#2563eb', weight: 4, opacity: 0.7, dashArray: '8, 8'}).addTo(map);
        }
        map.fitBounds(markers.getBounds(), {padding: [40, 40]});
    }
    
    loadMap();
});
</script>

@endsection
