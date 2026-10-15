<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bus;
use App\Models\Order;
use App\Models\SupportTicket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Order Stats
        $totalOrders = Order::count();
        $completedOrders = Order::where('order_status', 'delivered')->count();
        $pendingOrders = Order::whereIn('order_status', ['pending', 'accepted', 'preparing'])->count();
        $cancelledOrders = Order::where('order_status', 'cancelled')->count();
        
        // 2. Ticket Stats
        $openTicketsCount = SupportTicket::where('status', 'open')->count();
        $recentTickets = SupportTicket::with('customer')->orderBy('created_at', 'desc')->take(5)->get();
        
        // 3. Ticket Status Overview (Donut Chart Data)
        $ticketStatusData = [
            'open' => SupportTicket::where('status', 'open')->count(),
            'in_progress' => SupportTicket::where('status', 'in progress')->count(),
            'pending' => SupportTicket::where('status', 'pending')->count(),
            'resolved' => SupportTicket::where('status', 'resolved')->count(),
            'closed' => SupportTicket::where('status', 'closed')->count(),
        ];
        
        // 4. Support Performance (Line Chart Data for last 7 days)
        $performanceData = ['labels' => [], 'received' => [], 'resolved' => []];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $performanceData['labels'][] = $date->format('d M');
            $performanceData['received'][] = SupportTicket::whereDate('created_at', $date)->count();
            $performanceData['resolved'][] = SupportTicket::whereDate('updated_at', $date)->whereIn('status', ['resolved', 'closed'])->count();
        }

        // 5. Live Bus Tracking (All Active Buses)
        $activeBuses = Bus::where('status', 1)
                            ->with(['busRoute.stops', 'currentStop'])
                            ->orderBy('journey_delay_minutes', 'desc') // Put delayed buses first
                            ->get();

        return view('support.dashboard', compact(
            'totalOrders', 'completedOrders', 'pendingOrders', 'cancelledOrders', 
            'openTicketsCount', 'recentTickets', 'ticketStatusData', 'performanceData',
            'activeBuses'
        ));
    }

    public function checkNotifications()
    {
        $newTickets = SupportTicket::where('admin_read', false)->count();
        $pendingTasks = \App\Models\Order::where('order_status', 'pending')->count() +
                       \App\Models\Restaurant::where('status', 0)->count() +
                       \App\Models\Driver::where('status', 0)->count();
                       
        // Messages directed to Support Team
        $newMessages = \App\Models\Message::where('user_id', auth()->id())
                                ->where('is_admin', true)
                                ->where('is_read', false)
                                ->count();
                                
        return response()->json([
            'tickets' => $newTickets,
            'tasks' => $pendingTasks,
            'messages' => $newMessages,
            'total' => $newTickets + $pendingTasks + $newMessages
        ]);
    }

    public function notifyDelay(Request $request, $bus_id)
    {
        $bus = Bus::findOrFail($bus_id);
        return back()->with('success', "Restaurants expecting Bus {$bus->bus_number} have been notified of the delay.");
    }

    public function cancellations()
    {
        $cancelledOrders = Order::with(['user', 'restaurant'])->where('order_status', 'cancelled')->orderBy('updated_at', 'desc')->paginate(15);
        return view('support.cancellations', compact('cancelledOrders'));
    }
    
    public function deleteCancellation($id)
    {
        $order = Order::findOrFail($id);
        if ($order->order_status === 'cancelled') {
            $order->delete();
            return back()->with('success', 'Cancelled order deleted successfully.');
        }
        return back()->with('error', 'Cannot delete this order.');
    }

    public function refunds()
    {
        $pendingRefunds = \App\Models\SupportTicket::with(['customer', 'order', 'order.restaurant'])
            ->where('category', 'Refund')
            ->where('status', 'open')
            ->orderBy('created_at', 'desc')
            ->paginate(15, ['*'], 'pending_page');
            
        $processedRefunds = \App\Models\SupportTicket::with(['customer', 'order', 'order.restaurant'])
            ->where('category', 'Refund')
            ->whereIn('status', ['resolved', 'closed'])
            ->whereNotNull('refund_amount')
            ->orderBy('updated_at', 'desc')
            ->paginate(15, ['*'], 'processed_page');
            
        $totalRefundsGiven = \App\Models\SupportTicket::where('category', 'Refund')->whereNotNull('refund_amount')->count();
        $totalRefundAmount = \App\Models\SupportTicket::where('category', 'Refund')->sum('refund_amount');
        
        return view('support.refunds', compact('pendingRefunds', 'processedRefunds', 'totalRefundsGiven', 'totalRefundAmount'));
    }

    public function processRefund(Request $request, $id)
    {
        $ticket = \App\Models\SupportTicket::findOrFail($id);
        
        if ($ticket->category === 'Refund' && $ticket->status === 'open') {
            $request->validate([
                'refund_amount' => 'required|numeric|min:0'
            ]);
            
            $ticket->update([
                'status' => 'resolved',
                'refund_amount' => $request->refund_amount
            ]);
            
            if ($ticket->order) {
                $ticket->order->update(['payment_status' => 'refunded']);
            }
            
            return back()->with('success', 'Refund approved and recorded successfully.');
        }
        return back()->with('error', 'This ticket is not eligible for a refund.');
    }
    
    public function rejectRefund($id)
    {
        $ticket = \App\Models\SupportTicket::findOrFail($id);
        
        if ($ticket->category === 'Refund' && $ticket->status === 'open') {
            $ticket->update([
                'status' => 'closed',
            ]);
            
            return back()->with('success', 'Refund request rejected.');
        }
        return back()->with('error', 'This ticket cannot be rejected.');
    }
    
    public function orderVerification()
    {
        $pendingOrders = Order::with(['user', 'restaurant'])->whereNotIn('order_status', ['completed', 'cancelled'])->orderBy('created_at', 'desc')->paginate(15);
        return view('support.order_verification', compact('pendingOrders'));
    }
    
    public function verifyOrder($id)
    {
        $order = Order::findOrFail($id);
        if ($order->order_status === 'pending') {
            $order->order_status = 'placed';
            $order->save();
            return back()->with('success', 'Order verified and forwarded to the restaurant.');
        }
        return back()->with('error', 'Order cannot be verified.');
    }
}

