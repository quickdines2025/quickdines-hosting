<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;




class VendorCommissionSetting extends Model
{
    protected $table = 'vendor_commission_settings';

    protected $fillable = [
        'example_order_amount',
        'commission_percentage',
        'gst_percentage',
        'platform_fee',
    ];
}
