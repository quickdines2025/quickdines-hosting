<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Settlement;
use App\Models\VendorCommissionSetting;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

/**
 * Single source of truth for the vendor settlement calculation.
 *
 *   commission_amount = customer_paid * commission_percentage / 100
 *   gst_amount        = commission_amount * gst_percentage / 100   (GST only on commission)
 *   vendor_payable    = customer_paid - commission_amount - gst_amount - platform_fee
 */
class SettlementService
{
    /** Same defaults as the vendor_commission_settings migration. */
    private const DEFAULTS = [
        'commission_percentage' => 18.0,
        'gst_percentage'        => 18.0,
        'platform_fee'          => 10.0,
    ];

    public function rates(): array
    {
        $setting = VendorCommissionSetting::first();

        if (!$setting) {
            return self::DEFAULTS;
        }

        return [
            'commission_percentage' => (float) $setting->commission_percentage,
            'gst_percentage'        => (float) $setting->gst_percentage,
            'platform_fee'          => (float) $setting->platform_fee,
        ];
    }

    public function calculate(float $customerPaid, ?array $rates = null): array
    {
        $rates = $rates ?: $this->rates();

        $commission = round($customerPaid * $rates['commission_percentage'] / 100, 2);
        $gst        = round($commission * $rates['gst_percentage'] / 100, 2);
        $platform   = round($rates['platform_fee'], 2);
        $net        = round($customerPaid - $commission - $gst - $platform, 2);

        return [
            'amount'                => round($customerPaid, 2),
            'commission_percentage' => $rates['commission_percentage'],
            'commission_amount'     => $commission,
            'gst_percentage'        => $rates['gst_percentage'],
            'gst_amount'            => $gst,
            'platform_fee'          => $platform,
            'net_amount'            => $net,
        ];
    }

    /**
     * Create the restaurant settlement for a paid order. Idempotent: calling it
     * again for the same order returns the existing settlement untouched, so the
     * percentages recorded at payment time are never rewritten by later setting changes.
     */
    public function createForOrder(Order $order): ?Settlement
    {
        if (strtolower((string) $order->payment_status) !== 'paid' || !$order->restaurant_id) {
            return null;
        }

        return DB::transaction(function () use ($order) {
            $existing = Settlement::where('order_id', $order->id)
                ->where('restaurant_id', $order->restaurant_id)
                ->lockForUpdate()
                ->first();

            if ($existing) {
                return $existing;
            }

            try {
                // total_amount is the server-calculated amount verified against Razorpay.
                return Settlement::create(array_merge(
                    $this->calculate((float) $order->total_amount),
                    [
                        'order_id'      => $order->id,
                        'restaurant_id' => $order->restaurant_id,
                        'status'        => 'pending',
                    ]
                ));
            } catch (QueryException $e) {
                // Concurrent duplicate hit the unique index: return the winner.
                $winner = Settlement::where('order_id', $order->id)
                    ->where('restaurant_id', $order->restaurant_id)
                    ->first();

                if ($winner) {
                    return $winner;
                }
                throw $e;
            }
        });
    }
}
