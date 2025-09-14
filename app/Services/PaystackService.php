<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class PaystackService
{
    private $secretKey;
    private $publicKey;
    private $baseUrl;

    public function __construct()
    {
        $this->secretKey = config('services.paystack.secret_key');
        $this->publicKey = config('services.paystack.public_key');
        $this->baseUrl = 'https://api.paystack.co';
    }

    /**
     * Initialize a payment transaction
     */
    public function initializeTransaction($data)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/transaction/initialize', [
                'email' => $data['email'],
                'amount' => $data['amount'] * 100, // Convert to kobo
                'reference' => $data['reference'],
                'callback_url' => $data['callback_url'],
                'metadata' => [
                    'donor_name' => $data['donor_name'],
                    'donor_phone' => $data['donor_phone'] ?? null,
                    'purpose' => $data['purpose'],
                    'donation_type' => $data['donation_type'],
                    'member_id' => $data['member_id'] ?? null,
                    'is_anonymous' => $data['is_anonymous'] ?? false,
                ],
                'channels' => ['card', 'bank', 'ussd', 'mobile_money'],
            ]);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data']
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Transaction initialization failed'
            ];

        } catch (Exception $e) {
            Log::error('Paystack Initialize Transaction Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payment service unavailable'
            ];
        }
    }

    /**
     * Verify a payment transaction
     */
    public function verifyTransaction($reference)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transaction/verify/' . $reference);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data']
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Transaction verification failed'
            ];

        } catch (Exception $e) {
            Log::error('Paystack Verify Transaction Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Payment verification failed'
            ];
        }
    }

    /**
     * Get transaction details
     */
    public function getTransaction($transactionId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transaction/' . $transactionId);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data']
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Transaction not found'
            ];

        } catch (Exception $e) {
            Log::error('Paystack Get Transaction Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Transaction lookup failed'
            ];
        }
    }

    /**
     * List all transactions
     */
    public function listTransactions($params = [])
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get($this->baseUrl . '/transaction', $params);

            if ($response->successful()) {
                return [
                    'status' => true,
                    'data' => $response->json()['data']
                ];
            }

            return [
                'status' => false,
                'message' => $response->json()['message'] ?? 'Failed to fetch transactions'
            ];

        } catch (Exception $e) {
            Log::error('Paystack List Transactions Error: ' . $e->getMessage());
            return [
                'status' => false,
                'message' => 'Failed to fetch transactions'
            ];
        }
    }

    /**
     * Generate unique reference
     */
    public function generateReference($prefix = 'DON')
    {
        return $prefix . '_' . time() . '_' . uniqid();
    }

    /**
     * Get public key for frontend
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * Calculate Paystack fees
     */
    public function calculateFees($amount)
    {
        // Paystack charges 1.5% + ₦100 for local cards
        // and 3.9% for international cards
        // For simplicity, we'll use 1.5% + ₦100
        $percentage = 0.015;
        $fixedFee = 100;
        
        $fee = ($amount * $percentage) + $fixedFee;
        
        // Cap at ₦2000
        return min($fee, 2000);
    }
}
