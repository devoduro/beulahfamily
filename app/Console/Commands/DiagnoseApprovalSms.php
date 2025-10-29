<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Member;
use App\Services\MNotifyService;

class DiagnoseApprovalSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:diagnose-approval {member_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnose why approval SMS is not being sent for a member';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $memberId = $this->argument('member_id');
        
        $this->info('🔍 Diagnosing Approval SMS Issue');
        $this->newLine();
        
        // Find member
        $member = Member::find($memberId);
        
        if (!$member) {
            $this->error("❌ Member with ID {$memberId} not found!");
            return Command::FAILURE;
        }
        
        $this->info("📋 Member Details:");
        $this->line("ID: {$member->id}");
        $this->line("Name: {$member->first_name} {$member->last_name}");
        $this->line("Email: {$member->email}");
        $this->line("Phone: " . ($member->phone ?: '❌ NOT SET'));
        $this->line("Approval Status: {$member->approval_status}");
        $this->line("Is Active: " . ($member->is_active ? '✓ Yes' : '✗ No'));
        $this->line("Receive SMS: " . ($member->receive_sms ? '✓ Yes' : '✗ No'));
        $this->newLine();
        
        // Check conditions
        $this->info("🔍 Checking SMS Conditions:");
        $this->newLine();
        
        $checks = [];
        
        // Check 1: Phone number
        if ($member->phone) {
            $this->info("✓ Phone number exists: {$member->phone}");
            $checks['phone'] = true;
        } else {
            $this->error("✗ Phone number is missing!");
            $this->warn("  → Member must have a phone number to receive SMS");
            $checks['phone'] = false;
        }
        
        // Check 2: receive_sms flag
        if ($member->receive_sms) {
            $this->info("✓ Member opted in to receive SMS");
            $checks['receive_sms'] = true;
        } else {
            $this->error("✗ Member has receive_sms disabled!");
            $this->warn("  → Update member: receive_sms = true");
            $checks['receive_sms'] = false;
        }
        
        // Check 3: MNotify configuration
        $apiKey = config('services.mnotify.api_key');
        $senderId = config('services.mnotify.sender_id');
        
        if ($apiKey) {
            $this->info("✓ MNotify API Key configured");
            $checks['api_key'] = true;
        } else {
            $this->error("✗ MNotify API Key not configured!");
            $this->warn("  → Set MNOTIFY_API_KEY in .env file");
            $checks['api_key'] = false;
        }
        
        if ($senderId) {
            $this->info("✓ MNotify Sender ID configured: {$senderId}");
            $checks['sender_id'] = true;
        } else {
            $this->error("✗ MNotify Sender ID not configured!");
            $this->warn("  → Set MNOTIFY_SENDER_ID in .env file");
            $checks['sender_id'] = false;
        }
        
        $this->newLine();
        
        // Check recent logs
        $this->info("📜 Checking Recent Logs:");
        $logFile = storage_path('logs/laravel.log');
        
        if (file_exists($logFile)) {
            $logs = file_get_contents($logFile);
            $memberLogs = [];
            
            // Search for logs related to this member
            $lines = explode("\n", $logs);
            foreach ($lines as $line) {
                if (strpos($line, "member_id\":{$member->id}") !== false || 
                    strpos($line, "member_id\":\"{$member->id}\"") !== false) {
                    $memberLogs[] = $line;
                }
            }
            
            if (!empty($memberLogs)) {
                $this->info("Found " . count($memberLogs) . " log entries for this member:");
                $this->newLine();
                foreach (array_slice($memberLogs, -5) as $log) {
                    $this->line($log);
                }
            } else {
                $this->warn("No log entries found for member ID {$member->id}");
            }
        }
        
        $this->newLine();
        
        // Summary
        $allPassed = !in_array(false, $checks, true);
        
        if ($allPassed) {
            $this->info("✅ All checks passed! SMS should be sent on approval.");
            $this->newLine();
            
            if ($this->confirm('Would you like to test sending approval SMS now?', true)) {
                $this->testApprovalSms($member);
            }
        } else {
            $this->error("❌ Some checks failed. Fix the issues above.");
            $this->newLine();
            $this->info("🔧 Quick Fixes:");
            
            if (!$checks['phone']) {
                $this->line("• Add phone number:");
                $this->line("  php artisan tinker");
                $this->line("  >>> \$m = App\\Models\\Member::find({$member->id});");
                $this->line("  >>> \$m->phone = '0241234567';");
                $this->line("  >>> \$m->save();");
                $this->newLine();
            }
            
            if (!$checks['receive_sms']) {
                $this->line("• Enable SMS:");
                $this->line("  php artisan tinker");
                $this->line("  >>> \$m = App\\Models\\Member::find({$member->id});");
                $this->line("  >>> \$m->receive_sms = true;");
                $this->line("  >>> \$m->save();");
                $this->newLine();
            }
            
            if (!$checks['api_key'] || !$checks['sender_id']) {
                $this->line("• Configure MNotify in .env:");
                $this->line("  MNOTIFY_API_KEY=your_api_key");
                $this->line("  MNOTIFY_SENDER_ID=your_sender_id");
                $this->line("  php artisan config:clear");
            }
        }
        
        return Command::SUCCESS;
    }
    
    private function testApprovalSms($member)
    {
        $this->newLine();
        $this->info("📤 Testing Approval SMS...");
        
        try {
            $smsService = new MNotifyService();
            $newPassword = strtolower($member->first_name) . substr($member->phone, -4);
            
            $smsMessage = "Congratulations {$member->first_name}! Your Beulah Family membership has been APPROVED. Login at: {$member->email}, Password: {$newPassword}. Please change your password after first login. Welcome to the family!";
            
            $this->line("Message: {$smsMessage}");
            $this->line("Length: " . strlen($smsMessage) . " characters");
            $this->newLine();
            
            $result = $smsService->sendSMS($member->phone, $smsMessage);
            
            if ($result['success']) {
                $this->info("✅ SMS sent successfully!");
                $this->line("Message ID: " . ($result['message_id'] ?? 'N/A'));
                $this->line("Campaign ID: " . ($result['campaign_id'] ?? 'N/A'));
            } else {
                $this->error("❌ SMS failed!");
                $this->error("Error: " . ($result['error'] ?? 'Unknown error'));
                if (isset($result['response'])) {
                    $this->line("Response: " . json_encode($result['response'], JSON_PRETTY_PRINT));
                }
            }
        } catch (\Exception $e) {
            $this->error("❌ Exception: " . $e->getMessage());
        }
    }
}
