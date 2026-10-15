<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

/**
 * WHY THIS MIGRATION EXISTS
 * -------------------------
 * The original create_settlements_table migration created the columns
 * `order_amount` and `restaurant_amount`, but the Settlement model and
 * SettlementController already read/write `amount`, `net_amount`,
 * `commission_percentage`, `gst_percentage`, `driver_id`, `reference_id`
 * and `settled_at`. Those columns were never created by any migration.
 *
 * This migration is defensive: it only renames/adds what is missing, so it is
 * safe whether or not your live database was already patched by hand.
 *
 * It also adds two unique indexes so that repeated payment callbacks can never
 * create a duplicate settlement or a duplicate order for the same Razorpay payment.
 */
return new class extends Migration
{
    public function up()
    {
        // 1. Rename legacy columns (raw SQL: Laravel 8 needs doctrine/dbal for renameColumn).
        if (!Schema::hasColumn('settlements', 'amount') && Schema::hasColumn('settlements', 'order_amount')) {
            DB::statement('ALTER TABLE settlements CHANGE order_amount amount DECIMAL(10,2) NOT NULL DEFAULT 0');
        }
        if (!Schema::hasColumn('settlements', 'net_amount') && Schema::hasColumn('settlements', 'restaurant_amount')) {
            DB::statement('ALTER TABLE settlements CHANGE restaurant_amount net_amount DECIMAL(10,2) NOT NULL DEFAULT 0');
        }

        // 2. Add anything still missing.
        Schema::table('settlements', function (Blueprint $table) {
            if (!Schema::hasColumn('settlements', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('settlements', 'net_amount')) {
                $table->decimal('net_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('settlements', 'commission_percentage')) {
                $table->decimal('commission_percentage', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('settlements', 'gst_percentage')) {
                $table->decimal('gst_percentage', 5, 2)->default(0);
            }
            if (!Schema::hasColumn('settlements', 'driver_id')) {
                $table->unsignedBigInteger('driver_id')->nullable();
            }
            if (!Schema::hasColumn('settlements', 'reference_id')) {
                $table->string('reference_id')->nullable();
            }
            if (!Schema::hasColumn('settlements', 'settled_at')) {
                $table->timestamp('settled_at')->nullable();
            }
        });

        // 3. One settlement per (order, restaurant).
        $this->addUnique('settlements', 'settlements_order_restaurant_unique', ['order_id', 'restaurant_id']);

        // 4. One order per Razorpay payment (NULLs are allowed to repeat).
        $this->addUnique('orders', 'orders_razorpay_payment_id_unique', ['razorpay_payment_id']);
    }

    public function down()
    {
        Schema::table('settlements', function (Blueprint $table) {
            $table->dropUnique('settlements_order_restaurant_unique');
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->dropUnique('orders_razorpay_payment_id_unique');
        });
        // Renamed/added columns are intentionally left in place.
    }

    private function addUnique(string $tableName, string $indexName, array $columns): void
    {
        try {
            $exists = DB::select(
                'SELECT 1 FROM information_schema.statistics WHERE table_schema = DATABASE() AND table_name = ? AND index_name = ? LIMIT 1',
                [$tableName, $indexName]
            );
            if ($exists) {
                return;
            }
            Schema::table($tableName, function (Blueprint $table) use ($columns, $indexName) {
                $table->unique($columns, $indexName);
            });
        } catch (\Throwable $e) {
            // Most likely existing duplicate rows. Do not break the whole migration.
            Log::warning("Could not add unique index {$indexName} on {$tableName}: " . $e->getMessage());
        }
    }
};
