<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Order::with(['user', 'restaurant', 'bus', 'items.menuItem'])
            ->where(function ($q) {
                $q->where('payment_method', 'cod')
                    ->orWhere('payment_status', 'paid');
            })
            ->orderBy('created_at', 'desc');

        // Stats
        $stats = [
            'total' => Order::count(),
            'pending' => Order::whereIn('order_status', ['placed', 'pending'])->count(),
            'preparing' => Order::where('order_status', 'preparing')->count(),
            'completed' => Order::whereIn('order_status', ['completed', 'delivered', 'ready'])->count(),
            'cancelled' => Order::where('order_status', 'cancelled')->count(),
        ];

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('mobile', 'like', "%{$search}%");
                  })
                  ->orWhereHas('restaurant', function($rq) use ($search) {
                      $rq->where('restaurant_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('bus', function($bq) use ($search) {
                      $bq->where('bus_number', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        if ($request->filled('restaurant_id')) {
            $query->where('restaurant_id', $request->restaurant_id);
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->export == 'csv') {
            $orders = $query->get();
            $headers = array(
                "Content-type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=orders.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            );

            $columns = array('Order ID', 'Customer Name', 'Customer Mobile', 'Bus Details', 'Restaurant', 'Amount', 'Status', 'Order Time');

            $callback = function() use($orders, $columns) {
                $file = fopen('php://output', 'w');
                fputcsv($file, $columns);

                foreach ($orders as $order) {
                    $busRoute = optional($order->bus)->busRoute ? $order->bus->busRoute->source . ' to ' . $order->bus->busRoute->destination : (optional($order->bus)->route_from . ' to ' . optional($order->bus)->route_to);

                    $row['Order ID']  = '#ORD' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
                    $row['Customer Name']    = optional($order->user)->name ?? 'Guest';
                    $row['Customer Mobile']  = optional($order->user)->mobile ?? 'N/A';
                    $row['Bus Details']  = (optional($order->bus)->bus_number ?? 'N/A') . ' (' . $busRoute . ')';
                    $row['Restaurant']  = optional($order->restaurant)->restaurant_name ?? 'N/A';
                    $row['Amount']  = $order->total_amount;
                    $row['Status']  = $order->order_status;
                    $row['Order Time']  = $order->created_at->format('M d, Y h:i A');

                    fputcsv($file, array($row['Order ID'], $row['Customer Name'], $row['Customer Mobile'], $row['Bus Details'], $row['Restaurant'], $row['Amount'], $row['Status'], $row['Order Time']));
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        $orders = $query->paginate(15);
        $orders->appends($request->all());

        $buses = \App\Models\Bus::select('id', 'bus_number')->get();
        $restaurants = \App\Models\Restaurant::select('id', 'restaurant_name')->get();

        return view('admin.orders.index', compact('orders', 'stats', 'buses', 'restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        // Delete related items first
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', 'Order deleted successfully.');
    }
}
