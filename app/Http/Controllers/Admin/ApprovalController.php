<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Restaurant;
use App\Models\Driver;

class ApprovalController extends Controller
{
    public function index()
    {
        $pendingVendors = Restaurant::with('user')->where('status', 0)->get();
        $pendingDrivers = Driver::with('user')->where('status', 0)->get();

        return view('admin.approvals.index', compact('pendingVendors', 'pendingDrivers'));
    }

    public function approve($type, $id)
    {
        if ($type === 'vendor') {
            $model = Restaurant::findOrFail($id);
        } else {
            $model = Driver::findOrFail($id);
        }

        $model->status = 1;
        $model->save();

        return back()->with('success', ucfirst($type) . ' approved successfully.');
    }

    public function reject($type, $id)
    {
        if ($type === 'vendor') {
            $model = Restaurant::findOrFail($id);
        } else {
            $model = Driver::findOrFail($id);
        }

        $model->status = 2; // 2 = Rejected
        $model->save();

        return back()->with('success', ucfirst($type) . ' rejected successfully.');
    }
}
