<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDetailsToRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string('cuisine')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('discount')->nullable();
            $table->decimal('rating', 2, 1)->default(4.0);
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
            $table->dropColumn(['image', 'cuisine', 'delivery_time', 'discount', 'rating']);
        });
    }
}
