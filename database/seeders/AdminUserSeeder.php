<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Super Admin',
            'mobile' => '9999999999',
            'role' => 'admin',
            'password' => Hash::make('admin123'),
        ]);
    }
}
