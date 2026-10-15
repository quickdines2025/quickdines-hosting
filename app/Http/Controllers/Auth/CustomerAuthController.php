<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function showAuthForm()
    {
        if (Auth::check()) {
            return session('scanned_bus_id') ? redirect()->route('qr.entry') : redirect('/');
        }
        return view('auth.customer-otp');
    }
    public function sendOtp(Request $request)
    {
        $request->validate(['name' => ['nullable', 'string', 'max:255'], 'mobile' => ['required', 'string', 'max:15'],]);
        $mobile = $request->mobile;
        $otp = '1234';
        Cache::put('otp_' . $mobile, $otp, now()->addMinutes(10));
        Cache::put('otp_name_' . $mobile, $request->name, now()->addMinutes(10));
        return response()->json(['success' => true, 'message' => 'OTP sent successfully! (Use 1234 for testing)', 'mobile' => $mobile,]);
    }
    public function verifyOtp(Request $request)
    {
        $request->validate(['name' => ['nullable', 'string', 'max:255'], 'mobile' => ['required', 'string', 'max:15'], 'otp' => ['required', 'string', 'max:6'],]); /* |-------------------------------------------------------------------------- | OTP Verification |-------------------------------------------------------------------------- | Temporary testing OTP: 1234 */
        $mobile = $request->mobile;
        $enteredOtp = $request->otp;
        if ($enteredOtp !== '1234') {
            return response()->json(['success' => false, 'message' => 'Invalid OTP.',], 422);
        } /* |-------------------------------------------------------------------------- | Get Customer Name |-------------------------------------------------------------------------- */
        $name = $request->name ?: Cache::get('otp_name_' . $mobile) ?: 'Passenger';
        Cache::forget('otp_' . $mobile);
        Cache::forget('otp_name_' . $mobile); /* |-------------------------------------------------------------------------- | Find Existing User |-------------------------------------------------------------------------- | Mobile number is unique in the users table. | Therefore, check mobile without restricting the role. */
        $user = User::where('mobile', $mobile)->first(); /* |-------------------------------------------------------------------------- | Prevent Customer Login With Admin/Vendor Mobile |-------------------------------------------------------------------------- */
        if ($user && $user->role !== 'customer') {
            return response()->json(['success' => false, 'message' => 'This mobile number is already registered with another account.',], 422);
        } /* |-------------------------------------------------------------------------- | Create New Customer |-------------------------------------------------------------------------- */
        if (!$user) {
            $user = User::create(['name' => $name, 'mobile' => $mobile, 'role' => 'customer', 'password' => bcrypt(Str::random(32)), 'bus_id' => session('scanned_bus_id'),]);
        } /* |-------------------------------------------------------------------------- | Existing Customer |-------------------------------------------------------------------------- */ else {
            $updates = [];
            if ($name && $name !== 'Passenger') {
                $updates['name'] = $name;
            }
            if (session()->has('scanned_bus_id')) {
                $updates['bus_id'] = session('scanned_bus_id');
            }
            if (!empty($updates)) {
                $user->update($updates);
            }
        } /* |-------------------------------------------------------------------------- | Login Customer |-------------------------------------------------------------------------- */
        Auth::login($user, true); /* |-------------------------------------------------------------------------- | Redirect After Login |-------------------------------------------------------------------------- */
        return response()->json(['success' => true, 'message' => 'Registration/Login successful!', 'redirect' => session('scanned_bus_id') ? route('qr.entry') : url('/'),]);
    }
}
