<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\Settlement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Settlements = the vendor financial calculation for each order.
 * (Customer Paid, Commission, GST on commission, Platform Fee, Vendor Payable.)
 *
 * Status values are unchanged from the existing project:
 *   pending -> completed   ("completed" is displayed as "Settled").
 */
class SettlementController extends Controller
{
    /** Status label shown to admins. */
    private function statusLabel(string $status): string
    {
        return $status === 'completed' ? 'Settled' : ucfirst($status);
    }

    private function filteredQuery(Request $request)
    {
        $query = Settlement::with(['restaurant', 'driver', 'order.user'])
            ->orderBy('created_at', 'desc');

        // Partner type
        if ($request->filled('partner_type') && $request->partner_type !== 'All') {
            if ($request->partner_type === 'Restaurant') {
                $query->whereNotNull('restaurant_id');
            }
            if ($request->partner_type === 'Driver') {
                $query->whereNotNull('driver_id');
            }
        }

        // Restaurant
        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', (int) $request->restaurant_id);
        }

        // Order ID
        if ($request->filled('order_id')) {
            $query->where('order_id', (int) ltrim(trim($request->order_id), '#'));
        }

        // Customer (name / mobile / passenger name on the order)
        if ($request->filled('customer')) {
            $term = '%' . trim($request->customer) . '%';
            $query->whereHas('order', function ($q) use ($term) {
                $q->where('passenger_name', 'like', $term)
                    ->orWhereHas('user', function ($u) use ($term) {
                        $u->where('name', 'like', $term)->orWhere('mobile', 'like', $term);
                    });
            });
        }

        // Status (dropdown: Pending / Completed / Settled) and tabs
        if ($request->filled('status') && $request->status !== 'All') {
            $status = strtolower($request->status);
            $query->where('status', $status === 'settled' ? 'completed' : $status);
        }

        if ($request->filled('tab') && $request->tab !== 'All Settlements') {
            switch ($request->tab) {
                case 'Payable':
                case 'Pending':
                    $query->where('status', 'pending');
                    break;
                case 'Processing':
                    $query->where('status', 'processing');
                    break;
                case 'Completed':
                    $query->where('status', 'completed');
                    break;
                case 'Failed':
                    $query->where('status', 'failed');
                    break;
            }
        }

        // Date range ("2026-09-01 to 2026-09-30" or a single date)
        if ($request->filled('date')) {
            $dates = preg_split('/\s+to\s+/', trim($request->date));

            try {
                if (count($dates) === 2) {
                    $query->whereBetween('created_at', [
                        Carbon::parse(trim($dates[0]))->startOfDay(),
                        Carbon::parse(trim($dates[1]))->endOfDay(),
                    ]);
                } elseif (count($dates) === 1) {
                    $query->whereDate('created_at', Carbon::parse(trim($dates[0]))->format('Y-m-d'));
                }
            } catch (\Exception $e) {
                // Ignore invalid date.
            }
        }

