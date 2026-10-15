<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Settlement;
use Illuminate\Http\Request;

/**
 * Restaurant-wise aggregation of settlement records.
 * Payable = sum(net_amount); Settled = sum(net_amount where completed); Pending = Payable - Settled.
 */
class VendorPayoutController extends Controller
{
    public function index(Request $request)
    {
        $query = Settlement::query()
            ->whereNotNull('restaurant_id')
            ->selectRaw("restaurant_id,
                COUNT(*) as settlement_count,
                SUM(amount) as gross,
                SUM(commission_amount) as commission,
                SUM(gst_amount) as gst,
                SUM(platform_fee) as platform_fees,
                SUM(net_amount) as payable,
                SUM(CASE WHEN status = 'completed' THEN net_amount ELSE 0 END) as settled")
            ->groupBy('restaurant_id');

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', (int) $request->restaurant_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $rows = $query->get();
        $names = Restaurant::whereIn('id', $rows->pluck('restaurant_id'))->pluck('restaurant_name', 'id');

        $rows = $rows->map(function ($r) use ($names) {
            $r->name = $names[$r->restaurant_id] ?? 'Unknown Restaurant';
            $r->pending = (float) $r->payable - (float) $r->settled;
            return $r;
        })->sortBy('name')->values();

        $totals = [
            'gross' => $rows->sum('gross'),
            'commission' => $rows->sum('commission'),
            'gst' => $rows->sum('gst'),
            'platform_fees' => $rows->sum('platform_fees'),
            'payable' => $rows->sum('payable'),
            'settled' => $rows->sum('settled'),
            'pending' => $rows->sum('pending'),
        ];

        $restaurants = Restaurant::orderBy('restaurant_name')->get(['id', 'restaurant_name']);

        return view('admin.finance.vendor_payouts.index', compact('rows', 'totals', 'restaurants'));
    }
}
