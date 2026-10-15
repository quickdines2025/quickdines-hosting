<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAdvancedFieldsToBusRouteStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bus_route_stops', function (Blueprint $table) {
            $table->time('arrival_time')->nullable()->after('expected_time_from_start');
            $table->time('departure_time')->nullable()->after('arrival_time');
            $table->integer('stay_duration_mins')->nullable()->after('departure_time');
            $table->decimal('distance_from_start', 8, 2)->nullable()->after('stay_duration_mins');
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
            $table->dropColumn(['arrival_time', 'departure_time', 'stay_duration_mins', 'distance_from_start']);
        });
    }
}
