<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSettingsColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('notify_order_updates')->default(true);
            $table->boolean('notify_promotions')->default(false);
            $table->boolean('notify_email_newsletter')->default(true);
            $table->boolean('two_factor_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'notify_order_updates',
                'notify_promotions',
                'notify_email_newsletter',
                'two_factor_enabled'
            ]);
        });
    }
}
