<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class ChatController extends Controller
{
    public function fetchMessages()
    {
        $messages = Message::where('user_id', auth()->id())->orderBy('created_at', 'asc')->get();
        // mark admin messages as read
        Message::where('user_id', auth()->id())->where('is_admin', true)->where('is_read', false)->update(['is_read' => true]);
        
        return response()->json($messages);
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'user_id' => auth()->id(),
            'is_admin' => false,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message);
    }
}
