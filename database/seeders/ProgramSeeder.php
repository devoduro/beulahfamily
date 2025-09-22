<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create ERGATES Conference 2025
        Program::create([
            'name' => 'ERGATES Conference 2025',
            'description' => 'Join us for the annual ERGATES Conference 2025 - a premier business networking and development event for entrepreneurs, business owners, and professionals. This conference brings together industry leaders, innovative thinkers, and aspiring entrepreneurs to share insights, build connections, and explore new opportunities.',
            'type' => 'ergates_conference',
            'start_date' => '2025-03-15',
            'end_date' => '2025-03-16',
            'start_time' => '08:00',
            'end_time' => '17:00',
            'venue' => 'Beulah Family Conference Center',
            'registration_fee' => 150.00,
            'max_participants' => 500,
            'registration_deadline' => '2025-03-01',
            'status' => 'published',
            'requirements' => [
                'Valid business registration or proof of business ownership',
                'Professional photo for conference badge',
                'Business flyer or promotional material (optional)',
            ],
            'terms_and_conditions' => 'By registering for ERGATES Conference 2025, you agree to our terms and conditions. Registration fees are non-refundable after the deadline. Participants must adhere to the conference code of conduct.',
            'contact_email' => 'ergates@beulahfamily.org',
            'contact_phone' => '+233 24 123 4567',
            'allow_file_uploads' => true,
            'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png', 'mp4', 'mp3'],
            'max_file_size' => 100, // MB
            'max_files' => 5,
            'program_category' => 'business_conference',
        ]);

        // Create Annual Retreat 2025
        Program::create([
            'name' => 'Beulah Family Annual Retreat 2025',
            'description' => 'Join us for our annual spiritual retreat - a time of renewal, fellowship, and spiritual growth. Experience powerful worship sessions, inspiring teachings, and meaningful connections with fellow believers in a serene and peaceful environment.',
            'type' => 'annual_retreat',
            'start_date' => '2025-07-10',
            'end_date' => '2025-07-13',
            'start_time' => '09:00',
            'end_time' => '18:00',
            'venue' => 'Peduase Valley Resort, Eastern Region',
            'registration_fee' => 250.00,
            'max_participants' => 200,
            'registration_deadline' => '2025-06-30',
            'status' => 'published',
            'requirements' => [
                'Valid identification document',
                'Medical clearance (if applicable)',
                'Emergency contact information',
            ],
            'terms_and_conditions' => 'By registering for the Annual Retreat 2025, you agree to our terms and conditions. Registration includes accommodation, meals, and all retreat activities. Cancellations must be made at least 14 days before the retreat date for partial refund.',
            'contact_email' => 'retreat@beulahfamily.org',
            'contact_phone' => '+233 24 987 6543',
            'allow_file_uploads' => false,
            'allowed_file_types' => [],
            'max_file_size' => 0,
            'max_files' => 0,
            'program_category' => 'spiritual_retreat',
        ]);

        // Create additional sample programs
        Program::create([
            'name' => 'Youth Leadership Summit 2025',
            'description' => 'Empowering the next generation of Christian leaders through interactive workshops, mentorship sessions, and leadership training.',
            'type' => 'workshop',
            'start_date' => Carbon::create(2025, 2, 20),
            'end_date' => Carbon::create(2025, 2, 22),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'venue' => 'Youth Center',
            'registration_fee' => 50.00,
            'max_participants' => 200,
            'registration_deadline' => Carbon::create(2025, 2, 10),
            'status' => 'published',
            'terms_and_conditions' => 'Participants must be between 16-30 years old. All sessions are mandatory for certificate award.',
            'contact_email' => 'youth@beulahfamily.org',
            'contact_phone' => '+233 24 987 6543',
            'allow_file_uploads' => false,
        ]);

        Program::create([
            'name' => 'Marriage Enrichment Retreat',
            'description' => 'A weekend retreat designed to strengthen marriages and build stronger family foundations through biblical principles and practical workshops.',
            'type' => 'retreat',
            'start_date' => Carbon::create(2025, 4, 25),
            'end_date' => Carbon::create(2025, 4, 27),
            'start_time' => '18:00',
            'end_time' => '12:00',
            'venue' => 'Mountain View Retreat Center',
            'registration_fee' => 300.00,
            'max_participants' => 100,
            'registration_deadline' => Carbon::create(2025, 4, 15),
            'status' => 'published',
            'terms_and_conditions' => 'Registration is for couples only. Accommodation and meals are included in the registration fee.',
            'contact_email' => 'marriage@beulahfamily.org',
            'contact_phone' => '+233 24 555 0123',
            'allow_file_uploads' => false,
        ]);

        Program::create([
            'name' => 'Financial Literacy Workshop',
            'description' => 'Learn biblical principles of money management, investment strategies, and building generational wealth.',
            'type' => 'seminar',
            'start_date' => Carbon::create(2025, 1, 30),
            'end_date' => Carbon::create(2025, 1, 30),
            'start_time' => '14:00',
            'end_time' => '18:00',
            'venue' => 'Conference Room A',
            'registration_fee' => 0.00, // Free
            'max_participants' => 150,
            'registration_deadline' => Carbon::create(2025, 1, 28),
            'status' => 'published',
            'contact_email' => 'finance@beulahfamily.org',
            'allow_file_uploads' => false,
        ]);

        Program::create([
            'name' => 'Annual Church Conference 2025',
            'description' => 'Our annual church conference featuring guest speakers, worship sessions, and ministry updates.',
            'type' => 'conference',
            'start_date' => Carbon::create(2025, 6, 10),
            'end_date' => Carbon::create(2025, 6, 14),
            'start_time' => '07:00',
            'end_time' => '21:00',
            'venue' => 'Main Church Auditorium',
            'registration_fee' => 75.00,
            'max_participants' => 1000,
            'registration_deadline' => Carbon::create(2025, 5, 30),
            'status' => 'draft', // Not yet published
            'contact_email' => 'conference@beulahfamily.org',
            'contact_phone' => '+233 24 111 2222',
            'allow_file_uploads' => false,
        ]);
    }
}
