<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestEmailCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test {email? : The email address to send test email to}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');
        
        if (!$email) {
            $email = $this->ask('Enter the email address to send test email to');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address!');
            return 1;
        }

        $this->info('Testing email configuration...');
        $this->info('Mail Driver: ' . config('mail.default'));
        $this->info('Mail Host: ' . config('mail.mailers.smtp.host'));
        $this->info('Mail Port: ' . config('mail.mailers.smtp.port'));
        $this->info('Mail Username: ' . config('mail.mailers.smtp.username'));
        $this->info('Sending test email to: ' . $email);
        $this->newLine();

        try {
            Mail::raw('This is a test email from Beulah Family Church Management System. If you receive this, your email configuration is working correctly!', function ($message) use ($email) {
                $message->to($email)
                        ->subject('Test Email from Church Management System')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $this->info('✓ Email sent successfully!');
            $this->info('Please check the inbox (and spam folder) of: ' . $email);
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Failed to send email!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Common issues:');
            $this->warn('1. Check your .env file for correct MAIL_* settings');
            $this->warn('2. For Gmail, use App Password not regular password');
            $this->warn('3. Check if firewall is blocking SMTP ports');
            $this->warn('4. Verify MAIL_HOST and MAIL_PORT are correct');
            return 1;
        }
    }
}
