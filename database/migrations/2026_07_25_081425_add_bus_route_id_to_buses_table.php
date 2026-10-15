<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBusRouteIdToBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->unsignedBigInteger('bus_route_id')->nullable()->after('id');
            $table->foreign('bus_route_id')->references('id')->on('bus_routes')->onDelete('set null');
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
            $table->dropForeign(['bus_route_id']);
            $table->dropColumn('bus_route_id');
        });
    }
}
