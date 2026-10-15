<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRouteStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'bus_route_id',
        'stop_name',
        'stop_sequence',
        'expected_time_from_start',
        'arrival_time',
        'departure_time',
        'stay_duration_mins',
        'distance_from_start',
    ];

    public function route()
    {
        return $this->belongsTo(BusRoute::class, 'bus_route_id');
    }
}
