<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SmsCredit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsCreditSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the first admin user
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $this->command->error('No admin user found. Please create an admin user first.');
            return;
        }

        // Add initial SMS credits to admin account
        $credit = SmsCredit::getOrCreateForUser($adminUser->id);
        $credit->addCredits(500, 'Initial SMS credits for system testing and birthday automation');

        $this->command->info('Added 500 SMS credits to admin account for system operations.');
    }
}
