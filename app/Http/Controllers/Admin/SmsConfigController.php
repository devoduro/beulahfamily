<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\MNotifyService;
use Illuminate\Http\Request;

class SmsConfigController extends Controller
{
    protected $mnotifyService;

    public function __construct(MNotifyService $mnotifyService)
    {
        $this->middleware('auth');
        $this->mnotifyService = $mnotifyService;
    }

    /**
     * Show SMS configuration page
     */
    public function index()
    {
        // Get current balance
        $balance = $this->mnotifyService->getBalance();
        
        return view('admin.sms-config', compact('balance'));
    }

    /**
     * Test SMS configuration
     */
    public function test()
    {
        $result = $this->mnotifyService->getBalance();
        
        return response()->json([
            'success' => $result['success'],
            'balance' => $result['balance'] ?? 0,
            'currency' => $result['currency'] ?? 'GHS',
            'error' => $result['error'] ?? null,
            'api_configured' => !empty(config('services.mnotify.api_key')),
            'sender_configured' => !empty(config('services.mnotify.sender_id'))
        ]);
    }
}
