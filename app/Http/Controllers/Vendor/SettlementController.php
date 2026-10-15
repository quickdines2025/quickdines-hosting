<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Settlement;
use Illuminate\Http\Request;

/**
 * Vendor-side view of the settlements the super admin creates/pays.
 * Read-only: a vendor only ever sees their own restaurant's settlements.
 */
class SettlementController extends Controller
{
    public function index(Request $request)
    {
        $restaurant = auth()->user()->restaurant;
        if (!$restaurant) {
            return redirect()->back();
        }

        $base = Settlement::where('restaurant_id', $restaurant->id);

        $totalPayable = (float) (clone $base)->sum('net_amount');
        $received = (float) (clone $base)->where('status', 'completed')->sum('net_amount');

        $stats = [
            'payable' => $totalPayable,
            'received' => $received,
            'pending' => $totalPayable - $received,
            'gross' => (float) (clone $base)->sum('amount'),
            'deductions' => (float) (clone $base)->sum('commission_amount')
                + (float) (clone $base)->sum('gst_amount')
                + (float) (clone $base)->sum('platform_fee'),
        ];

        $query = (clone $base)->with('order')->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status === 'settled' ? 'completed' : $request->status);
        }
        if ($request->filled('order_id')) {
            $query->where('order_id', (int) ltrim(trim($request->order_id), '#'));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $settlements = $query->paginate(15)->withQueryString();

        return view('vendor.settlements', compact('settlements', 'stats'));
    }
}
