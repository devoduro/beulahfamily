<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberLoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample members with login credentials
        $members = [
            [
                'member_id' => '20240001',
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@example.com',
                'phone' => '+233241234567',
                'date_of_birth' => '1985-06-15',
                'gender' => 'male',
                'marital_status' => 'married',
                'membership_date' => '2020-01-15',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'chapter' => 'ACCRA',
                'address' => '123 Main Street, East Legon',
                'city' => 'Accra',
                'state' => 'Greater Accra',
                'country' => 'Ghana',
                'occupation' => 'Software Engineer',
                'employer' => 'Tech Solutions Ltd',
                'emergency_contact_name' => 'Jane Doe',
                'emergency_contact_phone' => '+233241234568',
                'is_baptized' => true,
                'baptism_date' => '2020-02-01',
                'is_confirmed' => true,
                'confirmation_date' => '2020-03-01',
                'receive_newsletter' => true,
                'receive_sms' => true,
                'is_active' => true,
                'password' => Hash::make('password123'),
            ],
            [
                'member_id' => '20240002',
                'first_name' => 'Mary',
                'last_name' => 'Johnson',
                'email' => 'mary.johnson@example.com',
                'phone' => '+233241234569',
                'date_of_birth' => '1990-03-22',
                'gender' => 'female',
                'marital_status' => 'single',
                'membership_date' => '2021-05-10',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'chapter' => 'KUMASI',
                'address' => '456 Oak Avenue, Asokore Mampong',
                'city' => 'Kumasi',
                'state' => 'Ashanti',
                'country' => 'Ghana',
                'occupation' => 'Teacher',
                'employer' => 'Kumasi Senior High School',
                'emergency_contact_name' => 'Sarah Johnson',
                'emergency_contact_phone' => '+233241234570',
                'is_baptized' => true,
                'baptism_date' => '2021-06-01',
                'is_confirmed' => false,
                'receive_newsletter' => true,
                'receive_sms' => false,
                'is_active' => true,
                'password' => Hash::make('mary2024'),
            ],
            [
                'member_id' => '20240003',
                'first_name' => 'David',
                'last_name' => 'Wilson',
                'email' => 'david.wilson@example.com',
                'phone' => '+233241234571',
                'whatsapp_phone' => '+233241234571',
                'date_of_birth' => '1978-11-08',
                'gender' => 'male',
                'marital_status' => 'married',
                'membership_date' => '2019-08-20',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'chapter' => 'NEW JESSY',
                'address' => '789 Pine Street, East Orange',
                'city' => 'Orange',
                'state' => 'New Jersey',
                'country' => 'USA',
                'occupation' => 'Business Analyst',
                'employer' => 'Corporate Solutions Inc',
                'emergency_contact_name' => 'Lisa Wilson',
                'emergency_contact_phone' => '+1234567890',
                'is_baptized' => true,
                'baptism_date' => '2019-09-15',
                'is_confirmed' => true,
                'confirmation_date' => '2019-10-15',
                'skills_talents' => ['leadership', 'public_speaking', 'music'],
                'interests' => ['youth_ministry', 'music_ministry'],
                'receive_newsletter' => true,
                'receive_sms' => true,
                'is_active' => true,
                'password' => Hash::make('david123'),
            ],
            [
                'member_id' => '20240004',
                'first_name' => 'Grace',
                'last_name' => 'Asante',
                'email' => 'grace.asante@example.com',
                'phone' => '+233241234572',
                'date_of_birth' => '1995-07-12',
                'gender' => 'female',
                'marital_status' => 'single',
                'membership_date' => '2022-01-01',
                'membership_status' => 'active',
                'membership_type' => 'member',
                'chapter' => 'ACCRA',
                'address' => '321 Cedar Road, Tema',
                'city' => 'Tema',
                'state' => 'Greater Accra',
                'country' => 'Ghana',
                'occupation' => 'Nurse',
                'employer' => 'Tema General Hospital',
                'emergency_contact_name' => 'Emmanuel Asante',
                'emergency_contact_phone' => '+233241234573',
                'is_baptized' => true,
                'baptism_date' => '2022-02-01',
                'is_confirmed' => false,
                'skills_talents' => ['healthcare', 'counseling'],
                'interests' => ['health_ministry', 'womens_ministry'],
                'receive_newsletter' => true,
                'receive_sms' => true,
                'is_active' => true,
                'password' => Hash::make('grace2024'),
            ],
        ];

        foreach ($members as $memberData) {
            Member::updateOrCreate(
                ['member_id' => $memberData['member_id']],
                $memberData
            );
        }

        $this->command->info('Sample members with login credentials created successfully!');
        $this->command->info('Test Login Credentials:');
        $this->command->info('1. Member ID: 20240001, Email: john.doe@example.com, Password: password123');
        $this->command->info('2. Member ID: 20240002, Email: mary.johnson@example.com, Password: mary2024');
        $this->command->info('3. Member ID: 20240003, Email: david.wilson@example.com, Password: david123');
        $this->command->info('4. Member ID: 20240004, Email: grace.asante@example.com, Password: grace2024');
    }
}
