<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VendorCommissionSetting;
use Illuminate\Http\Request;

class VendorCommissionSettingController extends Controller
{
    public function index()
    {
        $setting = VendorCommissionSetting::first();

        return view(
            'admin.finance.vendor_commission_setting.index',
            compact('setting')
        );
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'example_order_amount' => [
                'required',
                'numeric',
                'min:0',
            ],
            'commission_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'gst_percentage' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],
            'platform_fee' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        VendorCommissionSetting::updateOrCreate(
            ['id' => 1],
            $validated
        );

        return redirect()
            ->route('admin.vendor-commission.index')
            ->with(
                'success',
                'Vendor commission settings updated successfully.'
            );
    }
}
