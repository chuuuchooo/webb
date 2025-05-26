<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'sex' => 'Male',
            'address' => 'Health Center',
            'contact_number' => '09123456789',
            'birthdate' => Carbon::now()->subYears(30),
            'email' => 'admin@bhw',
            'password' => Hash::make('admin123'),
            'isAdmin' => true,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
