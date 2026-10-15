<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => ['required', 'in:customer,vendor,driver'],
            'name' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'document' => ['required_if:role,vendor,driver', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048']
        ]);

        $user = User::create([
            'name' => $request->name,
            'mobile' => $request->mobile,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role === 'customer') {
            event(new Registered($user));
            Auth::login($user);
            return redirect(RouteServiceProvider::HOME);
        }

        // For Vendor and Driver
        $path = $request->file('document')->store('documents', 'public');

        if ($request->role === 'vendor') {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('restaurants', 'public');
            }

            \App\Models\Restaurant::create([
                'user_id' => $user->id,
                'restaurant_name' => $request->name,
                'owner_name' => $request->name,
                'mobile' => $request->mobile,
                'status' => 0, // Pending
                'document_path' => $path,
                'image' => $imagePath,
                'city' => $request->city,
                'address' => $request->address,
                'cuisine' => $request->cuisine,
                'delivery_time' => $request->delivery_time,
                'discount' => $request->discount
            ]);
        } else if ($request->role === 'driver') {
            \App\Models\Driver::create([
                'user_id' => $user->id,
                'name' => $request->name,
                'mobile' => $request->mobile,
                'status' => 0, // Pending
                'document_path' => $path
            ]);
        }

        return redirect()->route('login')->with('success', 'Registration successful! Your application is pending verification by the admin. We will notify you once approved.');
    }
}
