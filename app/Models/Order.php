<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'driver_assigned_at' => 'datetime',
        'driver_location_updated_at' => 'datetime',
        'driver_eta_at' => 'datetime',

        'passenger_seats' => 'array',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function bus()
    {
        return $this->belongsTo(Bus::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function settlement()
    {
        return $this->hasOne(Settlement::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    /**
     * Backwards-compatible alias used by existing vendor views.
     * driver_id references the drivers table.
     */
    public function driverUser()
    {
        return $this->driver();
    }
}
