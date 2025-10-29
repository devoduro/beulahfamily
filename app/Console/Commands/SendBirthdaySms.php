<?php

namespace App\Console\Commands;

use App\Models\Member;
use App\Models\SmsTemplate;
use App\Models\SmsCredit;
use App\Services\MNotifyService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class SendBirthdaySms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:send-birthday {--dry-run : Run without actually sending SMS}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send birthday SMS messages to members celebrating their birthday today';

    protected $mnotifyService;

    public function __construct(MNotifyService $mnotifyService)
    {
        parent::__construct();
        $this->mnotifyService = $mnotifyService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $today = Carbon::today();
        
        $this->info("Starting birthday SMS sender for {$today->format('Y-m-d')}");
        
        if ($isDryRun) {
            $this->warn("DRY RUN MODE - No SMS will be sent");
        }

        try {
            // Get members with birthdays today
            $birthdayMembers = Member::whereRaw('DATE_FORMAT(date_of_birth, "%m-%d") = ?', [
                $today->format('m-d')
            ])
            ->where('is_active', true)
            ->whereNotNull('phone')
            ->get();

            if ($birthdayMembers->isEmpty()) {
                $this->info("No members with birthdays today.");
                return Command::SUCCESS;
            }

            $this->info("Found {$birthdayMembers->count()} members with birthdays today:");
            
            foreach ($birthdayMembers as $member) {
                $this->line("- {$member->first_name} {$member->last_name} ({$member->phone})");
            }

            // Get birthday SMS template
            $template = SmsTemplate::where('category', 'birthday')
                ->where('is_active', true)
                ->first();

            if (!$template) {
                // Create default birthday template if none exists
                $template = $this->createDefaultBirthdayTemplate();
                $this->warn("No birthday template found. Created default template.");
            }

            $this->info("Using template: {$template->name}");

            // Check system SMS credits (use admin user ID 1 or first admin)
            $adminUser = \App\Models\User::where('role', 'admin')->first();
            if (!$adminUser) {
                $this->error("No admin user found for system SMS credits");
                return Command::FAILURE;
            }

            $systemCredit = SmsCredit::getOrCreateForUser($adminUser->id);
            $requiredCredits = $birthdayMembers->count();

            if ($systemCredit->credits < $requiredCredits) {
                $this->error("Insufficient SMS credits. Required: {$requiredCredits}, Available: {$systemCredit->credits}");
                return Command::FAILURE;
            }

            $this->info("SMS Credits available: {$systemCredit->credits}");

            $successCount = 0;
            $failureCount = 0;

            // Send birthday messages
            foreach ($birthdayMembers as $member) {
                try {
                    $age = $member->date_of_birth ? $today->diffInYears($member->date_of_birth) : null;
                    
                    // Prepare template variables
                    $variables = [
                        'first_name' => $member->first_name,
                        'last_name' => $member->last_name,
                        'full_name' => $member->first_name . ' ' . $member->last_name,
                        'age' => $age,
                        'church_name' => 'Beulah Family'
                    ];

                    $message = $template->renderMessage($variables);
                    $phone = $this->mnotifyService->formatPhoneNumber($member->phone);

                    $this->line("Sending to {$member->first_name} {$member->last_name} ({$phone}):");
                    $this->line("Message: {$message}");

                    if (!$isDryRun) {
                        // Send SMS
                        $result = $this->mnotifyService->sendSms($phone, $message);

                        if ($result['success']) {
                            // Deduct credit
                            $systemCredit->deductCredits(1, "Birthday SMS sent to {$member->first_name} {$member->last_name}");
                            
                            $this->info("✓ SMS sent successfully");
                            $successCount++;

                            // Log the birthday SMS
                            Log::info("Birthday SMS sent", [
                                'member_id' => $member->id,
                                'member_name' => $member->first_name . ' ' . $member->last_name,
                                'phone' => $phone,
                                'message' => $message,
                                'result' => $result
                            ]);

                        } else {
                            $this->error("✗ Failed to send SMS: " . ($result['message'] ?? 'Unknown error'));
                            $failureCount++;

                            Log::error("Birthday SMS failed", [
                                'member_id' => $member->id,
                                'member_name' => $member->first_name . ' ' . $member->last_name,
                                'phone' => $phone,
                                'error' => $result['message'] ?? 'Unknown error'
                            ]);
                        }
                    } else {
                        $this->info("✓ Would send SMS (dry run)");
                        $successCount++;
                    }

                    // Small delay between messages
                    if (!$isDryRun) {
                        sleep(1);
                    }

                } catch (\Exception $e) {
                    $this->error("✗ Error processing {$member->first_name} {$member->last_name}: " . $e->getMessage());
                    $failureCount++;
                    
                    Log::error("Birthday SMS processing error", [
                        'member_id' => $member->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            // Summary
            $this->info("\n--- Birthday SMS Summary ---");
            $this->info("Total members: {$birthdayMembers->count()}");
            $this->info("Successful: {$successCount}");
            $this->info("Failed: {$failureCount}");
            
            if (!$isDryRun) {
                $remainingCredits = SmsCredit::getOrCreateForUser($adminUser->id)->credits;
                $this->info("Remaining SMS credits: {$remainingCredits}");
            }

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Birthday SMS command failed: " . $e->getMessage());
            Log::error("Birthday SMS command error", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return Command::FAILURE;
        }
    }

    /**
     * Create default birthday template.
     */
    private function createDefaultBirthdayTemplate()
    {
        return SmsTemplate::create([
            'name' => 'Default Birthday Wishes',
            'description' => 'Default birthday message template',
            'message' => 'Happy Birthday {{first_name}}! 🎉 May God bless you with joy, peace, and prosperity in your new year. We celebrate you today! - {{church_name}}',
            'category' => 'birthday',
            'variables' => ['first_name', 'last_name', 'full_name', 'age', 'church_name'],
            'created_by' => 1,
            'is_active' => true
        ]);
    }
}
