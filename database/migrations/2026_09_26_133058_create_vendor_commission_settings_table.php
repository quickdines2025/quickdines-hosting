<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorCommissionSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_commission_settings', function (Blueprint $table) {
            $table->id();
            $table->decimal('example_order_amount', 10, 2)->default(1000);
            $table->decimal('commission_percentage', 5, 2)->default(18);
            $table->decimal('gst_percentage', 5, 2)->default(18);
            $table->decimal('platform_fee', 10, 2)->default(10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vendor_commission_settings');
    }
}