        return $query;
    }

    public function index(Request $request)
    {
        $paginator = $this->filteredQuery($request)->paginate(15)->withQueryString();

        $settlements = [];

        foreach ($paginator as $settlement) {
            $partnerName = 'Unknown';
            $partnerType = 'Unknown';
            $bankName = $accountNumber = $ifscCode = $branchName = '';

            if ($settlement->restaurant_id && $settlement->restaurant) {
                $partner = $settlement->restaurant;
                $partnerName = $partner->restaurant_name ?? 'Unknown Restaurant';
                $partnerType = 'Restaurant';
            } elseif ($settlement->driver_id && $settlement->driver) {
                $partner = $settlement->driver;
                $partnerName = $partner->name ?? 'Unknown Driver';
                $partnerType = 'Driver';
            } else {
                $partner = null;
            }

            if ($partner) {
                $bankName = $partner->bank_name;
                $accountNumber = $partner->account_number;
                $ifscCode = $partner->ifsc_code;
                $branchName = $partner->branch_name;
            }

            $order = $settlement->order;
            $customerName = optional(optional($order)->user)->name
                ?? optional($order)->passenger_name
                ?? 'Unknown Customer';

            $settlements[] = (object) [
                'id' => 'SETT' . str_pad($settlement->id, 6, '0', STR_PAD_LEFT),
                'raw_id' => $settlement->id,
                'order_id' => $settlement->order_id,
                'partner_name' => $partnerName,
                'partner_type' => $partnerType,
                'customer_name' => $customerName,
                'amount' => number_format($settlement->amount, 2),
                'commission' => number_format($settlement->commission_amount, 2),
                'commission_percentage' => number_format($settlement->commission_percentage, 2),
                'gst' => number_format($settlement->gst_amount, 2),
                'gst_percentage' => number_format($settlement->gst_percentage, 2),
                'platform_fee' => number_format($settlement->platform_fee, 2),
                'net_amount' => number_format($settlement->net_amount, 2),
                'status' => ucfirst($settlement->status),
                'status_label' => $this->statusLabel($settlement->status),
                'date' => $settlement->created_at ? $settlement->created_at->format('M d, Y') : '-',
                'settled_date' => $settlement->settled_at ? $settlement->settled_at->format('M d, Y') : '-',
                'bank_name' => $bankName ?: 'Not Provided',
                'account_number' => $accountNumber ?: 'Not Provided',
                'ifsc_code' => $ifscCode ?: 'Not Provided',
                'branch_name' => $branchName ?: 'Not Provided',
                'reference_id' => $settlement->reference_id ?: '-',
            ];
        }

        /*
        | Stats.
        | Payable = every settlement's vendor amount. Settled = completed ones.
        | Pending = Payable - Settled.
        */
        $totalPayable = (float) Settlement::sum('net_amount');
        $totalSettled = (float) Settlement::where('status', 'completed')->sum('net_amount');
        $pendingAmount = $totalPayable - $totalSettled;
        $pendingCount = Settlement::where('status', 'pending')->count();

        $weekQuery = Settlement::where('status', 'completed')
            ->where('settled_at', '>=', now()->startOfWeek());

        $stats = [
            'total_payable' => number_format($totalPayable, 2),
            'settled_this_week' => number_format((clone $weekQuery)->sum('net_amount'), 2),
            'settlements_this_week_count' => (clone $weekQuery)->count(),
            'pending_settlements' => number_format($pendingAmount, 2),
            'pending_count' => $pendingCount,
            'total_settled' => number_format($totalSettled, 2),
        ];

        $recentPayouts = [];
        $recent = Settlement::with(['restaurant', 'driver'])
            ->where('status', 'completed')
            ->orderBy('settled_at', 'desc')
            ->take(5)
            ->get();

        foreach ($recent as $settlement) {
            $recentPayouts[] = (object) [
                'partner_name' => optional($settlement->restaurant)->restaurant_name
                    ?? optional($settlement->driver)->name
                    ?? 'Unknown',
                'amount' => number_format($settlement->net_amount, 2),
                'date' => $settlement->settled_at ? $settlement->settled_at->format('M d, Y') : '-',
            ];
        }

        $transactions = $paginator; // the view uses this name for pagination
        $restaurants = Restaurant::orderBy('restaurant_name')->get(['id', 'restaurant_name']);

        return view(
            'admin.finance.settlements.index',
            compact('stats', 'settlements', 'transactions', 'recentPayouts', 'restaurants')
        );
    }

    public function settle(Request $request, $id)
    {
        $request->validate([
            'reference_id' => ['nullable', 'string', 'max:255'],
        ]);

        // Row lock + status re-check inside the transaction: a double click or two
        // admins can never settle the same settlement twice.
        $settled = DB::transaction(function () use ($id, $request) {
            $settlement = Settlement::lockForUpdate()->findOrFail($id);

            if ($settlement->status !== 'pending') {
                return false;
            }

            $settlement->status = 'completed';
            $settlement->reference_id = $request->reference_id;
            $settlement->settled_at = now();
            $settlement->save();

            // Record the actual vendor payment so it appears in Payout History.
            // user_type 'restaurant' keeps it separate from the vendor wallet
            // ('vendor') and customer payment ('customer') transactions.
            if ($settlement->restaurant_id) {
                \App\Models\Transaction::create([
                    'order_id' => $settlement->order_id,
                    'user_type' => 'restaurant',
                    'user_id' => $settlement->restaurant_id,
                    'amount' => $settlement->net_amount,
                    'type' => 'payout',
                    'status' => 'completed',
                    'payment_method' => 'bank_transfer',
                    'reference_id' => $request->reference_id,
                ]);
            }

            return true;
        });

        if (!$settled) {
            return back()->with('error', 'Only pending settlements can be settled.');
        }

        return back()->with('success', 'Settlement completed successfully!');
    }
}
