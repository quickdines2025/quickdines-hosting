<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Helper to get formatted transaction ID
    public function getTxIdAttribute()
    {
        return 'TRX' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class, 'user_id');
    }

    // Customer payment transactions store the Order id in `order_id`.
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'user_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Helper to dynamically get the partner based on user_type
    public function getPartnerAttribute()
    {
        if ($this->user_type == 'restaurant') return $this->restaurant;
        if ($this->user_type == 'driver') return $this->driver;
        if ($this->user_type == 'customer') return $this->customer;
        return null;
    }
}
