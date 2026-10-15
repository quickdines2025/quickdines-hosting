<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Settlement;

class FinancialController extends Controller
{
    /**
     * Finance Dashboard.
     *  - Customer payments  <- transactions (customer, completed/success)
     *  - Commission/GST/Fee/Payable/Settled <- settlements
     *  - Pending = Payable - Settled
     */
    public function index(Request $request)
    {
        $from = $request->filled('date_from') ? $request->date_from : null;
        $to = $request->filled('date_to') ? $request->date_to : null;
        $restaurantId = $request->filled('restaurant_id') ? (int) $request->restaurant_id : null;

        // Customer payments (one row per successful customer transaction => no double counting)
        $payments = Transaction::where('user_type', 'customer')
            ->whereIn('status', ['completed', 'success']);

        // Paid orders
        $orders = \App\Models\Order::whereRaw('LOWER(payment_status) = ?', ['paid']);

        $settlements = Settlement::query();

        if ($from) {
            $payments->whereDate('created_at', '>=', $from);
            $orders->whereDate('created_at', '>=', $from);
            $settlements->whereDate('created_at', '>=', $from);
        }
        if ($to) {
            $payments->whereDate('created_at', '<=', $to);
            $orders->whereDate('created_at', '<=', $to);
            $settlements->whereDate('created_at', '<=', $to);
        }
        if ($restaurantId) {
            $payments->whereIn('order_id', \App\Models\Order::where('restaurant_id', $restaurantId)->pluck('id')->map(function ($id) {
                return (string) $id;
            }));
            $orders->where('restaurant_id', $restaurantId);
            $settlements->where('restaurant_id', $restaurantId);
        }

        $totalPayable = (float) (clone $settlements)->sum('net_amount');
        $totalSettled = (float) (clone $settlements)->where('status', 'completed')->sum('net_amount');

        $stats = [
            'customer_payments' => (float) $payments->sum('amount'),
            'total_orders' => $orders->count(),
            'commission' => (float) (clone $settlements)->sum('commission_amount'),
            'gst' => (float) (clone $settlements)->sum('gst_amount'),
            'platform_fees' => (float) (clone $settlements)->sum('platform_fee'),
            'vendor_payable' => $totalPayable,
            'settled' => $totalSettled,
            'pending' => $totalPayable - $totalSettled,
        ];
        $stats['quickdines_earnings'] = $stats['commission'] + $stats['gst'] + $stats['platform_fees'];

        $restaurants = \App\Models\Restaurant::orderBy('restaurant_name')->get(['id', 'restaurant_name']);

        return view('admin.finance.index', compact('stats', 'restaurants'));
    }

    public function approveWithdrawal(Request $request, $id)
    {
        $transaction = Transaction::where('type', 'debit')->where('status', 'pending')->findOrFail($id);
        $transaction->status = 'completed';
        $transaction->save();

        return back()->with('success', 'Withdrawal approved and marked as completed.');
    }

    public function rejectWithdrawal(Request $request, $id)
    {
        $transaction = Transaction::where('type', 'debit')->where('status', 'pending')->findOrFail($id);
        $transaction->status = 'failed';
        $transaction->save();
        
        // Refund logic
        if ($transaction->user_type == 'vendor') {
            $restaurant = \App\Models\Restaurant::find($transaction->user_id);
            if ($restaurant) {
                $restaurant->wallet_balance += $transaction->amount;
                $restaurant->save();
            }
        } elseif ($transaction->user_type == 'driver') {
            $driver = \App\Models\Driver::find($transaction->user_id);
            if ($driver) {
                $driver->wallet_balance += $transaction->amount;
                $driver->save();
            }
        }

        return back()->with('error', 'Withdrawal rejected and amount refunded.');
    }
}
