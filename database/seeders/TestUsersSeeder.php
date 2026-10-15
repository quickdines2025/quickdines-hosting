<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Driver;
use App\Models\Restaurant;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('users')->truncate();
        DB::table('drivers')->truncate();
        DB::table('restaurants')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Admin
        User::create([
            'name' => 'Admin User',
            'mobile' => '9999999999',
            'email' => 'admin@quickdines.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        // 2. Driver
        $driverUser = User::create([
            'name' => 'ramuuu',
            'mobile' => '8888888888',
            'email' => 'driver@quickdines.com',
            'password' => Hash::make('driver123'),
            'role' => 'driver',
            'status' => 'active'
        ]);
        Driver::create([
            'user_id' => $driverUser->id,
            'name' => 'ramuuu',
            'mobile' => '8888888888',
            'status' => 1,
            'rating' => 4.8,
            'rating_count' => 12
        ]);

        // 3. Vendor
        $vendorUser = User::create([
            'name' => 'Paradise Hotel',
            'mobile' => '7777777777',
            'email' => 'vendor@quickdines.com',
            'password' => Hash::make('vendor123'),
            'role' => 'vendor',
            'status' => 'active'
        ]);
        Restaurant::create([
            'user_id' => $vendorUser->id,
            'restaurant_name' => 'Paradise Hotel',
            'owner_name' => 'Paradise Owner',
            'city' => 'visakhapatnam',
            'mobile' => '7777777777',
            'status' => 1
        ]);
    }
}
