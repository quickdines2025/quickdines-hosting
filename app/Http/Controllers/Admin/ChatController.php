<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;

class ChatController extends Controller
{
    public function index()
    {
        return view('admin.chat.index');
    }

    public function fetchUsers()
    {
        // Get users who have messages
        $userIds = Message::select('user_id')->distinct()->pluck('user_id');
        
        $users = User::whereIn('id', $userIds)->where('role', '!=', 'support')->get()->map(function($user) {
            $latestMessage = Message::where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            $unreadCount = Message::where('user_id', $user->id)->where('is_admin', false)->where('is_read', false)->count();
            
            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role,
                'latest_message_time' => $latestMessage ? $latestMessage->created_at->timestamp : 0,
                'unread_count' => $unreadCount,
                'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=random&color=fff'
            ];
        })->sortByDesc('latest_message_time')->values();
        
        return response()->json($users);
    }

    public function fetchMessages($user_id)
    {
        $messages = Message::where('user_id', $user_id)->orderBy('created_at', 'asc')->get();
        
        // mark user messages as read
        Message::where('user_id', $user_id)->where('is_admin', false)->where('is_read', false)->update(['is_read' => true]);
        
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $user_id)
    {
        $request->validate(['message' => 'required|string']);

        $message = Message::create([
            'user_id' => $user_id,
            'is_admin' => true,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($message);
    }
    public function deleteChat($user_id)
    {
        Message::where('user_id', $user_id)->delete();
        return response()->json(['success' => true]);
    }
}
