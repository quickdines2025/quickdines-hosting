<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $restaurant = Restaurant::where('user_id', auth()->id())->first();
        if (!$restaurant) return redirect()->back();

        $transactions = Transaction::where('user_type', 'vendor')
            ->whereIn('user_id', [$restaurant->id, $restaurant->user_id])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('vendor.wallet.index', compact('restaurant', 'transactions'));
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100'
        ]);

        $restaurant = Restaurant::where('user_id', auth()->id())->first();

        if ($restaurant->wallet_balance < $request->amount) {
            return redirect()->back()->with('error', 'Insufficient balance');
        }

        // Deduct from wallet
        $restaurant->wallet_balance -= $request->amount;
        $restaurant->save();

        // Create transaction record
        $transaction = new Transaction();
        $transaction->user_type = 'vendor';
        $transaction->user_id = $restaurant->id;
        $transaction->amount = $request->amount;
        $transaction->type = 'debit';
        $transaction->status = 'pending'; // Requires admin approval
        $transaction->save();

        return redirect()->back()->with('success', 'Withdrawal request submitted successfully');
    }
}
