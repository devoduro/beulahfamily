<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\BirthdayNotificationService;
use Illuminate\Support\Facades\Log;

class SendBirthdayReminders extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'birthday:send-reminders 
                            {--days=3 : Days ahead to send reminders}
                            {--dry-run : Run without actually sending reminders}';

    /**
     * The console command description.
     */
    protected $description = 'Send birthday reminders for upcoming celebrations';

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
        $days = (int) $this->option('days');
        $isDryRun = $this->option('dry-run');

        $this->info("📅 Checking for birthdays in the next {$days} days...");

        $upcomingBirthdays = $this->birthdayService->getUpcomingBirthdays($days);

        if ($upcomingBirthdays->isEmpty()) {
            $this->info('🎈 No upcoming birthdays found in the specified period.');
            return 0;
        }

        $this->info("🎂 Found {$upcomingBirthdays->count()} upcoming birthday(s)!");

        // Display upcoming birthdays
        $this->table(
            ['Name', 'Birthday', 'Days Until', 'Age', 'Chapter'],
            $upcomingBirthdays->map(function ($member) {
                $nextBirthday = $this->getNextBirthday($member->date_of_birth);
                $daysUntil = $this->getDaysUntilBirthday($member->date_of_birth);
                
                return [
                    $member->full_name,
                    $nextBirthday->format('M j, Y'),
                    $daysUntil . ' day' . ($daysUntil > 1 ? 's' : ''),
                    ($member->age + 1) . ' years',
                    $member->chapter
                ];
            })
        );

        if ($isDryRun) {
            $this->warn('🔍 DRY RUN MODE - No reminders will be sent');
            return 0;
        }

        // Send reminders (this could be extended to send to admins/pastors)
        $reminders = $this->birthdayService->scheduleBirthdayReminders();
        
        $this->info("📝 Generated {count($reminders)} reminder(s):");
        foreach ($reminders as $reminder) {
            $this->line("   • {$reminder['member']} - {$reminder['days_until']} days");
        }

        Log::info('Birthday reminders processed', [
            'upcoming_count' => $upcomingBirthdays->count(),
            'reminders_generated' => count($reminders),
            'days_ahead' => $days
        ]);

        return 0;
    }

    /**
     * Get next birthday date
     */
    protected function getNextBirthday($dateOfBirth)
    {
        $birthday = \Carbon\Carbon::parse($dateOfBirth);
        $today = \Carbon\Carbon::today();
        
        $nextBirthday = $birthday->copy()->year($today->year);
        
        if ($nextBirthday->isPast()) {
            $nextBirthday->addYear();
        }
        
        return $nextBirthday;
    }

    /**
     * Get days until next birthday
     */
    protected function getDaysUntilBirthday($dateOfBirth)
    {
        $nextBirthday = $this->getNextBirthday($dateOfBirth);
        return \Carbon\Carbon::today()->diffInDays($nextBirthday);
    }
}
