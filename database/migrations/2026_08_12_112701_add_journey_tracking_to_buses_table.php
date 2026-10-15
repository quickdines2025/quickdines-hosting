<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddJourneyTrackingToBusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('buses', function (Blueprint $table) {
            $table->timestamp('journey_start_time')->nullable()->after('journey_updated_at');
            $table->integer('journey_delay_minutes')->default(0)->after('journey_start_time');
            $table->foreignId('current_stop_id')->nullable()->constrained('bus_route_stops')->onDelete('set null')->after('journey_delay_minutes');
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
            $table->dropForeign(['current_stop_id']);
            $table->dropColumn(['journey_start_time', 'journey_delay_minutes', 'current_stop_id']);
        });
    }
}
