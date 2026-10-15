<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\SystemSetting;
use App\Models\Transaction;
use App\Models\Driver;
use App\Services\OrderPaymentService;
use App\Services\SettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    /**
     * Validate the cart on the server and calculate the real amount.
     * The browser's localStorage totals are never trusted for payment.
     */
    private function calculateCart(array $cartData): array
    {
        if (empty($cartData['items']) || !is_array($cartData['items'])) {
            throw new \InvalidArgumentException('Cart is empty!');
        }

        $items = [];
        $restaurantId = null;
        $itemTotal = 0.0;

        foreach ($cartData['items'] as $item) {
            $menuId = (int) ($item['id'] ?? 0);
            $quantity = (int) ($item['qty'] ?? 0);

            if ($menuId < 1 || $quantity < 1) {
                throw new \InvalidArgumentException('Invalid cart item.');
            }

            $menu = Menu::where('availability', 1)->find($menuId);
            if (!$menu) {
                throw new \InvalidArgumentException('One or more food items are no longer available.');
            }

            if ($restaurantId === null) {
                $restaurantId = (int) $menu->restaurant_id;
            } elseif ($restaurantId !== (int) $menu->restaurant_id) {
                throw new \InvalidArgumentException('All cart items must be from the same restaurant.');
            }

            $lineTotal = (float) $menu->price * $quantity;
            $itemTotal += $lineTotal;

            $items[] = [
                'menu' => $menu,
                'quantity' => $quantity,
                'price' => (float) $menu->price,
            ];
        }

        $platformFee = (float) SystemSetting::getSetting('platform_commission', 10);
        $gst = (float) round($itemTotal * 0.05);
        $finalPrice = round($itemTotal + $platformFee + $gst, 2);

        return compact('items', 'restaurantId', 'itemTotal', 'platformFee', 'gst', 'finalPrice');
    }

    /**
     * QR customers may only order from restaurants assigned to their scanned bus.
     */
    private function restaurantAllowedForBus(int $restaurantId): bool
    {
        $busId = session('scanned_bus_id');
        if (!$busId) {
            return true; // normal (non-QR) customer
        }

        $bus = \App\Models\Bus::find($busId);
        if (!$bus) {
            return false;
        }

        return $bus->restaurants()
            ->where('restaurants.status', 1)
            ->where('restaurants.id', $restaurantId)
            ->exists();
    }

    /**
     * Create the Razorpay order AND the local QuickDines Order (status
     * 'payment_pending') before the Razorpay checkout window ever opens.
     *
     * The Order is durable in the database from this point on, so a lost
     * PHP session or a later exception can no longer result in "Razorpay
     * captured the money, QuickDines has no record of it".
     */
    public function createPaymentOrder(Request $request)
    {
        $request->validate([
            'passenger_name' => 'required|string|max:255',
            'phone_number' => ['required', 'regex:/^[0-9]{10}$/'],
            'gender' => 'required|in:Male,Female,Other',
            'passenger_count' => 'required|integer|min:1|max:6',
            'passenger_seats' => 'required|array|min:1',
            'passenger_seats.*.passenger_number' => 'required|integer|min:1',
            'passenger_seats.*.seat_number' => 'required|string|max:255',
            'cooking_instructions' => 'nullable|string|max:1000',
            'cart_data' => 'required|string',
        ], [
            'phone_number.regex' => 'Mobile number must be exactly 10 digits.',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'message' => 'Please login before making a payment.'
            ], 401);
        }

        if (count($request->passenger_seats) !== (int) $request->passenger_count) {
            return response()->json([
                'message' => 'Please enter a seat number for every passenger.'
            ], 422);
        }

        $cartData = json_decode($request->cart_data, true);

        if (!is_array($cartData)) {
            return response()->json([
                'message' => 'Invalid cart data.'
            ], 422);
        }

        try {
            $cart = $this->calculateCart($cartData);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Check restaurant belongs to scanned bus
        |--------------------------------------------------------------------------
        */

        if (!$this->restaurantAllowedForBus((int) $cart['restaurantId'])) {
            return response()->json([
                'message' => 'This restaurant is not available for your bus.'
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | Razorpay Configuration
        |--------------------------------------------------------------------------
        */

        $keyId = config('services.razorpay.key_id');
        $keySecret = config('services.razorpay.key_secret');

        if (!$keyId || !$keySecret) {
            return response()->json([
                'message' => 'Razorpay is not configured. Add RAZORPAY_KEY_ID and RAZORPAY_KEY_SECRET to .env.'
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Bus / Driver
        |--------------------------------------------------------------------------
        */

        $busId = session('scanned_bus_id') ?: auth()->user()->bus_id;

        /*
        |--------------------------------------------------------------------------
        | Passenger Seats
        |--------------------------------------------------------------------------
        */

        $passengerSeats = collect($request->passenger_seats)
            ->map(function ($p) {
                return [
                    'passenger_number' => (int) $p['passenger_number'],
                    'seat_number' => trim($p['seat_number']),
                ];
            })
            ->values()
            ->all();

        /*
        |--------------------------------------------------------------------------
        | Create Razorpay Order ONLY
        |--------------------------------------------------------------------------
        */

        try {

            $razorpayResponse = Http::withBasicAuth(
                $keyId,
                $keySecret
            )
                ->acceptJson()
                ->post(
                    'https://api.razorpay.com/v1/orders',
                    [
                        'amount' => (int) round(
                            $cart['finalPrice'] * 100
                        ),

                        'currency' => 'INR',

                        'receipt' => 'QD-' .
                            auth()->id() .
                            '-' .
                            Str::upper(
                                Str::random(8)
                            ),

                        'notes' => [
                            'restaurant_id' =>
                            (string) $cart['restaurantId'],

                            'bus_id' =>
                            (string) ($busId ?? ''),

                            'user_id' =>
                            (string) auth()->id(),
                        ],
                    ]
                );

            if (!$razorpayResponse->successful()) {

                return response()->json([
                    'message' => 'Unable to create Razorpay order.',

                    'error' =>
                    $razorpayResponse->json(
                        'error.description'
                    ) ?? 'Razorpay API error',

                ], 502);
            }

            $razorpayOrder =
                $razorpayResponse->json();

            /*
            |--------------------------------------------------------------------------
            | Create the QuickDines Order NOW, before the customer ever sees the
            | Razorpay window.
            |--------------------------------------------------------------------------
            |
            | IMPORTANT (production fix):
            | Previously the Order was created only inside verifyPayment(), using
            | data kept in the PHP session across the Razorpay redirect. If that
            | session was lost, or anything after payment threw an exception,
            | Razorpay had captured the money while QuickDines had no Order,
            | no OrderItems and no Transaction at all.
            |
            | The order now exists from this point on, with:
            |   payment_status = 'pending'
            |   order_status   = 'payment_pending'
            | A 'payment_pending' order is never shown in any vendor list (vendor
            | queries only match statuses like placed/preparing/ready/etc.), so
            | this does not put unpaid orders in front of restaurants.
            |
            | verifyPayment() (and the Razorpay webhook, as a second safety net)
            | simply look this same row up by razorpay_order_id and mark it paid.
            | The session copy below is kept only for the /checkout page and as a
            | fallback; it is no longer the only place this data lives.
            */

            $order = DB::transaction(function () use ($cart, $request, $busId, $passengerSeats, $razorpayOrder) {

                $assignedDriver = $busId
                    ? Driver::where('bus_id', $busId)->where('status', 1)->orderBy('id')->first()
                    : null;

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'restaurant_id' => $cart['restaurantId'],
                    'bus_id' => $busId,
                    'driver_id' => $assignedDriver?->id,
                    'driver_assigned_at' => $assignedDriver ? now() : null,

                    'passenger_name' => $request->passenger_name,
                    'phone_number' => $request->phone_number,
                    'gender' => $request->gender,
                    'seat_number' => collect($passengerSeats)->pluck('seat_number')->implode(', '),
                    'passenger_count' => (int) $request->passenger_count,
                    'passenger_seats' => $passengerSeats,
                    'cooking_instructions' => 'Preference: Delivery to my seat | ' . ($request->cooking_instructions ?? ''),

                    'total_amount' => $cart['finalPrice'],

                    'payment_status' => 'pending',
                    'payment_method' => 'upi',
                    'order_status' => 'payment_pending',

                    'razorpay_order_id' => $razorpayOrder['id'],
                ]);

                foreach ($cart['items'] as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $item['menu']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                return $order;
            });

            /*
            |--------------------------------------------------------------------------
            | Secondary copy in session (not load-bearing anymore)
            |--------------------------------------------------------------------------
            */

            session([
                'pending_checkout' => [
                    'order_id' => $order->id,
                    'razorpay_order_id' => $razorpayOrder['id'],
                ],
            ]);

            return response()->json([

                'success' => true,

                'order_id' => $order->id,

                'razorpay_order_id' =>
                $razorpayOrder['id'],

                'amount' =>
                (int) round(
                    $cart['finalPrice'] * 100
                ),

                'currency' => 'INR',

                'key_id' => $keyId,

                'customer' => [

                    'name' =>
                    $request->passenger_name,

                    'contact' =>
                    $request->phone_number,

                    'email' =>
                    auth()->user()->email ?? '',
                ],
            ]);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'message' =>
                'Could not start payment. Please try again.'
            ], 500);
        }
    }


    /**
     * Verify Razorpay payment and mark the already-existing Order as paid.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_order_id' =>
            'required|string|max:255',

            'razorpay_payment_id' =>
            'required|string|max:255',

            'razorpay_signature' =>
            'required|string|max:255',
        ]);

        if (!auth()->check()) {

            return response()->json([
                'message' => 'Unauthenticated.'
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Find the Order (created durably in createPaymentOrder(), BEFORE the
        | customer ever saw the Razorpay window)
        |--------------------------------------------------------------------------
        |
        | This is the core fix: the order no longer depends on the PHP session
        | having survived the Razorpay redirect. It already exists in the
        | database, keyed by razorpay_order_id, so we just look it up.
        */

        $order = Order::where('razorpay_order_id', $request->razorpay_order_id)
            ->where('user_id', auth()->id())
            ->first();

        // Already verified before (duplicate click, or the webhook beat the
        // browser to it): return the same success response, create nothing twice.
        if ($order && strtolower((string) $order->payment_status) === 'paid') {
            session()->forget('pending_checkout');

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect_url' => route('checkout.success', ['id' => $order->id]),
            ]);
        }

        if (!$order) {
            // Should not happen for any checkout started after this fix, since
            // the order is created up front. Kept only in case a checkout that
            // began just before a deploy is still mid-flight.
            return response()->json([
                'message' => 'Order record not found for this payment. If money was deducted, contact support with payment ID ' . $request->razorpay_payment_id . '.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Razorpay Signature
        |--------------------------------------------------------------------------
        */

        $secret =
            config('services.razorpay.key_secret');

        if (!$secret) {

            return response()->json([
                'message' =>
                'Razorpay secret is not configured.'
            ], 500);
        }

        $generatedSignature =
            hash_hmac(
                'sha256',

                $request->razorpay_order_id .
                    '|' .
                    $request->razorpay_payment_id,

                $secret
            );

        if (
            !hash_equals(
                $generatedSignature,
                $request->razorpay_signature
            )
        ) {

            return response()->json([
                'message' =>
                'Payment verification failed.'
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Verify Amount + Status With Razorpay
        |--------------------------------------------------------------------------
        */

        $keyId =
            config('services.razorpay.key_id');

        $keySecret =
            config('services.razorpay.key_secret');

        try {

            $razorpayPaymentResponse =
                Http::withBasicAuth(
                    $keyId,
                    $keySecret
                )
                ->acceptJson()
                ->get(
                    'https://api.razorpay.com/v1/payments/' .
                        $request->razorpay_payment_id
                );

            if (!$razorpayPaymentResponse->successful()) {

                return response()->json([
                    'message' =>
                    'Unable to verify payment with Razorpay.'
                ], 502);
            }

            $payment =
                $razorpayPaymentResponse->json();

            $expectedAmount =
                (int) round(
                    (float) $order->total_amount * 100
                );

            $paidAmount =
                (int) ($payment['amount'] ?? 0);

            if ($paidAmount !== $expectedAmount) {

                return response()->json([
                    'message' =>
                    'Payment amount mismatch.'
                ], 422);
            }

            if (
                ($payment['status'] ?? '') !== 'captured'
            ) {

                return response()->json([
                    'message' =>
                    'Payment has not been captured.'
                ], 422);
            }
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'message' =>
                'Unable to verify payment.'
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | MARK ORDER PAID
        |--------------------------------------------------------------------------
        |
        | The Order row has existed since createPaymentOrder(); this only
        | updates it. A Settlement failure inside markPaid() cannot undo this.
        */

        try {

            $order = app(OrderPaymentService::class)->markPaid(
                $order,
                $request->razorpay_payment_id,
                $request->razorpay_signature,
                'checkout'
            );

            session()->forget('pending_checkout');

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'redirect_url' => route('checkout.success', ['id' => $order->id]),
            ]);
        } catch (\Illuminate\Database\QueryException $e) {

            // Unique index on razorpay_payment_id caught a race with the webhook.
            $order->refresh();

            if (strtolower((string) $order->payment_status) === 'paid') {

                session()->forget('pending_checkout');

                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect_url' => route('checkout.success', ['id' => $order->id]),
                ]);
            }

            report($e);

            return response()->json([
                'message' =>
                'Payment was successful, but we could not confirm the order. Please contact support with payment ID ' . $request->razorpay_payment_id . '.'
            ], 500);
        } catch (\Throwable $e) {

            report($e);

            return response()->json([
                'message' =>
                'Payment was successful, but we could not confirm the order. Please contact support with payment ID ' . $request->razorpay_payment_id . '.'
            ], 500);
        }
    }

    /**
     * Cash on delivery is disabled: every order must be paid online.
     * (Kept only so the form's fallback action never creates an unpaid order.)
     */
    public function process(Request $request)
    {
        return redirect()->route('checkout')
            ->with('error', 'Please use Place Order to pay online.');
    }

    /**
     * Razorpay payment cancelled/failed.
     *
     * No QuickDines Order exists at this stage,
     * so nothing should appear in the vendor panel.
     */
    public function cancelPayment(Request $request)
    {
        if (!auth()->check()) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        // The order already exists (created in createPaymentOrder()). If the
        // customer backs out of the Razorpay window, mark that specific order
        // failed/cancelled instead of leaving it stuck as "payment_pending"
        // forever. Never touch an order that already got paid (e.g. the
        // customer actually paid, closed the Razorpay popup late, and the
        // webhook or the success handler already marked it paid).
        $razorpayOrderId = $request->input('razorpay_order_id')
            ?: session('pending_checkout.razorpay_order_id');

        if ($razorpayOrderId) {
            Order::where('razorpay_order_id', $razorpayOrderId)
                ->where('user_id', auth()->id())
                ->where('payment_status', '!=', 'paid')
                ->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled',
                ]);
        }

        session()->forget('pending_checkout');

        return response()->json([
            'success' => true,
            'message' => 'Payment cancelled.'
        ]);
    }
}
