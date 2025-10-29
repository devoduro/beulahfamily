<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use Carbon\Carbon;

class TestBirthdaySms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test-birthday-setup {phone} {--name=TestUser}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a test member with today\'s birthday and test the birthday SMS system';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $name = $this->option('name');
        $today = Carbon::today();
        
        $this->info('🎂 Birthday SMS Testing Setup');
        $this->newLine();
        
        // Check if test member already exists
        $testEmail = 'birthday.test@example.com';
        $existingMember = Member::where('email', $testEmail)->first();
        
        if ($existingMember) {
            $this->warn('Test member already exists. Updating...');
            
            // Update birthday to today
            $birthYear = $today->year - 25; // Make them 25 years old
            $birthdayDate = Carbon::create($birthYear, $today->month, $today->day);
            
            $existingMember->update([
                'first_name' => $name,
                'phone' => $phone,
                'date_of_birth' => $birthdayDate,
                'is_active' => true,
                'receive_sms' => true
            ]);
            
            $testMember = $existingMember;
            $this->info('✓ Test member updated');
        } else {
            $this->info('Creating test member with birthday today...');
            
            // Create birthday date (25 years ago today)
            $birthYear = $today->year - 25;
            $birthdayDate = Carbon::create($birthYear, $today->month, $today->day);
            
            // Create test member
            $testMember = Member::create([
                'first_name' => $name,
                'last_name' => 'Birthday',
                'email' => $testEmail,
                'phone' => $phone,
                'date_of_birth' => $birthdayDate,
                'gender' => 'male',
                'chapter' => 'ACCRA',
                'membership_type' => 'member',
                'membership_status' => 'active',
                'approval_status' => 'approved',
                'is_active' => true,
                'receive_sms' => true,
                'password' => bcrypt('password123')
            ]);
            
            $this->info('✓ Test member created');
        }
        
        $this->newLine();
        $this->info('📋 Test Member Details:');
        $this->line('Name: ' . $testMember->first_name . ' ' . $testMember->last_name);
        $this->line('Email: ' . $testMember->email);
        $this->line('Phone: ' . $testMember->phone);
        $this->line('Birthday: ' . $testMember->date_of_birth->format('F d, Y'));
        $this->line('Age: ' . $testMember->date_of_birth->age . ' years old');
        $this->line('Today: ' . $today->format('F d, Y'));
        $this->line('Birthday Match: ' . ($testMember->date_of_birth->format('m-d') === $today->format('m-d') ? '✓ YES' : '✗ NO'));
        
        $this->newLine();
        $this->info('🧪 Now testing birthday SMS command...');
        $this->newLine();
        
        // Run the birthday SMS command in dry-run mode first
        $this->call('sms:send-birthday', ['--dry-run' => true]);
        
        $this->newLine();
        $this->info('✅ Dry run completed!');
        $this->newLine();
        
        if ($this->confirm('Do you want to send the actual SMS now?', true)) {
            $this->newLine();
            $this->info('📤 Sending actual birthday SMS...');
            $this->call('sms:send-birthday');
            $this->newLine();
            $this->info('✅ Check your phone for the birthday SMS!');
        }
        
        $this->newLine();
        $this->info('🧹 Cleanup Options:');
        if ($this->confirm('Do you want to delete the test member?', false)) {
            $testMember->forceDelete();
            $this->info('✓ Test member deleted');
        } else {
            $this->info('Test member kept for future testing');
            $this->line('Email: ' . $testMember->email);
            $this->line('To delete later: Member::where(\'email\', \'' . $testMember->email . '\')->forceDelete();');
        }
        
        return Command::SUCCESS;
    }
}
