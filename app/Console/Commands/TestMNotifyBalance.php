<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\MNotifyService;

class TestMNotifyBalance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mnotify:test-balance';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test MNotify balance API connection';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing MNotify Balance API...');
        
        // Check if API key is configured
        $apiKey = config('services.mnotify.api_key');
        if (!$apiKey) {
            $this->error('MNotify API key is not configured. Please set MNOTIFY_API_KEY in your .env file.');
            return 1;
        }
        
        $this->info('API Key configured: ' . substr($apiKey, 0, 10) . '...');
        
        // Test the balance API
        $mnotifyService = new MNotifyService();
        $result = $mnotifyService->getBalance();
        
        if ($result['success']) {
            $this->info('✅ Balance API call successful!');
            $this->info('Balance: ' . $result['balance'] . ' ' . ($result['currency'] ?? 'GHS'));
            
            if (isset($result['response'])) {
                $this->info('Full Response: ' . json_encode($result['response'], JSON_PRETTY_PRINT));
            }
        } else {
            $this->error('❌ Balance API call failed!');
            $this->error('Error: ' . ($result['error'] ?? 'Unknown error'));
            
            if (isset($result['response'])) {
                $this->error('Response: ' . json_encode($result['response'], JSON_PRETTY_PRINT));
            }
        }
        
        return $result['success'] ? 0 : 1;
    }
}
