<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Family;
use App\Models\Member;
use App\Models\Ministry;
use App\Models\Event;
use App\Models\Donation;
use App\Models\Announcement;
use Carbon\Carbon;

class ChurchManagementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample families
        $families = [
            [
                'family_name' => 'Johnson Family',
                'address' => '123 Church Street',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'postal_code' => '100001',
                'country' => 'Nigeria',
                'home_phone' => '+234-801-234-5678',
                'email' => 'johnson.family@email.com'
            ],
            [
                'family_name' => 'Adebayo Family',
                'address' => '456 Faith Avenue',
                'city' => 'Abuja',
                'state' => 'FCT',
                'postal_code' => '900001',
                'country' => 'Nigeria',
                'home_phone' => '+234-802-345-6789',
                'email' => 'adebayo.family@email.com'
            ],
            [
                'family_name' => 'Okafor Family',
                'address' => '789 Grace Road',
                'city' => 'Port Harcourt',
                'state' => 'Rivers',
                'postal_code' => '500001',
                'country' => 'Nigeria',
                'home_phone' => '+234-803-456-7890',
                'email' => 'okafor.family@email.com'
            ]
        ];

        foreach ($families as $familyData) {
            Family::create($familyData);
        }

        // Create sample members
        $members = [
            [
                'first_name' => 'John',
                'last_name' => 'Johnson',
                'title' => 'Pastor',
                'email' => 'pastor.john@church.com',
                'phone' => '+234-801-111-1111',
                'date_of_birth' => '1975-05-15',
                'gender' => 'male',
                'marital_status' => 'married',
                'address' => '123 Church Street',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'occupation' => 'Pastor',
                'membership_date' => '2010-01-01',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'family_id' => 1,
                'relationship_to_head' => 'head',
                'is_baptized' => true,
                'baptism_date' => '1995-06-01',
                'is_confirmed' => true,
                'confirmation_date' => '1995-06-01'
            ],
            [
                'first_name' => 'Mary',
                'last_name' => 'Johnson',
                'title' => 'Mrs',
                'email' => 'mary.johnson@email.com',
                'phone' => '+234-801-111-1112',
                'date_of_birth' => '1978-08-22',
                'gender' => 'female',
                'marital_status' => 'married',
                'address' => '123 Church Street',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'occupation' => 'Teacher',
                'membership_date' => '2010-01-01',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'family_id' => 1,
                'relationship_to_head' => 'spouse',
                'is_baptized' => true,
                'baptism_date' => '1996-07-01'
            ],
            [
                'first_name' => 'David',
                'last_name' => 'Johnson',
                'email' => 'david.johnson@email.com',
                'phone' => '+234-801-111-1113',
                'date_of_birth' => '2005-03-10',
                'gender' => 'male',
                'marital_status' => 'single',
                'address' => '123 Church Street',
                'city' => 'Lagos',
                'state' => 'Lagos',
                'occupation' => 'Student',
                'membership_date' => '2015-01-01',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'family_id' => 1,
                'relationship_to_head' => 'child',
                'is_baptized' => true,
                'baptism_date' => '2018-04-01'
            ],
            [
                'first_name' => 'Adebayo',
                'last_name' => 'Adebayo',
                'title' => 'Elder',
                'email' => 'elder.adebayo@church.com',
                'phone' => '+234-802-222-2222',
                'date_of_birth' => '1965-12-05',
                'gender' => 'male',
                'marital_status' => 'married',
                'address' => '456 Faith Avenue',
                'city' => 'Abuja',
                'state' => 'FCT',
                'occupation' => 'Engineer',
                'membership_date' => '2008-03-15',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'family_id' => 2,
                'relationship_to_head' => 'head',
                'is_baptized' => true,
                'baptism_date' => '1985-08-15',
                'is_confirmed' => true,
                'confirmation_date' => '1985-08-15'
            ],
            [
                'first_name' => 'Grace',
                'last_name' => 'Okafor',
                'title' => 'Sister',
                'email' => 'grace.okafor@email.com',
                'phone' => '+234-803-333-3333',
                'date_of_birth' => '1990-09-18',
                'gender' => 'female',
                'marital_status' => 'single',
                'address' => '789 Grace Road',
                'city' => 'Port Harcourt',
                'state' => 'Rivers',
                'occupation' => 'Nurse',
                'membership_date' => '2018-06-01',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'family_id' => 3,
                'relationship_to_head' => 'head',
                'is_baptized' => true,
                'baptism_date' => '2019-01-01'
            ]
        ];

        foreach ($members as $memberData) {
            Member::create($memberData);
        }

        // Create sample ministries
        $ministries = [
            [
                'name' => 'Worship Team',
                'description' => 'Leading worship and praise during services',
                'leader_id' => 2,
                'meeting_day' => 'Wednesday',
                'meeting_time' => '19:00:00',
                'meeting_location' => 'Church Sanctuary',
                'ministry_type' => 'worship',
                'target_age_min' => 16,
                'target_age_max' => 65,
                'target_gender' => 'all',
                'budget' => 50000.00
            ],
            [
                'name' => 'Youth Ministry',
                'description' => 'Ministry focused on young people aged 13-25',
                'leader_id' => 3,
                'meeting_day' => 'Saturday',
                'meeting_time' => '16:00:00',
                'meeting_location' => 'Youth Hall',
                'ministry_type' => 'youth',
                'target_age_min' => 13,
                'target_age_max' => 25,
                'target_gender' => 'all',
                'budget' => 75000.00
            ],
            [
                'name' => 'Children Ministry',
                'description' => 'Sunday school and activities for children',
                'leader_id' => 2,
                'meeting_day' => 'Sunday',
                'meeting_time' => '09:00:00',
                'meeting_location' => 'Children Hall',
                'ministry_type' => 'children',
                'target_age_min' => 3,
                'target_age_max' => 12,
                'target_gender' => 'all',
                'budget' => 30000.00
            ],
            [
                'name' => 'Outreach Ministry',
                'description' => 'Community outreach and evangelism',
                'leader_id' => 4,
                'meeting_day' => 'Saturday',
                'meeting_time' => '08:00:00',
                'meeting_location' => 'Church Office',
                'ministry_type' => 'outreach',
                'target_age_min' => 18,
                'target_age_max' => null,
                'target_gender' => 'all',
                'budget' => 100000.00
            ]
        ];

        foreach ($ministries as $ministryData) {
            Ministry::create($ministryData);
        }

        // Assign members to ministries
        $memberMinistries = [
            ['member_id' => 1, 'ministry_id' => 1, 'role' => 'leader'],
            ['member_id' => 2, 'ministry_id' => 1, 'role' => 'member'],
            ['member_id' => 2, 'ministry_id' => 3, 'role' => 'leader'],
            ['member_id' => 3, 'ministry_id' => 2, 'role' => 'leader'],
            ['member_id' => 4, 'ministry_id' => 4, 'role' => 'leader'],
            ['member_id' => 5, 'ministry_id' => 1, 'role' => 'member'],
            ['member_id' => 5, 'ministry_id' => 4, 'role' => 'member'],
        ];

        foreach ($memberMinistries as $assignment) {
            \DB::table('member_ministry')->insert([
                'member_id' => $assignment['member_id'],
                'ministry_id' => $assignment['ministry_id'],
                'role' => $assignment['role'],
                'joined_date' => now()->subMonths(rand(1, 12)),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        // Create sample events
        $events = [
            [
                'title' => 'Sunday Morning Service',
                'description' => 'Weekly Sunday morning worship service',
                'event_type' => 'service',
                'start_datetime' => now()->next('Sunday')->setTime(10, 0),
                'end_datetime' => now()->next('Sunday')->setTime(12, 0),
                'location' => 'Main Sanctuary',
                'ministry_id' => 1,
                'organizer_id' => 1,
                'status' => 'published',
                'is_recurring' => true,
                'recurrence_type' => 'weekly',
                'recurrence_interval' => 1
            ],
            [
                'title' => 'Youth Conference 2025',
                'description' => 'Annual youth conference with inspiring speakers and activities',
                'event_type' => 'conference',
                'start_datetime' => now()->addDays(30)->setTime(9, 0),
                'end_datetime' => now()->addDays(32)->setTime(18, 0),
                'location' => 'Conference Center',
                'ministry_id' => 2,
                'organizer_id' => 3,
                'max_attendees' => 200,
                'registration_fee' => 5000.00,
                'requires_registration' => true,
                'registration_deadline' => now()->addDays(20),
                'status' => 'published'
            ],
            [
                'title' => 'Community Outreach',
                'description' => 'Feeding program for the less privileged',
                'event_type' => 'outreach',
                'start_datetime' => now()->addDays(15)->setTime(8, 0),
                'end_datetime' => now()->addDays(15)->setTime(14, 0),
                'location' => 'Community Center',
                'ministry_id' => 4,
                'organizer_id' => 4,
                'status' => 'published'
            ]
        ];

        foreach ($events as $eventData) {
            Event::create($eventData);
        }

        // Create sample donations
        $donations = [
            [
                'member_id' => 1,
                'amount' => 50000.00,
                'donation_type' => 'tithe',
                'payment_method' => 'bank_transfer',
                'donation_date' => now()->subDays(7),
                'received_by' => 1,
                'status' => 'confirmed'
            ],
            [
                'member_id' => 2,
                'amount' => 25000.00,
                'donation_type' => 'offering',
                'payment_method' => 'cash',
                'donation_date' => now()->subDays(7),
                'received_by' => 1,
                'status' => 'confirmed'
            ],
            [
                'member_id' => 4,
                'amount' => 100000.00,
                'donation_type' => 'building_fund',
                'purpose' => 'New sanctuary construction',
                'payment_method' => 'check',
                'reference_number' => 'CHK001234',
                'donation_date' => now()->subDays(14),
                'received_by' => 1,
                'status' => 'confirmed'
            ],
            [
                'member_id' => 5,
                'amount' => 15000.00,
                'donation_type' => 'missions',
                'purpose' => 'Missionary support',
                'payment_method' => 'mobile_money',
                'donation_date' => now()->subDays(3),
                'received_by' => 1,
                'status' => 'confirmed'
            ]
        ];

        foreach ($donations as $donationData) {
            Donation::create($donationData);
        }

        // Create sample announcements
        $announcements = [
            [
                'title' => 'Welcome to Our Church Management System',
                'content' => 'We are excited to announce the launch of our new church management system. This platform will help us better serve our congregation and manage church activities more efficiently.',
                'type' => 'general',
                'priority' => 'high',
                'created_by' => 1,
                'publish_date' => now(),
                'display_on_website' => true,
                'status' => 'published'
            ],
            [
                'title' => 'Youth Conference Registration Open',
                'content' => 'Registration is now open for the Youth Conference 2025. Early bird registration ends in two weeks. Register now to secure your spot!',
                'type' => 'event',
                'priority' => 'medium',
                'created_by' => 1,
                'publish_date' => now(),
                'expire_date' => now()->addDays(20),
                'display_on_website' => true,
                'status' => 'published'
            ],
            [
                'title' => 'Prayer Request: Community Outreach',
                'content' => 'Please join us in prayer for our upcoming community outreach program. Pray for good weather and open hearts in the community.',
                'type' => 'prayer_request',
                'priority' => 'medium',
                'created_by' => 1,
                'publish_date' => now(),
                'display_on_website' => true,
                'status' => 'published'
            ]
        ];

        foreach ($announcements as $announcementData) {
            Announcement::create($announcementData);
        }

        $this->command->info('Church management sample data seeded successfully!');
    }
}
