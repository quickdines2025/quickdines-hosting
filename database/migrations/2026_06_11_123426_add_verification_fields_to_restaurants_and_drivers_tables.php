<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationFieldsToRestaurantsAndDriversTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('document_path')->nullable()->after('status');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->boolean('status')->default(0)->after('mobile'); // 0=pending, 1=approved
            $table->string('document_path')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('document_path');
        });

        Schema::table('drivers', function (Blueprint $table) {
            $table->dropColumn(['status', 'document_path']);
        });
    }
}
