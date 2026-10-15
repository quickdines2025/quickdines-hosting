<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Driver;
use App\Models\Transaction;

class WalletController extends Controller
{
    public function index()
    {
        $driver = Driver::where('user_id', auth()->id())->first();
        if (!$driver) return redirect()->back();

        $transactions = Transaction::where('user_type', 'driver')
            ->where('user_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('driver.wallet.index', compact('driver', 'transactions'));
    }
}
