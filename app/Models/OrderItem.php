<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function menuItem()
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
