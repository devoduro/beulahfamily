<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Services\ActivityLogger;

class BirthdayController extends Controller
{
    /**
     * Display birthday celebrants list with filtering
     */
    public function index(Request $request)
    {
        $query = Member::whereNotNull('date_of_birth')
            ->where('membership_status', 'active')
            ->orderBy('date_of_birth');

        // Filter by month
        if ($request->filled('month')) {
            $query->whereMonth('date_of_birth', $request->month);
        }

        // Filter by chapter
        if ($request->filled('chapter')) {
            $query->where('chapter', $request->chapter);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by age range
        if ($request->filled('min_age')) {
            $minDate = Carbon::now()->subYears($request->min_age);
            $query->where('date_of_birth', '<=', $minDate);
        }

        if ($request->filled('max_age')) {
            $maxDate = Carbon::now()->subYears($request->max_age);
            $query->where('date_of_birth', '>=', $maxDate);
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$search}%");
            });
        }

        $members = $query->paginate(20);

        // Add age and next birthday to each member
        $members->getCollection()->transform(function ($member) {
            $member->age = Carbon::parse($member->date_of_birth)->age;
            $member->next_birthday = $this->getNextBirthday($member->date_of_birth);
            $member->days_until_birthday = $this->getDaysUntilBirthday($member->date_of_birth);
            return $member;
        });

        // Get statistics
        $stats = $this->getBirthdayStats();

        // Get upcoming birthdays (next 30 days)
        $upcomingBirthdays = $this->getUpcomingBirthdays(30);

        // Get today's birthdays
        $todayBirthdays = $this->getTodayBirthdays();

        // Filter options
        $months = [
            1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
            5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
            9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
        ];

        $chapters = ['ACCRA', 'KUMASI', 'NEW JESSY'];

        return view('birthdays.index', compact(
            'members',
            'stats',
            'upcomingBirthdays',
            'todayBirthdays',
            'months',
            'chapters',
            'request'
        ));
    }

    /**
     * Display upcoming birthdays
     */
    public function upcoming(Request $request)
    {
        $days = $request->get('days', 30);
        $birthdays = $this->getUpcomingBirthdays($days);

        return view('birthdays.upcoming', compact('birthdays', 'days'));
    }

    /**
     * Display today's birthdays
     */
    public function today()
    {
        $birthdays = $this->getTodayBirthdays();

        return view('birthdays.today', compact('birthdays'));
    }

    /**
     * Display birthday details for a specific member
     */
    /**
     * Show individual member birthday details
     */
    public function show(Member $member)
    {
        if (!$member->date_of_birth) {
            abort(404, 'Birthday information not available');
        }

        $member->age = Carbon::parse($member->date_of_birth)->age;
        $member->next_birthday = $this->getNextBirthday($member->date_of_birth);
        $member->days_until_birthday = $this->getDaysUntilBirthday($member->date_of_birth);

        return view('birthdays.show', compact('member'));
    }

    /**
     * Get upcoming birthdays within specified days
     */
    protected function getUpcomingBirthdays($days = 30)
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
            })
            ->map(function ($member) {
                $member->next_birthday = $this->getNextBirthday($member->date_of_birth);
                $member->days_until_birthday = $this->getDaysUntilBirthday($member->date_of_birth);
                return $member;
            });
    }

    /**
     * Get today's birthday celebrants
     */
    protected function getTodayBirthdays()
    {
        $today = Carbon::today();
        
        return Member::whereNotNull('date_of_birth')
            ->where('membership_status', 'active')
            ->get()
            ->filter(function ($member) use ($today) {
                $dob = Carbon::parse($member->date_of_birth);
                return $dob->month == $today->month && $dob->day == $today->day;
            })
            ->map(function ($member) {
                $member->next_birthday = $this->getNextBirthday($member->date_of_birth);
                $member->days_until_birthday = 0;
                return $member;
            });
    }

    /**
     * Get birthday statistics
     */
    protected function getBirthdayStats()
    {
        $today = Carbon::today();
        
        $allMembers = Member::whereNotNull('date_of_birth')
            ->where('membership_status', 'active')
            ->get();
        
        $todaysBirthdays = $allMembers->filter(function($member) use ($today) {
            $dob = Carbon::parse($member->date_of_birth);
            return $dob->month == $today->month && $dob->day == $today->day;
        });
        
        $thisWeekBirthdays = $allMembers->filter(function($member) use ($today) {
            $nextBirthday = $this->getNextBirthday($member->date_of_birth);
            return $nextBirthday->between($today, $today->copy()->addDays(7));
        });
        
        $thisMonthBirthdays = $allMembers->filter(function($member) use ($today) {
            $dob = Carbon::parse($member->date_of_birth);
            return $dob->month == $today->month;
        });
        
        $upcomingBirthdays = $allMembers->filter(function($member) use ($today) {
            $nextBirthday = $this->getNextBirthday($member->date_of_birth);
            return $nextBirthday->between($today, $today->copy()->addDays(30));
        });
        
        $ages = $allMembers->pluck('age')->filter();
        
        // Calculate age groups for chart
        $ageGroups = [
            '0-17' => 0,
            '18-30' => 0,
            '31-50' => 0,
            '51-70' => 0,
            '71+' => 0,
        ];
        
        foreach ($allMembers as $member) {
            $age = $member->age;
            if ($age <= 17) $ageGroups['0-17']++;
            elseif ($age <= 30) $ageGroups['18-30']++;
            elseif ($age <= 50) $ageGroups['31-50']++;
            elseif ($age <= 70) $ageGroups['51-70']++;
            else $ageGroups['71+']++;
        }
        
        return [
            'total_members' => $allMembers->count(),
            'total_birthdays' => $allMembers->count(),
            'today_birthdays' => $todaysBirthdays->count(),
            'this_week_birthdays' => $thisWeekBirthdays->count(),
            'this_month_birthdays' => $thisMonthBirthdays->count(),
            'upcoming_30_days' => $upcomingBirthdays->count(),
            'average_age' => $ages->count() > 0 ? round($ages->average(), 1) : 0,
            'youngest' => $ages->count() > 0 ? $ages->min() : 0,
            'oldest' => $ages->count() > 0 ? $ages->max() : 0,
            'age_groups' => $ageGroups,
        ];
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

public function sendWishes(Request $request, Member $member)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'send_email' => 'boolean',
            'send_sms' => 'boolean'
        ]);

        $sendEmail = $request->boolean('send_email');
        $sendSms = $request->boolean('send_sms');

        if (!$sendEmail && !$sendSms) {
            return response()->json([
                'success' => false,
                'message' => 'Please select at least one delivery method (Email or SMS).'
            ], 400);
        }

        try {
            $birthdayService = app(\App\Services\BirthdayNotificationService::class);
            
            $result = $birthdayService->sendBirthdayWishes(
                $member,
                $request->message,
                $sendEmail,
                $sendSms
            );

            $deliveryMethods = [];
            if ($result['email']) $deliveryMethods[] = 'email';
            if ($result['sms']) $deliveryMethods[] = 'SMS';

            if (empty($deliveryMethods)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send birthday wishes. ' . implode(', ', $result['errors'])
                ], 500);
            }

            $deliveryText = implode(' and ', $deliveryMethods);
            
            return response()->json([
                'success' => true,
                'message' => "🎉 Birthday wishes sent successfully to {$member->full_name} via {$deliveryText}!"
            ]);

        } catch (\Exception $e) {
            \Log::error('Birthday wishes sending failed', [
                'member_id' => $member->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending birthday wishes. Please try again.'
            ], 500);
        }
    }
}
