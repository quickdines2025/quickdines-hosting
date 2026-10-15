<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->string('bus_name')->nullable()->after('bus_number');
            $table->string('operator')->nullable()->after('bus_name');
            $table->string('route_from')->nullable()->after('operator');
            $table->string('route_to')->nullable()->after('route_from');
            $table->time('departure_time')->nullable()->after('route_to');
            $table->time('arrival_time')->nullable()->after('departure_time');
            $table->string('driver_name')->nullable()->after('arrival_time');
            $table->string('driver_phone')->nullable()->after('driver_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->dropColumn([
                'bus_name',
                'operator',
                'route_from',
                'route_to',
                'departure_time',
                'arrival_time',
                'driver_name',
                'driver_phone'
            ]);
        });
    }
}
