<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Restaurant;

class AnalyticsController extends Controller
{
    public function download(Request $request)
    {
        $restaurant = Restaurant::where('user_id', auth()->id())->firstOrFail();

        $startDate = $request->start_date
            ?? now()->subDays(6)->format('Y-m-d');

        $endDate = $request->end_date
            ?? now()->format('Y-m-d');

        $orders = Order::where('restaurant_id', $restaurant->id)
            ->whereBetween('created_at', [
                $startDate . ' 00:00:00',
                $endDate . ' 23:59:59'
            ])
            ->get();

        $filename = 'analytics-report-' . $startDate . '-to-' . $endDate . '.csv';

        return response()->streamDownload(function () use ($orders) {

            $handle = fopen('php://output', 'w');

            fputcsv($handle, [
                'Order ID',
                'Date',
                'Total Amount',
                'Payment Method',
                'Payment Status',
                'Order Status'
            ]);

            foreach ($orders as $order) {
                fputcsv($handle, [
                    $order->id,
                    $order->created_at?->format('d-m-Y H:i'),
                    $order->total_amount,
                    $order->payment_method,
                    $order->payment_status,
                    $order->order_status
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}
