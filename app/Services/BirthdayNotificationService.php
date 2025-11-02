<?php

namespace App\Services;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Services\ActivityLogger;

class BirthdayNotificationService
{
    protected $activityLogger;

    public function __construct(ActivityLogger $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Send birthday wishes to a member
     */
    public function sendBirthdayWishes(Member $member, $message, $sendEmail = true, $sendSms = false)
    {
        $results = [
            'email' => false,
            'sms' => false,
            'errors' => []
        ];

        // Send Email
        if ($sendEmail && $member->email) {
            try {
                $results['email'] = $this->sendBirthdayEmail($member, $message);
            } catch (\Exception $e) {
                $results['errors'][] = 'Email failed: ' . $e->getMessage();
                Log::error('Birthday email failed', [
                    'member_id' => $member->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Send SMS
        if ($sendSms && $member->phone) {
            try {
                $results['sms'] = $this->sendBirthdaySms($member, $message);
            } catch (\Exception $e) {
                $results['errors'][] = 'SMS failed: ' . $e->getMessage();
                Log::error('Birthday SMS failed', [
                    'member_id' => $member->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Log the activity
        $this->activityLogger->log('birthday_wishes_sent', 
            "Birthday wishes sent to {$member->full_name}", 
            'Member', 
            $member->id,
            [
                'email_sent' => $results['email'],
                'sms_sent' => $results['sms'],
                'message_preview' => substr($message, 0, 100)
            ]
        );

        return $results;
    }

    /**
     * Send birthday email
     */
    protected function sendBirthdayEmail(Member $member, $message)
    {
        $subject = "🎉 Happy Birthday, {$member->first_name}!";
        
        // Create a simple email view
        $emailContent = view('emails.birthday-wishes', [
            'member' => $member,
            'message' => $message,
            'subject' => $subject
        ])->render();

        // Send email using Laravel's Mail facade
        Mail::send([], [], function ($mail) use ($member, $subject, $emailContent) {
            $mail->to($member->email, $member->full_name)
                 ->subject($subject)
                 ->html($emailContent);
        });

        Log::info('Birthday email sent successfully', [
            'member_id' => $member->id,
            'email' => $member->email
        ]);

        return true;
    }

    /**
     * Send birthday SMS
     */
    protected function sendBirthdaySms(Member $member, $message)
    {
        // Truncate message for SMS (160 character limit)
        $smsMessage = substr($message, 0, 160);
        
        // Use existing SMS service if available
        if (class_exists('\App\Services\SmsService')) {
            $smsService = app(\App\Services\SmsService::class);
            return $smsService->sendSms($member->phone, $smsMessage);
        }

        // Fallback: Log as if sent (for development)
        Log::info('Birthday SMS would be sent', [
            'member_id' => $member->id,
            'phone' => $member->phone,
            'message' => $smsMessage
        ]);

        return true;
    }

    /**
     * Get today's birthday celebrants
     */
    public function getTodaysBirthdays()
    {
        return Member::whereNotNull('date_of_birth')
            ->where('membership_status', 'active')
            ->whereRaw('DAY(date_of_birth) = ?', [Carbon::today()->day])
            ->whereRaw('MONTH(date_of_birth) = ?', [Carbon::today()->month])
            ->get();
    }

    /**
     * Get upcoming birthdays within specified days
     */
    public function getUpcomingBirthdays($days = 7)
    {
        $today = Carbon::today();
        $endDate = Carbon::today()->addDays($days);

        return Member::whereNotNull('date_of_birth')
            ->where('membership_status', 'active')
            ->get()
            ->filter(function ($member) use ($today, $endDate) {
                $nextBirthday = $this->getNextBirthday($member->date_of_birth);
                return $nextBirthday->between($today, $endDate);
            })
            ->sortBy(function ($member) {
                return $this->getDaysUntilBirthday($member->date_of_birth);
            });
    }

    /**
     * Send automatic birthday notifications
     */
    public function sendAutomaticBirthdayNotifications()
    {
        $todaysBirthdays = $this->getTodaysBirthdays();
        $results = [];

        foreach ($todaysBirthdays as $member) {
            $message = $this->getDefaultBirthdayMessage($member);
            
            $result = $this->sendBirthdayWishes($member, $message, true, false);
            $results[] = [
                'member' => $member->full_name,
                'result' => $result
            ];
        }

        return $results;
    }

    /**
     * Get default birthday message
     */
    protected function getDefaultBirthdayMessage(Member $member)
    {
        return "🎉 Happy Birthday, {$member->first_name}! 🎂

On this special day, we celebrate you and all the joy you bring to our church family. May God bless you with another year of health, happiness, and spiritual growth.

May your birthday be filled with love, laughter, and wonderful memories. We're grateful to have you as part of our Beulah Family community.

Wishing you all the best on your special day!

With love and prayers,
Your Church Family at Beulah Family";
    }

    /**
     * Get next birthday date
     */
    protected function getNextBirthday($dateOfBirth)
    {
        $birthday = Carbon::parse($dateOfBirth);
        $today = Carbon::today();
        
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
        return Carbon::today()->diffInDays($nextBirthday);
    }

    /**
     * Schedule birthday reminders
     */
    public function scheduleBirthdayReminders()
    {
        $upcomingBirthdays = $this->getUpcomingBirthdays(3); // 3 days ahead
        $results = [];

        foreach ($upcomingBirthdays as $member) {
            $daysUntil = $this->getDaysUntilBirthday($member->date_of_birth);
            
            if ($daysUntil == 1) { // Tomorrow
                $message = "🎂 Reminder: {$member->full_name}'s birthday is tomorrow! Don't forget to wish them well.";
            } elseif ($daysUntil == 3) { // 3 days ahead
                $message = "📅 Upcoming Birthday: {$member->full_name}'s birthday is in 3 days ({$this->getNextBirthday($member->date_of_birth)->format('M j')}).";
            } else {
                continue;
            }

            // Log reminder (could be extended to send to admins)
            Log::info('Birthday reminder', [
                'member_id' => $member->id,
                'member_name' => $member->full_name,
                'days_until' => $daysUntil,
                'birthday_date' => $this->getNextBirthday($member->date_of_birth)->format('Y-m-d')
            ]);

            $results[] = [
                'member' => $member->full_name,
                'days_until' => $daysUntil,
                'message' => $message
            ];
        }

        return $results;
    }
}
