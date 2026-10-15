<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

/**
 * Marks a pre-created ("payment_pending") Order as paid.
 *
 * Used from two independent places so an order can never be stranded
 * as "Razorpay captured the money, QuickDines has no record":
 *   1. CheckoutController::verifyPayment() — the normal browser-side path.
 *   2. RazorpayWebhookController::handle() — a safety net that fires even
 *      if the customer's browser/tab never completes step 1.
 *
 * Both paths call the SAME method, so the "mark paid" logic, the
 * Transaction write and the Settlement creation only exist once.
 */
class OrderPaymentService
{
    /**
     * @param  string|null  $signature  The Razorpay checkout signature. Only the
     *                                  browser path has this; a webhook-confirmed
     *                                  payment is authenticated by the webhook's
     *                                  own HMAC instead, so this can be null.
     * @param  string  $source  'checkout' or 'webhook', stored for debugging only.
     */
    public function markPaid(Order $order, string $paymentId, ?string $signature, string $source = 'checkout'): Order
    {
        // Already paid (race between the browser call and the webhook, or a
        // Razorpay webhook retry): nothing left to do.
        if (strtolower((string) $order->payment_status) === 'paid') {
            return $order;
        }

        // Step 1 (small, critical transaction): the fact that this order was
        // paid must survive even if something later (Settlement) throws.
        DB::transaction(function () use ($order, $paymentId, $signature) {
            $order->payment_status = 'paid';
            $order->payment_method = 'upi';
            $order->razorpay_payment_id = $paymentId;
            if ($signature) {
                $order->razorpay_signature = $signature;
            }
            // Only move a still-pending order into the vendor queue; never
            // downgrade an order a vendor has already started preparing.
            if ($order->order_status === 'payment_pending') {
                $order->order_status = 'pending';
            }
            $order->save();

            Transaction::updateOrCreate(
                [
                    'order_id' => (string) $order->id,
                    'reference_id' => $paymentId,
                ],
                [
                    'user_type' => 'customer',
                    'user_id' => $order->user_id,
                    'amount' => $order->total_amount,
                    'type' => 'credit',
                    'status' => 'completed',
                    'payment_method' => 'razorpay',
                ]
            );
        });

        // Step 2, deliberately OUTSIDE the transaction above: a Settlement
        // failure (bad commission setting, etc.) must never roll back the
        // fact that the order is paid. If this throws, the order stays paid
        // and `php artisan finance:backfill-settlements` can create the settlement later.
        try {
            app(SettlementService::class)->createForOrder($order->fresh());
        } catch (\Throwable $e) {
            report($e);
        }

        return $order->fresh();
    }
}
