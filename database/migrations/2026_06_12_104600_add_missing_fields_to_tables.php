<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMissingFieldsToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('seat_number')->nullable()->after('bus_id');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->decimal('wallet_balance', 10, 2)->default(0)->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('seat_number');
        });

        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('wallet_balance');
        });
    }
}
