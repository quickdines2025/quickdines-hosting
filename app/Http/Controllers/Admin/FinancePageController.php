<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FinancePageController extends Controller
{
    /**
     * Transactions = actual CUSTOMER payments (what the customer really paid).
     * Vendor wallet entries and driver/vendor payouts are not customer payments;
     * payouts stay under Payout History / Vendor Payouts.
     */
    private function customerPaymentsQuery(Request $request)
    {
        $query = \App\Models\Transaction::query()
            ->with(['order.restaurant', 'order.user', 'customer'])
            ->where('user_type', 'customer')
            ->orderBy('created_at', 'desc');

        // Transaction ID (accepts "TRX0012" or "12")
        if ($request->filled('search')) {
            $searchId = str_ireplace('TRX', '', trim($request->search));
            if (is_numeric($searchId)) {
                $query->where('id', (int) $searchId);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->filled('order_id')) {
            $query->where('order_id', trim($request->order_id));
        }

        if ($request->filled('customer')) {
            $term = '%' . trim($request->customer) . '%';
            $query->whereHas('customer', function ($q) use ($term) {
                $q->where('name', 'like', $term)->orWhere('mobile', 'like', $term);
            });
        }

        if ($request->filled('restaurant_id')) {
            $restaurantId = (int) $request->restaurant_id;
            $query->whereHas('order', function ($q) use ($restaurantId) {
                $q->where('restaurant_id', $restaurantId);
            });
        }

        if ($request->filled('status') && $request->status != 'All Status') {
            if (in_array($request->status, ['completed', 'success'])) {
                $query->whereIn('status', ['completed', 'success']);
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return $query;
    }

    public function transactions(Request $request)
    {
        $transactions = $this->customerPaymentsQuery($request)->paginate(15)->withQueryString();

        $customer = \App\Models\Transaction::where('user_type', 'customer');

        // "Total Amount" only counts successful payments (same rule as the Finance Dashboard).
        $stats = [
            'total_count' => (clone $customer)->count(),
            'total_amount' => (clone $customer)->whereIn('status', ['completed', 'success'])->sum('amount'),
            'successful' => (clone $customer)->whereIn('status', ['completed', 'success'])->count(),
            'refunded' => (clone $customer)->where('status', 'refunded')->count(),
            'failed' => (clone $customer)->where('status', 'failed')->count(),
        ];

        $restaurants = \App\Models\Restaurant::orderBy('restaurant_name')->get(['id', 'restaurant_name']);

        return view('admin.finance.transactions.index', compact('transactions', 'stats', 'restaurants'));
    }

    public function exportTransactions(Request $request)
    {
        $transactions = $this->customerPaymentsQuery($request)->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=transactions_".date('Y-m-d_H-i-s').".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Transaction ID', 'Order ID', 'Customer', 'Restaurant', 'Customer Paid (Rs)', 'Payment Method', 'Razorpay Payment ID', 'Payment Status', 'Date'];

        $callback = function() use($transactions, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($transactions as $trx) {
                $order = $trx->order;
                fputcsv($file, [
                    $trx->tx_id,
                    $trx->order_id ?? '-',
                    optional($trx->customer)->name ?? optional($order)->passenger_name ?? '-',
                    optional(optional($order)->restaurant)->restaurant_name ?? '-',
                    $trx->amount,
                    $trx->payment_method ?? '-',
                    $trx->reference_id ?? optional($order)->razorpay_payment_id ?? '-',
                    ucfirst($trx->status),
                    $trx->created_at->format('M d, Y h:i A')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function payouts(Request $request)
    {
        $query = \App\Models\Transaction::where('type', 'payout')->orderBy('created_at', 'desc');

        if ($request->filled('user_type') && $request->user_type != 'All Partners') {
            $query->where('user_type', strtolower($request->user_type));
        }
        if ($request->filled('status') && $request->status != 'All Status') {
            $query->where('status', strtolower($request->status));
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payouts = $query->paginate(15)->withQueryString();

        // Stats calculation
        $stats = [
            'total_payouts' => \App\Models\Transaction::where('type', 'payout')->where('status', 'completed')->sum('amount'),
            'this_week' => \App\Models\Transaction::where('type', 'payout')->where('status', 'completed')->where('updated_at', '>=', now()->subDays(7))->sum('amount'),
            'this_month' => \App\Models\Transaction::where('type', 'payout')->where('status', 'completed')->where('updated_at', '>=', now()->startOfMonth())->sum('amount'),
            'pending_payouts' => \App\Models\Transaction::where('type', 'payout')->where('status', 'pending')->sum('amount'),
        ];

        return view('admin.finance.payouts.index', compact('payouts', 'stats'));
    }

    public function exportPayouts(Request $request)
    {
        $query = \App\Models\Transaction::where('type', 'payout')->orderBy('created_at', 'desc');

        if ($request->filled('user_type') && $request->user_type != 'All Partners') {
            $query->where('user_type', strtolower($request->user_type));
        }
        if ($request->filled('status') && $request->status != 'All Status') {
            $query->where('status', strtolower($request->status));
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payouts = $query->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=payouts_".date('Y-m-d_H-i-s').".csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['Payout ID', 'Partner', 'Type', 'Amount (Rs)', 'Status', 'Date', 'Bank UTR'];

        $callback = function() use($payouts, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($payouts as $trx) {
                $partnerName = 'Unknown Partner';
                if ($trx->user_type == 'restaurant' || $trx->user_type == 'vendor') {
                    $partner = \App\Models\Restaurant::find($trx->user_id);
                    $partnerName = $partner ? ($partner->name ?? $partner->restaurant_name ?? 'Unknown Restaurant') : 'Unknown Restaurant';
                } elseif ($trx->user_type == 'driver') {
                    $partner = \App\Models\User::find($trx->user_id);
                    $partnerName = $partner ? $partner->name : 'Unknown Driver';
                }

                fputcsv($file, [
                    'PYO100' . $trx->id,
                    $partnerName,
                    ucfirst($trx->user_type) . ' Payout',
                    $trx->amount,
                    ucfirst($trx->status),
                    $trx->created_at->format('M d, Y h:i A'),
                    $trx->reference_id ?? '-'
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function commissions()
    {
        $settings = \App\Models\SystemSetting::pluck('value', 'key')->toArray();

        // Defaults if not set
        $settings = array_merge([
            'platform_commission' => 10,
            'gst_on_commission' => 18,
            'min_commission' => 50,
            'max_commission' => 100,
            'apply_commission_on' => 'after_discount',
            'commission_rounding' => '1',
            'commission_type' => 'percentage',
            'apply_on_delivery' => '0',
            'driver_commission_per_order' => 10
        ], $settings);

        return view('admin.finance.commissions.index', compact('settings'));
    }

    public function saveCommissions(Request $request)
    {
        $data = $request->except('_token');
        
        // Handle toggle checkbox which might not be in request if unchecked
        if (!isset($data['apply_on_delivery'])) {
            $data['apply_on_delivery'] = '0';
        }

        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            \App\Models\SystemSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'Commission settings updated successfully!');
    }
}
