<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Donation;
use App\Models\Member;
use App\Models\User;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $members = Member::limit(5)->get();
        $user = User::first();

        if (!$user) {
            $this->command->error('No users found. Please create a user first.');
            return;
        }

        $donationTypes = ['tithe', 'offering', 'building_fund', 'missions', 'special_offering'];
        $paymentMethods = ['cash', 'bank_transfer', 'mobile_money', 'card'];

        // Create donations for members
        foreach ($members as $member) {
            for ($i = 0; $i < rand(2, 5); $i++) {
                Donation::create([
                    'member_id' => $member->id,
                    'donor_name' => $member->full_name,
                    'donor_email' => $member->email,
                    'donor_phone' => $member->phone,
                    'amount' => rand(50, 1000),
                    'donation_type' => $donationTypes[array_rand($donationTypes)],
                    'purpose' => $this->getRandomPurpose(),
                    'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                    'donation_date' => Carbon::now()->subDays(rand(1, 90)),
                    'received_by' => $user->id,
                    'status' => 'confirmed',
                    'is_anonymous' => rand(0, 10) < 2, // 20% chance of anonymous
                    'tax_deductible' => true,
                    'notes' => rand(0, 3) == 0 ? 'Thank you for your generosity!' : null,
                ]);
            }
        }

        // Create some non-member donations
        for ($i = 0; $i < 10; $i++) {
            Donation::create([
                'member_id' => null,
                'donor_name' => $this->getRandomName(),
                'donor_email' => 'donor' . ($i + 1) . '@example.com',
                'donor_phone' => '+233' . rand(200000000, 299999999),
                'amount' => rand(25, 500),
                'donation_type' => $donationTypes[array_rand($donationTypes)],
                'purpose' => $this->getRandomPurpose(),
                'payment_method' => $paymentMethods[array_rand($paymentMethods)],
                'donation_date' => Carbon::now()->subDays(rand(1, 60)),
                'received_by' => $user->id,
                'status' => ['confirmed', 'pending'][array_rand(['confirmed', 'pending'])],
                'is_anonymous' => rand(0, 10) < 3, // 30% chance of anonymous
                'tax_deductible' => true,
            ]);
        }

        $this->command->info('Created test donations successfully!');
    }

    private function getRandomPurpose(): string
    {
        $purposes = [
            'Monthly tithe',
            'Sunday offering',
            'Building fund contribution',
            'Mission support',
            'Special thanksgiving',
            'Youth ministry support',
            'Church anniversary',
            'Harvest thanksgiving',
            'Christmas offering',
            'Easter special',
        ];

        return $purposes[array_rand($purposes)];
    }

    private function getRandomName(): string
    {
        $firstNames = ['John', 'Mary', 'David', 'Sarah', 'Michael', 'Grace', 'Peter', 'Ruth', 'Samuel', 'Esther'];
        $lastNames = ['Asante', 'Mensah', 'Osei', 'Boateng', 'Adjei', 'Owusu', 'Appiah', 'Agyei', 'Frimpong', 'Amoah'];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }
}
