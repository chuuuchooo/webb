<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CreateAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user with default credentials';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Check if admin already exists
        $admin = User::where('email', 'admin@bhw')->first();

        if (!$admin) {
            // Create admin user
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
            
            $this->info('Admin user created successfully!');
            $this->info('Email: admin@bhw');
            $this->info('Password: admin123');
        } else {
            $this->info('Admin user already exists!');
        }

        return Command::SUCCESS;
    }
}
