<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

// Check if admin already exists
$admin = User::where('email', 'admin@bhw')->first();

if (!$admin) {
    // Create admin user
    $admin = User::create([
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
    
    echo "Admin user created successfully!\n";
    echo "Email: admin@bhw\n";
    echo "Password: admin123\n";
} else {
    echo "Admin user already exists!\n";
} 