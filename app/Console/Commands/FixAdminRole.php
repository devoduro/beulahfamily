<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FixAdminRole extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-admin-role';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Finds the admin@church.com user and ensures their role is set to \'admin\'.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = 'admin@church.com';
        $user = \App\Models\User::where('email', $adminEmail)->first();

        if ($user) {
            if ($user->role !== \App\Models\User::ROLE_ADMIN) {
                $this->info("Found user {$adminEmail} with incorrect role '{$user->role}'.");
                $user->role = \App\Models\User::ROLE_ADMIN;
                $user->save();
                $this->info("Role updated to '" . \App\Models\User::ROLE_ADMIN . "'.");
            } else {
                $this->info("User {$adminEmail} already has the correct role.");
            }
        } else {
            $this->error("User with email {$adminEmail} not found.");
        }
    }
}
