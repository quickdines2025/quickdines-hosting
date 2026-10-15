@extends('layouts.frontend')

@section('content')
<div class="light-content" style="min-height: 60vh; padding: 40px 15px;">
    <div class="container" style="max-width: 600px; margin: 0 auto;">
        
        <!-- Search Section -->
        <div class="page-card" style="text-align: center; margin-bottom: 30px;">
            <div style="width: 60px; height: 60px; background: #eff6ff; color: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 16px;">
                <i class="fa-solid fa-utensils"></i>
            </div>
            <h1 style="font-size: 24px; font-weight: 800; color: #000; margin-bottom: 12px;">Track Your Restaurant</h1>
            <p style="color: #64748b; margin-bottom: 24px; font-size: 14px; line-height: 1.5;">Enter your Bus Number to see the live route and estimated arrival time at the restaurant.</p>
            
            <form action="{{ route('track-bus') }}" method="GET" class="page-form" style="display: flex; gap: 10px;">
                <input type="text" name="bus_number" value="{{ request('bus_number') }}" placeholder="Enter Bus Number (e.g. BUS-123)" style="flex-grow: 1; padding: 12px 16px; border: 1px solid #e2e8f0; border-radius: 8px; outline: none; font-size: 15px;" required>
                <button type="submit" class="btn-primary" style="padding: 12px 24px; white-space: nowrap; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Track</button>
            </form>
        </div>

        <!-- Tracking Results Section -->
        @if(isset($searched) && $searched)
            @if($bus && $bus->busRoute)
                <div class="page-card">
                    <h3 style="font-size: 18px; font-weight: 700; margin-bottom: 20px; color: #0f172a; display: flex; align-items: center; justify-content: space-between;">
                        <span>{{ $bus->bus_number }} - {{ $bus->busRoute->name }}</span>
                        <span style="font-size: 12px; padding: 4px 10px; background: {{ $bus->journey_start_time ? '#dcfce7' : '#f1f5f9' }}; color: {{ $bus->journey_start_time ? '#16a34a' : '#64748b' }}; border-radius: 20px;">
                            {{ $bus->journey_start_time ? 'Journey Started' : 'Not Started' }}
                        </span>
                    </h3>
                    
                    @if($bus->journey_delay_minutes > 0)
                    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        <span>Bus is running {{ $bus->journey_delay_minutes }} minutes late.</span>
                    </div>
                    @elseif($bus->journey_delay_minutes < 0)
                    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Bus is running {{ abs($bus->journey_delay_minutes) }} minutes early.</span>
                    </div>
                    @endif

                    <!-- Timeline -->
                    <div style="position: relative; padding-left: 30px;">
                        <!-- Timeline Vertical Line -->
                        <div style="position: absolute; top: 10px; bottom: 20px; left: 11px; width: 2px; background: #e2e8f0; z-index: 1;"></div>
                        
                        @php
                            $currentStopPassed = false;
                        @endphp
                        
                        @foreach($bus->busRoute->stops as $stop)
                            @php
                                $isCurrentStop = $bus->current_stop_id == $stop->id;
                                if ($isCurrentStop) {
                                    $currentStopPassed = true;
                                }
                                
                                $statusColor = '#94a3b8'; // default gray (upcoming)
                                $statusIcon = 'fa-circle';
                                $textColor = '#64748b';
                                
                                if (!$currentStopPassed && !$isCurrentStop) {
                                    // Passed stop
                                    $statusColor = '#16a34a';
                                    $statusIcon = 'fa-solid fa-circle-check';
                                    $textColor = '#475569';
                                } elseif ($isCurrentStop) {
                                    // Current stop
                                    $statusColor = '#3b82f6';
                                    $statusIcon = 'fa-solid fa-location-crosshairs';
                                    $textColor = '#0f172a';
                                }
                            @endphp
                            
                            <div style="position: relative; margin-bottom: {{ $loop->last ? '0' : '25px' }}; z-index: 2;">
                                <!-- Marker -->
                                <div style="position: absolute; left: -30px; top: 2px; width: 24px; height: 24px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: {{ $statusColor }}; font-size: 14px;">
                                    <i class="{{ $statusIcon }}"></i>
                                </div>
                                
                                <!-- Stop Info -->
                                <div>
                                    <h4 style="font-size: 15px; font-weight: {{ $isCurrentStop ? '700' : '600' }}; color: {{ $textColor }}; margin-bottom: 4px;">{{ $stop->stop_name }}</h4>
                                    
                                    <div style="display: flex; gap: 15px; font-size: 12px; color: #64748b;">
                                        @if($bus->journey_start_time || $stop->arrival_time)
                                            <span style="color: {{ $isCurrentStop ? '#3b82f6' : '#64748b' }}; font-weight: 500;">
                                                <i class="fa-regular fa-clock" style="margin-right: 4px;"></i>
                                                @if($bus->journey_delay_minutes > 0 && $stop->arrival_time)
                                                    <del class="text-muted" style="margin-right: 4px; font-size: 11px; opacity: 0.7;">{{ \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') }}</del>
                                                    <span style="color: #ef4444;">{{ $bus->calculateEtaForStop($stop) ? $bus->calculateEtaForStop($stop)->format('h:i A') : 'N/A' }}</span>
                                                @elseif($bus->journey_delay_minutes < 0 && $stop->arrival_time)
                                                    <del class="text-muted" style="margin-right: 4px; font-size: 11px; opacity: 0.7;">{{ \Carbon\Carbon::parse($stop->arrival_time)->format('h:i A') }}</del>
                                                    <span style="color: #16a34a;">{{ $bus->calculateEtaForStop($stop) ? $bus->calculateEtaForStop($stop)->format('h:i A') : 'N/A' }}</span>
                                                @else
                                                    ETA: {{ $bus->calculateEtaForStop($stop) ? $bus->calculateEtaForStop($stop)->format('h:i A') : 'N/A' }}
                                                @endif
                                            </span>
                                        @else
                                            <span>
                                                <i class="fa-solid fa-hourglass-start" style="margin-right: 4px;"></i>Pending Schedule
                                            </span>
                                        @endif
                                        <span>
                                            <i class="fa-solid fa-road" style="margin-right: 4px;"></i>{{ $stop->distance_from_start }} km
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="page-card" style="text-align: center; padding: 40px 20px;">
                    <i class="fa-solid fa-bus-slash" style="font-size: 40px; color: #cbd5e1; margin-bottom: 16px;"></i>
                    <h3 style="font-size: 18px; font-weight: 600; color: #475569; margin-bottom: 8px;">Bus Not Found</h3>
                    <p style="color: #64748b; font-size: 14px;">We couldn't find a bus with that number. Please check the number and try again.</p>
                </div>
            @endif
        @endif
        
    </div>
</div>
@endsection
