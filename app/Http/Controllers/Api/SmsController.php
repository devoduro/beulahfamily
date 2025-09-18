<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MNotifyService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    protected $mnotifyService;

    public function __construct(MNotifyService $mnotifyService)
    {
        $this->middleware('auth');
        $this->mnotifyService = $mnotifyService;
    }

    /**
     * Get SMS balance from MNotify
     */
    public function getBalance()
    {
        try {
            $result = $this->mnotifyService->getBalance();
            
            return response()->json([
                'success' => $result['success'],
                'balance' => $result['balance'] ?? 0,
                'currency' => $result['currency'] ?? 'GHS',
                'error' => $result['error'] ?? null
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'balance' => 0,
                'error' => 'Failed to fetch SMS balance: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get SMS statistics and usage
     */
    public function getStats()
    {
        try {
            $balance = $this->mnotifyService->getBalance();
            
            // You can add more statistics here like:
            // - SMS sent this month
            // - SMS delivery rates
            // - Cost analysis
            
            return response()->json([
                'success' => true,
                'balance' => $balance['balance'] ?? 0,
                'currency' => $balance['currency'] ?? 'GHS',
                'stats' => [
                    'balance_status' => $balance['success'] ? 'active' : 'error',
                    'last_updated' => now()->toISOString()
                ]
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch SMS statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}
