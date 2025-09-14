<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset the admin user password to "password"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::where('email', 'admin@church.com')->first();
        
        if (!$user) {
            $this->error('Admin user not found!');
            return 1;
        }
        
        $user->password = Hash::make('password');
        $user->save();
        
        $this->info('Admin password has been reset to: password');
        $this->info('You can now login with:');
        $this->info('Email: admin@church.com');
        $this->info('Password: password');
        
        return 0;
    }
}
