<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BirthdayNotificationService;
use Illuminate\Support\Facades\Log;

class SendBirthdayNotifications extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'birthday:send-notifications 
                            {--dry-run : Run without actually sending notifications}
                            {--email : Send email notifications}
                            {--sms : Send SMS notifications}';

    /**
     * The console command description.
     */
    protected $description = 'Send birthday notifications to today\'s celebrants';

    protected $birthdayService;

    /**
     * Create a new command instance.
     */
    public function __construct(BirthdayNotificationService $birthdayService)
    {
        parent::__construct();
        $this->birthdayService = $birthdayService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🎂 Starting Birthday Notification Service...');
        
        $isDryRun = $this->option('dry-run');
        $sendEmail = $this->option('email') ?? true;
        $sendSms = $this->option('sms') ?? false;

        // Get today's birthday celebrants
        $todaysBirthdays = $this->birthdayService->getTodaysBirthdays();

        if ($todaysBirthdays->isEmpty()) {
            $this->info('🎈 No birthdays today! Everyone can relax.');
            return 0;
        }

        $this->info("�� Found {$todaysBirthdays->count()} birthday celebrant(s) today!");

        // Display celebrants
        $this->table(
            ['Name', 'Email', 'Phone', 'Chapter', 'Age'],
            $todaysBirthdays->map(function ($member) {
                return [
                    $member->full_name,
                    $member->email ?? 'N/A',
                    $member->phone ?? 'N/A',
                    $member->chapter,
                    $member->age . ' years'
                ];
            })
        );

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No notifications will be sent');
            return 0;
        }

        // Confirm before sending
        if (!$this->confirm('Send birthday notifications to these members?')) {
            $this->info('Operation cancelled.');
            return 0;
        }

        $successCount = 0;
        $errorCount = 0;

        $progressBar = $this->output->createProgressBar($todaysBirthdays->count());
        $progressBar->start();

        foreach ($todaysBirthdays as $member) {
            try {
                $message = $this->getPersonalizedMessage($member);
                
                $result = $this->birthdayService->sendBirthdayWishes(
                    $member, 
                    $message, 
                    $sendEmail, 
                    $sendSms
                );

                if ($result['email'] || $result['sms']) {
                    $successCount++;
                    $this->newLine();
                    $this->info("✅ Sent birthday wishes to {$member->full_name}");
                } else {
                    $errorCount++;
                    $this->newLine();
                    $this->error("❌ Failed to send wishes to {$member->full_name}");
                    if (!empty($result['errors'])) {
                        foreach ($result['errors'] as $error) {
                            $this->error("   - {$error}");
                        }
                    }
                }

            } catch (\Exception $e) {
                $errorCount++;
                $this->newLine();
                $this->error("❌ Error sending wishes to {$member->full_name}: {$e->getMessage()}");
                
                Log::error('Birthday notification command error', [
                    'member_id' => $member->id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
            }

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->newLine(2);

        // Summary
        $this->info("🎊 Birthday Notification Summary:");
        $this->info("   ✅ Successful: {$successCount}");
        if ($errorCount > 0) {
            $this->error("   ❌ Failed: {$errorCount}");
        }
        $this->info("   📧 Email enabled: " . ($sendEmail ? 'Yes' : 'No'));
        $this->info("   📱 SMS enabled: " . ($sendSms ? 'Yes' : 'No'));

        Log::info('Birthday notifications completed', [
            'total_celebrants' => $todaysBirthdays->count(),
            'successful' => $successCount,
            'failed' => $errorCount,
            'email_enabled' => $sendEmail,
            'sms_enabled' => $sendSms
        ]);

        return 0;
    }

    /**
     * Get personalized birthday message
     */
    protected function getPersonalizedMessage($member)
    {
        $age = $member->age;
        $firstName = $member->first_name;

        return "🎉 Happy {$age}th Birthday, {$firstName}! 🎂

On this wonderful day, we celebrate you and all the joy you bring to our Beulah Family church community. 

May God bless you with:
✨ Another year of health and happiness
🙏 Spiritual growth and deeper faith  
💝 Love from family and friends
🌟 Success in all your endeavors

Thank you for being such a special part of our church family. We're blessed to have you with us!

May this new year of your life be filled with God's abundant blessings, laughter, and beautiful memories.

Have a fantastic birthday celebration! 🎈

With love and prayers,
Your Church Family at Beulah Family

\"The Lord bless you and keep you; the Lord make his face shine on you and be gracious to you.\" - Numbers 6:24-25";
    }
}
