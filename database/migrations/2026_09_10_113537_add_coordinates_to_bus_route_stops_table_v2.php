<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoordinatesToBusRouteStopsTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bus_route_stops', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('distance_from_start');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bus_route_stops', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude']);
        });
    }
}
