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
            'description' => 'Join us for the annual ERGATES Conference 2025, a premier business and entrepreneurship conference designed to empower Christian entrepreneurs and business leaders. This conference will feature keynote speakers, workshops, networking opportunities, and business exhibitions.',
            'type' => 'conference',
            'start_date' => Carbon::create(2025, 3, 15), // March 15, 2025
            'end_date' => Carbon::create(2025, 3, 17),   // March 17, 2025
            'start_time' => '08:00',
            'end_time' => '18:00',
            'venue' => 'Beulah Family Church Main Auditorium',
            'registration_fee' => 150.00,
            'max_participants' => 500,
            'registration_deadline' => Carbon::create(2025, 3, 1), // March 1, 2025
            'status' => 'published',
            'requirements' => [
                'business_registration' => true,
                'contact_information' => true,
                'business_flyer_upload' => true,
            ],
            'terms_and_conditions' => 'By registering for ERGATES Conference 2025, you agree to: 1) Provide accurate business information, 2) Participate professionally and respectfully, 3) Allow photography and videography for promotional purposes, 4) Comply with all conference guidelines and schedules, 5) Pay registration fees as required. Refunds are available up to 7 days before the event. The organizers reserve the right to modify the program schedule if necessary.',
            'contact_email' => 'ergates2025@beulahfamily.org',
            'contact_phone' => '+233 24 123 4567',
            'allow_file_uploads' => true,
            'allowed_file_types' => ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi', 'mp3', 'wav'],
            'max_file_size' => 100, // 100 MB
            'max_files' => 5,
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
