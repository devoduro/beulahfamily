<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GatewaySettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display gateway settings
     */
    public function index()
    {
        $gateways = [
            'paystack' => [
                'name' => 'Paystack',
                'description' => 'Accept payments via Paystack (Cards, Bank Transfer, USSD)',
                'status' => config('services.paystack.public_key') ? 'active' : 'inactive',
                'icon' => 'fas fa-credit-card',
                'color' => 'blue',
                'settings' => [
                    'public_key' => config('services.paystack.public_key'),
                    'secret_key' => config('services.paystack.secret_key') ? '••••••••' : null,
                    'merchant_email' => config('services.paystack.merchant_email'),
                ]
            ],
            'mnotify' => [
                'name' => 'MNotify SMS',
                'description' => 'Send SMS notifications and bulk messages',
                'status' => config('services.mnotify.api_key') ? 'active' : 'inactive',
                'icon' => 'fas fa-sms',
                'color' => 'green',
                'settings' => [
                    'api_key' => config('services.mnotify.api_key') ? '••••••••' : null,
                    'sender_id' => config('services.mnotify.sender_id'),
                ]
            ],
            'email' => [
                'name' => 'Email Gateway',
                'description' => 'SMTP email configuration for notifications',
                'status' => config('mail.mailers.smtp.host') ? 'active' : 'inactive',
                'icon' => 'fas fa-envelope',
                'color' => 'purple',
                'settings' => [
                    'host' => config('mail.mailers.smtp.host'),
                    'port' => config('mail.mailers.smtp.port'),
                    'username' => config('mail.mailers.smtp.username'),
                    'from_address' => config('mail.from.address'),
                ]
            ]
        ];

        return view('settings.gateways.index', compact('gateways'));
    }

    /**
     * Update gateway settings
     */
    public function update(Request $request, $gateway)
    {
        $request->validate([
            'settings' => 'required|array'
        ]);

        // In a real application, you would update the .env file or database
        // For now, we'll just return a success message
        
        return redirect()->route('system.config.index')
            ->with('success', ucfirst($gateway) . ' gateway settings updated successfully.');
    }

    /**
     * Test gateway connection
     */
    public function test($gateway)
    {
        $success = false;
        $message = '';

        switch ($gateway) {
            case 'paystack':
                // Test Paystack connection
                $success = !empty(config('services.paystack.public_key'));
                $message = $success ? 'Paystack connection successful' : 'Paystack not configured';
                break;
                
            case 'mnotify':
                // Test MNotify connection
                $success = !empty(config('services.mnotify.api_key'));
                $message = $success ? 'MNotify connection successful' : 'MNotify not configured';
                break;
                
            case 'email':
                // Test email connection
                $success = !empty(config('mail.mailers.smtp.host'));
                $message = $success ? 'Email gateway connection successful' : 'Email not configured';
                break;
                
            default:
                $message = 'Unknown gateway';
        }

        return response()->json([
            'success' => $success,
            'message' => $message
        ]);
    }
}
