<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Event;
use App\Models\Member;
use App\Models\EventQrCode;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing events and members
        $events = Event::all();
        $members = Member::all();

        if ($events->isEmpty() || $members->isEmpty()) {
            $this->command->info('No events or members found. Please seed events and members first.');
            return;
        }

        // Create attendance records for the last 30 days
        $attendanceData = [];
        
        foreach ($events as $event) {
            // Generate QR code for each event
            $qrCode = EventQrCode::create([
                'event_id' => $event->id,
                'qr_code_token' => \Illuminate\Support\Str::random(32),
                'qr_code_path' => 'qr-codes/event-' . $event->id . '-' . time() . '.png',
                'expires_at' => now()->addDays(7),
                'is_active' => true,
                'scan_count' => 0,
                'scan_logs' => []
            ]);

            // Create attendance for 60-80% of members for each event
            $attendeeCount = rand(
                (int)($members->count() * 0.6), 
                (int)($members->count() * 0.8)
            );
            
            $selectedMembers = $members->random($attendeeCount);
            
            foreach ($selectedMembers as $member) {
                $checkInTime = $event->start_datetime->copy()->subMinutes(rand(5, 30));
                $checkOutTime = rand(0, 100) > 20 ? // 80% chance of checking out
                    $checkInTime->copy()->addHours(rand(1, 4))->addMinutes(rand(0, 59)) : 
                    null;

                $attendanceData[] = [
                    'event_id' => $event->id,
                    'member_id' => $member->id,
                    'checked_in_at' => $checkInTime,
                    'checked_out_at' => $checkOutTime,
                    'attendance_method' => rand(0, 100) > 30 ? 'qr_code' : 'manual', // 70% QR, 30% manual
                    'device_info' => $this->getRandomDeviceInfo(),
                    'ip_address' => $this->getRandomIpAddress(),
                    'notes' => rand(0, 100) > 80 ? 'Late arrival' : null,
                    'is_verified' => true,
                    'created_at' => $checkInTime,
                    'updated_at' => $checkOutTime ?? $checkInTime
                ];

                // Update QR code scan count
                $qrCode->increment('scan_count');
            }
        }

        // Insert attendance records one by one to handle duplicates
        foreach ($attendanceData as $data) {
            try {
                Attendance::create($data);
            } catch (\Exception $e) {
                // Skip duplicates
                continue;
            }
        }

        // Create some additional historical attendance data
        $this->createHistoricalAttendance($events, $members);

        $this->command->info('Created ' . count($attendanceData) . ' attendance records with QR codes.');
    }

    /**
     * Create historical attendance data for the past month
     */
    private function createHistoricalAttendance($events, $members)
    {
        $historicalData = [];
        
        // Create attendance for past 30 days (simulating regular services)
        for ($i = 1; $i <= 30; $i++) {
            $date = Carbon::now()->subDays($i);
            
            // Skip if it's not a Sunday (assuming Sunday services)
            if ($date->dayOfWeek !== Carbon::SUNDAY) {
                continue;
            }

            // Create a simulated Sunday service attendance
            $attendeeCount = rand(40, 70); // 40-70 attendees per service
            $selectedMembers = $members->random(min($attendeeCount, $members->count()));
            
            foreach ($selectedMembers as $member) {
                $checkInTime = $date->copy()->setTime(9, rand(0, 30)); // 9:00-9:30 AM
                $checkOutTime = $checkInTime->copy()->addHours(2)->addMinutes(rand(0, 30));

                $historicalData[] = [
                    'event_id' => $events->random()->id,
                    'member_id' => $member->id,
                    'checked_in_at' => $checkInTime,
                    'checked_out_at' => $checkOutTime,
                    'attendance_method' => rand(0, 100) > 50 ? 'qr_code' : 'manual',
                    'device_info' => $this->getRandomDeviceInfo(),
                    'ip_address' => $this->getRandomIpAddress(),
                    'notes' => null,
                    'is_verified' => true,
                    'created_at' => $checkInTime,
                    'updated_at' => $checkOutTime
                ];
            }
        }

        if (!empty($historicalData)) {
            foreach ($historicalData as $data) {
                try {
                    Attendance::create($data);
                } catch (\Exception $e) {
                    // Skip duplicates
                    continue;
                }
            }
            $this->command->info('Created ' . count($historicalData) . ' historical attendance records.');
        }
    }

    /**
     * Get random device info
     */
    private function getRandomDeviceInfo()
    {
        $devices = [
            'Mozilla/5.0 (iPhone; CPU iPhone OS 15_0 like Mac OS X) AppleWebKit/605.1.15',
            'Mozilla/5.0 (Android 11; Mobile; rv:68.0) Gecko/68.0 Firefox/88.0',
            'Mozilla/5.0 (Linux; Android 10; SM-G973F) AppleWebKit/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15',
            'Mozilla/5.0 (Linux; Android 11; Pixel 5) AppleWebKit/537.36'
        ];

        return $devices[array_rand($devices)];
    }

    /**
     * Get random IP address
     */
    private function getRandomIpAddress()
    {
        return rand(192, 192) . '.' . rand(168, 168) . '.' . rand(1, 1) . '.' . rand(2, 254);
    }
}
