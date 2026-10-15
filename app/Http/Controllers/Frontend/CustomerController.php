<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Favorite;

class CustomerController extends Controller
{
    public function trackOrder()
    {
        // Get the latest active order
        $order = Order::with(['restaurant', 'bus', 'items'])
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed', 'preparing', 'ready', 'out_for_delivery'])
            ->orderBy('created_at', 'desc')
            ->first();

        return view('frontend.track-order', compact('order'));
    }

    public function favourites()
    {
        $favorites = Favorite::with('menu.restaurant')
            ->where('user_id', auth()->id())
            ->get();
            
        return view('frontend.favorites', compact('favorites'));
    }

    public function toggleFavorite(Request $request)
    {
        $request->validate([
            'menu_id' => 'required|exists:menus,id'
        ]);

        $favorite = \App\Models\Favorite::where('user_id', auth()->id())
            ->where('menu_id', $request->menu_id)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            \App\Models\Favorite::create([
                'user_id' => auth()->id(),
                'menu_id' => $request->menu_id
            ]);
            return response()->json(['status' => 'added']);
        }
    }

    public function logout(Request $request)
    {
        \Illuminate\Support\Facades\Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->name = $request->name;
        $user->mobile = $request->mobile;
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('dob')) {
            $user->dob = $request->dob;
        }
        if ($request->has('gender')) {
            $user->gender = $request->gender;
        }
        
        if ($request->hasFile('avatar')) {
            // Delete old avatar if needed (optional)
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }
        
        $user->save();

        return redirect()->back()->with('success', 'Profile updated successfully!');
    }

    public function updateNotifications(Request $request)
    {
        $user = auth()->user();
        $type = $request->type;
        $status = $request->status == 'true';
        
        if (in_array($type, ['notify_order_updates', 'notify_promotions', 'notify_email_newsletter'])) {
            $user->$type = $status;
            $user->save();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        if (!\Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user = auth()->user();
        $user->password = \Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }

    public function toggle2FA(Request $request)
    {
        $user = auth()->user();
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();
        return back()->with('success', 'Two-Factor Authentication settings updated!');
    }

    public function logoutDevices(Request $request)
    {
        \Auth::logout();
        return redirect('/')->with('success', 'Logged out successfully.');
    }

    public function deleteAccount(Request $request)
    {
        $user = auth()->user();
        \Auth::logout();
        $user->delete();
        
        return redirect('/')->with('success', 'Your account has been deleted.');
    }

    public function tickets()
    {
        $tickets = \App\Models\SupportTicket::where('customer_id', auth()->id())->latest()->get();
        $recentOrders = \App\Models\Order::with('restaurant')->where('user_id', auth()->id())->latest()->take(10)->get();
        return view('frontend.support-tickets', compact('tickets', 'recentOrders'));
    }

    public function storeTicket(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'priority' => 'required',
            'issue' => 'required',
            'order_id' => 'nullable|exists:orders,id'
        ]);

        \App\Models\SupportTicket::create([
            'ticket_number' => 'TKT-' . strtoupper(\Illuminate\Support\Str::random(6)),
            'customer_id' => auth()->id(),
            'order_id' => $request->category === 'Refund' ? $request->order_id : null,
            'category' => $request->category,
            'priority' => $request->priority,
            'issue' => $request->issue,
            'status' => 'open'
        ]);

        return back()->with('success', 'Your support ticket has been submitted successfully. Our team will review it shortly.');
    }

    public function storeReview(Request $request, $orderId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_text' => 'nullable|string|max:1000'
        ]);

        $order = Order::where('user_id', auth()->id())->findOrFail($orderId);

        \App\Models\Review::updateOrCreate(
            ['order_id' => $order->id, 'user_id' => auth()->id()],
            [
                'restaurant_id' => $order->restaurant_id,
                'rating' => $request->rating,
                'review_text' => $request->review_text
            ]
        );

        return back()->with('success', 'Thank you for your review!');
    }

    public function cancelOrder(Request $request, $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        
        if ($order->order_status == 'pending') {
            $order->order_status = 'cancelled';
            $order->save();
            return redirect()->route('dashboard')->with('success', 'Order cancelled successfully.');
        }
        
        return redirect()->route('dashboard')->with('error', 'Order cannot be cancelled at this stage.');
    }

    public function checkNotifications()
    {
        $user = auth()->user();
        if (!$user) return response()->json(['messages' => 0, 'order_update' => null]);

        $newMessagesCount = \App\Models\Message::where('user_id', $user->id)
                                ->where('is_admin', true)
                                ->where('is_read', false)
                                ->count();

        $latestOrderUpdate = Order::where('user_id', $user->id)
                                ->max('updated_at');

        return response()->json([
            'messages' => $newMessagesCount,
            'order_update' => $latestOrderUpdate
        ]);
    }
}
