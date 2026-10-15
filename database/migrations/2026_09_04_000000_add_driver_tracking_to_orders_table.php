<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('driver_assigned_at')->nullable()->after('driver_id');
            $table->timestamp('driver_location_updated_at')->nullable()->after('driver_assigned_at');
            $table->decimal('driver_distance_km', 8, 2)->nullable()->after('driver_location_updated_at');
            $table->unsignedInteger('driver_eta_minutes')->nullable()->after('driver_distance_km');
            $table->timestamp('driver_eta_at')->nullable()->after('driver_eta_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'driver_assigned_at',
                'driver_location_updated_at',
                'driver_distance_km',
                'driver_eta_minutes',
                'driver_eta_at',
            ]);
        });
    }
};
