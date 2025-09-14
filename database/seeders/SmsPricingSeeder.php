<?php

namespace Database\Seeders;

use App\Models\SmsPricing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsPricingSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $pricingPlans = [
            [
                'name' => 'Starter Pack',
                'description' => 'Perfect for small announcements and reminders',
                'credits' => 100,
                'price' => 10.00,
                'bonus_credits' => 0,
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => 'Basic Pack',
                'description' => 'Great for regular church communications',
                'credits' => 250,
                'price' => 22.50,
                'bonus_credits' => 10, // 10% bonus
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => 'Standard Pack',
                'description' => 'Most popular choice for active churches',
                'credits' => 500,
                'price' => 40.00,
                'bonus_credits' => 15, // 15% bonus
                'is_active' => true,
                'sort_order' => 3
            ],
            [
                'name' => 'Premium Pack',
                'description' => 'Best value for large congregations',
                'credits' => 1000,
                'price' => 70.00,
                'bonus_credits' => 20, // 20% bonus
                'is_active' => true,
                'sort_order' => 4
            ],
            [
                'name' => 'Enterprise Pack',
                'description' => 'Maximum capacity for mega churches',
                'credits' => 2500,
                'price' => 150.00,
                'bonus_credits' => 25, // 25% bonus
                'is_active' => true,
                'sort_order' => 5
            ],
            [
                'name' => 'Unlimited Monthly',
                'description' => 'Unlimited SMS for one month',
                'credits' => 10000,
                'price' => 300.00,
                'bonus_credits' => 0,
                'is_active' => true,
                'sort_order' => 6
            ]
        ];

        foreach ($pricingPlans as $plan) {
            SmsPricing::create($plan);
        }
    }
}
