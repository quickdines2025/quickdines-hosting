<?php

namespace App\Http\Controllers\Support;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupportTicket;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = SupportTicket::with('user')->orderBy('created_at', 'desc')->get();
        return view('support.tickets.index', compact('tickets'));
    }

    public function updateStatus(Request $request, $id)
    {
        $ticket = SupportTicket::findOrFail($id);
        $ticket->status = $request->status;
        $ticket->save();

        return back()->with('success', 'Ticket status updated.');
    }
}
