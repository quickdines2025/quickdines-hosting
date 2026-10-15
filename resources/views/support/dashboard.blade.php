@extends('layouts.support')

@section('title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="page-title" style="font-size: 24px; font-weight: 800;">Network Command Center <span style="font-size: 13px; font-weight: 500; color: #64748b;">Monitor all active buses in real-time</span></h2>
</div>

@if($activeBuses->count() > 0)
    <div class="row">
        @foreach($activeBuses as $bus)
            <div class="col-md-6 mb-4">
                <div class="card h-100" style="border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);">
                    <div class="card-header" style="background: white; border-bottom: 1px solid #e2e8f0; border-radius: 12px 12px 0 0; padding: 15px 20px;">
                        <div style="display: flex; align-items: center; justify-content: space-between;">
                            <h3 style="font-size: 16px; font-weight: 700; margin-bottom: 0; color: #0f172a; display: flex; align-items: center;">
                                <i class="fa-solid fa-bus text-primary me-2"></i> {{ $bus->bus_number }}
                            </h3>
                            <div style="display: flex; gap: 8px;">
                                <span style="font-size: 11px; padding: 4px 8px; background: {{ $bus->journey_start_time ? '#dcfce7' : '#f1f5f9' }}; color: {{ $bus->journey_start_time ? '#16a34a' : '#64748b' }}; border-radius: 20px;">
                                    {{ $bus->journey_start_time ? 'Journey Started' : 'Not Started' }}
                                </span>
                            </div>
                        </div>
                        @if($bus->busRoute)
                            <div style="font-size: 12px; color: #64748b; margin-top: 5px;">Route: {{ $bus->busRoute->name }}</div>
                        @endif
                    </div>
                    
                    <div class="card-body" style="padding: 20px; max-height: 400px; overflow-y: auto;">
                        @if($bus->journey_delay_minutes > 0)
                        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Bus is running <strong>{{ $bus->journey_delay_minutes }} minutes</strong> late.</span>
                            </div>
                            <form action="{{ route('support.notify-delay', $bus->id) }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger" style="font-size: 11px; padding: 4px 10px; border-radius: 6px;" onclick="return confirm('Notify affected restaurants about this delay?');">
                                    Notify Restaurants
                                </button>
                            </form>
                        </div>
                        @elseif($bus->journey_delay_minutes < 0)
                        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 12px; border-radius: 8px; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between;">
                            <div style="display: flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-clock-rotate-left"></i>
                                <span>Bus is running <strong>{{ abs($bus->journey_delay_minutes) }} minutes</strong> early.</span>
                            </div>
                        </div>
                        @endif

                        @if($bus->busRoute && $bus->busRoute->stops->count() > 0)
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
                                    
                                    <div style="position: relative; margin-bottom: {{ $loop->last ? '0' : '20px' }}; z-index: 2;">
                                        <!-- Marker -->
                                        <div style="position: absolute; left: -30px; top: 2px; width: 24px; height: 24px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: {{ $statusColor }}; font-size: 14px;">
                                            <i class="{{ $statusIcon }}"></i>
                                        </div>
                                        
                                        <!-- Stop Info -->
                                        <div>
                                            <h4 style="font-size: 14px; font-weight: {{ $isCurrentStop ? '700' : '600' }}; color: {{ $textColor }}; margin-bottom: 2px;">{{ $stop->stop_name }}</h4>
                                            
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted p-4">
                                <i class="fa-solid fa-route fs-2 mb-2 opacity-50"></i>
                                <p class="mb-0">No route defined for this bus.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card" style="border-radius: 12px; border: 1px solid #e2e8f0; text-align: center; padding: 60px 20px;">
        <i class="fa-solid fa-bus-slash" style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;"></i>
        <h3 style="font-size: 18px; font-weight: 600; color: #475569; margin-bottom: 8px;">No Active Buses</h3>
        <p style="color: #64748b; font-size: 14px;">There are currently no active buses operating in the network.</p>
    </div>
@endif

@endsection

