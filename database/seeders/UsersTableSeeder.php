<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'first_name' => 'Admin',
            'last_name' => 'User',
            'sex' => 'Male',
            'address' => '123 Main St, City',
            'contact_number' => '123-456-7890',
            'birthdate' => '1990-01-01',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        // Create some demo users
        User::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'sex' => 'Male',
            'address' => '456 Oak St, City',
            'contact_number' => '987-654-3210',
            'birthdate' => '1985-05-15',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'sex' => 'Female',
            'address' => '789 Pine St, City',
            'contact_number' => '555-123-4567',
            'birthdate' => '1992-08-20',
            'email' => 'jane@example.com',
            'password' => Hash::make('password'),
        ]);
    }
}
