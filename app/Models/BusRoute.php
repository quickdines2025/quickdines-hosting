<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusRoute extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'source',
        'destination',
        'distance',
        'status',
    ];

    public function buses()
    {
        return $this->hasMany(Bus::class, 'bus_route_id');
    }

    public function stops()
    {
        return $this->hasMany(BusRouteStop::class, 'bus_route_id')->orderBy('stop_sequence');
    }
}
