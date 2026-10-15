<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Settlement;
use App\Models\Transaction;
use App\Services\SettlementService;
use Illuminate\Console\Command;

class BackfillSettlements extends Command
{
    protected $signature = 'finance:backfill-settlements {--dry-run : Only report, create nothing}';

    protected $description = 'Create missing settlements for paid orders and missing payout records for settled settlements (idempotent).';

    public function handle(SettlementService $service)
    {
        $created = 0;

        Order::whereRaw('LOWER(payment_status) = ?', ['paid'])
            ->whereNotNull('razorpay_payment_id')
            ->whereNotExists(function ($q) {
                $q->selectRaw('1')->from('settlements')
                    ->whereColumn('settlements.order_id', 'orders.id')
                    ->whereColumn('settlements.restaurant_id', 'orders.restaurant_id');
            })
            ->orderBy('id')
            ->chunkById(200, function ($orders) use ($service, &$created) {
                foreach ($orders as $order) {
                    if (!$this->option('dry-run')) {
                        $service->createForOrder($order);
                    }
                    $created++;
                }
            });

        $this->info(($this->option('dry-run') ? 'Would create ' : 'Created ') . $created . ' settlement(s).');

        // Settled settlements that have no payout record yet (so Payout History shows them).
        $payouts = 0;

        Settlement::where('status', 'completed')
            ->whereNotNull('restaurant_id')
            ->orderBy('id')
            ->chunkById(200, function ($settlements) use (&$payouts) {
                foreach ($settlements as $settlement) {
                    $exists = Transaction::where('type', 'payout')
                        ->where('user_type', 'restaurant')
                        ->where('user_id', $settlement->restaurant_id)
                        ->where('order_id', (string) $settlement->order_id)
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    if (!$this->option('dry-run')) {
                        $tx = Transaction::create([
                            'order_id' => $settlement->order_id,
                            'user_type' => 'restaurant',
                            'user_id' => $settlement->restaurant_id,
                            'amount' => $settlement->net_amount,
                            'type' => 'payout',
                            'status' => 'completed',
                            'payment_method' => 'bank_transfer',
                            'reference_id' => $settlement->reference_id,
                        ]);
                        // Show the real settlement date, not today.
                        if ($settlement->settled_at) {
                            $tx->created_at = $settlement->settled_at;
                            $tx->updated_at = $settlement->settled_at;
                            $tx->save();
                        }
                    }
                    $payouts++;
                }
            });

        $this->info(($this->option('dry-run') ? 'Would create ' : 'Created ') . $payouts . ' payout record(s).');

        return 0;
    }
}
