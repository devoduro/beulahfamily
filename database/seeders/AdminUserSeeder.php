<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Church Administrator',
            'email' => 'admin@church.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        // Create staff user
        User::create([
            'name' => 'Church Staff',
            'email' => 'staff@church.com',
            'password' => Hash::make('password'),
            'role' => User::ROLE_STAFF,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin and staff users created successfully!');
    }
}
