<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bus extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'bus_number',
        'bus_name',
        'operator',
        'route_from',
        'route_to',
        'distance',
        'departure_time',
        'arrival_time',
        'driver_name',
        'driver_phone',
        'qr_code',
        'current_lat',
        'current_lng',
        'status',
        'image',
        'bus_route_id',
        'journey_status',
        'journey_updated_at',
        'journey_start_time',
        'journey_delay_minutes',
        'current_stop_id',
    ];

    protected $casts = [
        'journey_updated_at' => 'datetime',
        'journey_start_time' => 'datetime',
    ];

    public function busRoute()
    {
        return $this->belongsTo(BusRoute::class, 'bus_route_id');
    }

    public function currentStop()
    {
        return $this->belongsTo(BusRouteStop::class, 'current_stop_id');
    }

    // Helper method to calculate ETA for a given stop
    public function calculateEtaForStop(BusRouteStop $stop)
    {
        if ($stop->arrival_time) {
            $scheduledTime = \Carbon\Carbon::parse($stop->arrival_time);
            return $scheduledTime->addMinutes((int)$this->journey_delay_minutes);
        }

        if (!$this->journey_start_time) {
            return null; // Journey hasn't started
        }

        $startTime = \Carbon\Carbon::parse($this->journey_start_time);
        $totalMinutesToAdd = $stop->expected_time_from_start + $this->journey_delay_minutes;

        return $startTime->addMinutes($totalMinutesToAdd);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function restaurants()
    {
        return $this->belongsToMany(Restaurant::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'bus_id');
    }
}
