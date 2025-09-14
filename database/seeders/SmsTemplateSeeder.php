<?php

namespace Database\Seeders;

use App\Models\SmsTemplate;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SmsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the first admin user
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $this->command->error('No admin user found. Please create an admin user first.');
            return;
        }

        $templates = [
            [
                'name' => 'Birthday Wishes',
                'category' => 'birthday',
                'message' => 'Happy Birthday {{first_name}}! 🎉 May God bless you with many more years of joy, health, and prosperity. We celebrate you today at {{church_name}}!',
                'description' => 'Default birthday message for church members',
                'is_active' => true,
                'variables' => ['first_name', 'church_name'],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Welcome New Member',
                'category' => 'welcome',
                'message' => 'Welcome to {{church_name}}, {{first_name}}! We are excited to have you join our church family. God bless you!',
                'description' => 'Welcome message for new church members',
                'is_active' => true,
                'variables' => ['first_name', 'church_name'],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Sunday Service Reminder',
                'category' => 'reminder',
                'message' => 'Good morning {{first_name}}! Don\'t forget our Sunday service today at {{event_time}}. See you at {{church_name}}!',
                'description' => 'Reminder for Sunday service attendance',
                'is_active' => true,
                'variables' => ['first_name', 'event_time', 'church_name'],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Event Announcement',
                'category' => 'event',
                'message' => 'Hi {{first_name}}! Join us for {{event_name}} on {{event_date}} at {{event_time}}. Location: {{church_name}}. God bless!',
                'description' => 'General event announcement template',
                'is_active' => true,
                'variables' => ['first_name', 'event_name', 'event_date', 'event_time', 'church_name'],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Donation Thank You',
                'category' => 'thank_you',
                'message' => 'Thank you {{first_name}} for your generous donation of GHS {{amount}}. Your support helps us serve God better at {{church_name}}!',
                'description' => 'Thank you message for donations',
                'is_active' => true,
                'variables' => ['first_name', 'amount', 'church_name'],
                'created_by' => $adminUser->id,
            ],
            [
                'name' => 'Emergency Alert',
                'category' => 'emergency',
                'message' => 'URGENT: {{message}}. Please contact the church office immediately. {{church_name}}',
                'description' => 'Emergency notification template',
                'is_active' => true,
                'variables' => ['message', 'church_name'],
                'created_by' => $adminUser->id,
            ]
        ];

        foreach ($templates as $template) {
            SmsTemplate::create($template);
        }

        $this->command->info('SMS templates created successfully!');
    }
}
