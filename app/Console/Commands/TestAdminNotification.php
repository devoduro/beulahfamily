<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Models\Member;

class TestAdminNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-admin-notification {--email=ghanabeulahfamily@gmail.com}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test admin notification email for new member registration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $adminEmail = $this->option('email');
        
        $this->info('Testing Admin Notification Email...');
        $this->newLine();
        
        // Display configuration
        $this->info('📧 Email Configuration:');
        $this->line('Mail Driver: ' . config('mail.default'));
        $this->line('From Address: ' . config('mail.from.address'));
        $this->line('From Name: ' . config('mail.from.name'));
        $this->line('Admin Email: ' . $adminEmail);
        $this->newLine();
        
        // Create a test member object
        $testMember = new Member([
            'first_name' => 'John',
            'middle_name' => 'David',
            'last_name' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '0241234567',
            'whatsapp_phone' => '0241234567',
            'date_of_birth' => '1990-05-15',
            'gender' => 'male',
            'marital_status' => 'single',
            'chapter' => 'ACCRA',
            'membership_type' => 'member',
            'occupation' => 'Software Developer',
            'address' => '123 Main Street',
            'city' => 'Accra',
            'approval_status' => 'pending',
        ]);
        
        // Set created_at manually for the test
        $testMember->created_at = now();
        $testMember->id = 999; // Fake ID for testing
        
        $this->info('📤 Sending test email to admin...');
        $this->line('Member: ' . $testMember->first_name . ' ' . $testMember->last_name);
        $this->line('Email: ' . $testMember->email);
        $this->newLine();
        
        try {
            Mail::send('emails.admin-new-registration', [
                'member' => $testMember,
            ], function ($message) use ($testMember, $adminEmail) {
                $message->to($adminEmail)
                    ->subject('🎉 New Member Registration - ' . $testMember->first_name . ' ' . $testMember->last_name . ' (TEST)');
            });
            
            $this->info('✅ Admin notification email sent successfully!');
            $this->newLine();
            $this->info('📬 Check the inbox for: ' . $adminEmail);
            $this->newLine();
            $this->info('Email Details:');
            $this->line('Subject: 🎉 New Member Registration - John David Doe (TEST)');
            $this->line('Contains: Member details, approval buttons, and action links');
            $this->newLine();
            $this->info('🎉 Test completed successfully!');
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            
            return 1;
        }
    }
}
