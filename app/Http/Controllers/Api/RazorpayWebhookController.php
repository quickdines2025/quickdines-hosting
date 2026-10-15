<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\OrderPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Safety net for the exact failure the production incident described:
 * Razorpay captured the money, but the customer's browser never finished
 * calling CheckoutController::verifyPayment() (tab closed, network drop,
 * JS error, the phone's browser killed in the background, etc.).
 *
 * Razorpay calls this URL directly from its own servers whenever a payment
 * event happens, independent of the customer's device, and retries it until
 * it gets a 2xx response. Configure it in the Razorpay Dashboard under
 * Settings -> Webhooks:
 *   URL:    https://<your-domain>/api/webhooks/razorpay
 *   Events: payment.captured, payment.failed
 *   Secret: must match RAZORPAY_WEBHOOK_SECRET in .env
 *
 * This route lives under /api so it is never blocked by CSRF verification
 * (Razorpay cannot send a Laravel CSRF token).
 */
class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $secret = config('services.razorpay.webhook_secret');

        if (!$secret) {
            // Not configured yet: accept with 200 so Razorpay does not retry
            // forever, but log loudly so this gets noticed and fixed.
            Log::warning('Razorpay webhook received but RAZORPAY_WEBHOOK_SECRET is not set.');
            return response()->json(['status' => 'ignored'], 200);
        }

        $rawBody = $request->getContent();
        $signature = $request->header('X-Razorpay-Signature');

        if (!$signature || !hash_equals(hash_hmac('sha256', $rawBody, $secret), $signature)) {
            Log::warning('Razorpay webhook signature mismatch.', ['ip' => $request->ip()]);
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        $payload = json_decode($rawBody, true);
        $event = $payload['event'] ?? null;
        $paymentEntity = $payload['payload']['payment']['entity'] ?? null;

        if (!$paymentEntity || empty($paymentEntity['order_id'])) {
            // Not a payment event we care about (or malformed): acknowledge
            // so Razorpay stops retrying, do nothing.
            return response()->json(['status' => 'ignored'], 200);
        }

        $order = Order::where('razorpay_order_id', $paymentEntity['order_id'])->first();

        if (!$order) {
            // A payment for an order_id QuickDines never created. Should not
            // happen once every checkout pre-creates its Order, but log it
            // so a captured payment is never silently lost from view.
            Log::error('Razorpay webhook: no matching QuickDines order.', [
                'event' => $event,
                'razorpay_order_id' => $paymentEntity['order_id'],
                'razorpay_payment_id' => $paymentEntity['id'] ?? null,
            ]);
            return response()->json(['status' => 'order_not_found'], 200);
        }

        if ($event === 'payment.captured') {
            try {
                app(OrderPaymentService::class)->markPaid(
                    $order,
                    $paymentEntity['id'],
                    null, // no checkout-side signature available via webhook
                    'webhook'
                );
            } catch (\Throwable $e) {
                report($e);
                // Still 200: Razorpay already captured the money; retrying
                // this webhook will not change that. The error is logged
                // above and in markPaid()'s own settlement try/catch.
            }
        } elseif ($event === 'payment.failed') {
            if (strtolower((string) $order->payment_status) !== 'paid') {
                $order->update([
                    'payment_status' => 'failed',
                    'order_status' => 'cancelled',
                ]);
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
