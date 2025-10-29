<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MNotifyService;

class TestRegistrationSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:test-registration {phone} {--name=John}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SMS notification for member registration';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $phone = $this->argument('phone');
        $name = $this->option('name');
        
        $this->info('Testing Registration SMS...');
        $this->newLine();
        
        // Display configuration
        $this->info('📱 SMS Configuration:');
        $this->line('API Key: ' . (config('services.mnotify.api_key') ? '✓ Configured' : '✗ Missing'));
        $this->line('Sender ID: ' . (config('services.mnotify.sender_id') ?: '✗ Missing'));
        $this->line('Phone: ' . $phone);
        $this->newLine();
        
        if (!config('services.mnotify.api_key')) {
            $this->error('❌ MNotify API Key is not configured!');
            $this->info('Please set MNOTIFY_API_KEY in your .env file');
            return 1;
        }
        
        if (!config('services.mnotify.sender_id')) {
            $this->error('❌ MNotify Sender ID is not configured!');
            $this->info('Please set MNOTIFY_SENDER_ID in your .env file');
            return 1;
        }
        
        try {
            $smsService = new MNotifyService();
            
            // Simulate registration SMS
            $email = 'test@example.com';
            $password = strtolower($name) . '1234';
            
            $message = "Welcome to Beulah Family, {$name}! Your registration is pending approval. You'll receive another SMS once approved. Login: {$email}, Password: {$password}";
            
            $this->info('📤 Sending SMS...');
            $this->line('Message: ' . $message);
            $this->line('Length: ' . strlen($message) . ' characters');
            $this->newLine();
            
            $result = $smsService->sendSMS($phone, $message);
            
            if ($result['success']) {
                $this->info('✅ SMS sent successfully!');
                $this->newLine();
                $this->info('Response Details:');
                $this->line('Message ID: ' . ($result['message_id'] ?? 'N/A'));
                $this->line('Cost: ₵' . number_format($result['cost'] ?? 0, 2));
                $this->newLine();
                $this->info('🎉 Registration SMS test completed successfully!');
                return 0;
            } else {
                $this->error('❌ SMS sending failed!');
                $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
                $this->newLine();
                
                if (isset($result['response'])) {
                    $this->info('API Response:');
                    $this->line(json_encode($result['response'], JSON_PRETTY_PRINT));
                }
                
                return 1;
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Exception occurred!');
            $this->error('Message: ' . $e->getMessage());
            $this->newLine();
            $this->line('Stack trace:');
            $this->line($e->getTraceAsString());
            return 1;
        }
    }
}
