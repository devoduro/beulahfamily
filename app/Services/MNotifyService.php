<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;

class MNotifyService
{
    protected $apiKey;
    protected $senderId;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.mnotify.api_key');
        $this->senderId = config('services.mnotify.sender_id');
        $this->baseUrl = 'https://api.mnotify.com/api';
    }

    /**
     * Send SMS to a single recipient.
     */
    public function sendSMS($recipient, $message, $senderId = null)
    {
        try {
            $response = Http::timeout(30)->post($this->baseUrl . '/sms/quick', [
                'recipient' => [$this->formatPhoneNumber($recipient)],
                'sender' => $senderId ?? $this->senderId,
                'message' => $message,
                'is_schedule' => false,
                'schedule_date' => ''
            ], [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['code']) && $responseData['code'] === '2000') {
                Log::info('SMS sent successfully', [
                    'recipient' => $recipient,
                    'message_id' => $responseData['message_id'] ?? null,
                    'cost' => $responseData['cost'] ?? null
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'cost' => $responseData['cost'] ?? 0,
                    'response' => $responseData
                ];
            } else {
                Log::error('SMS sending failed', [
                    'recipient' => $recipient,
                    'response' => $responseData,
                    'status_code' => $response->status()
                ]);

                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'SMS sending failed',
                    'response' => $responseData
                ];
            }

        } catch (\Exception $e) {
            Log::error('SMS service error: ' . $e->getMessage(), [
                'recipient' => $recipient,
                'exception' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'SMS service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk SMS to multiple recipients.
     */
    public function sendBulkSMS($recipients, $message, $senderId = null)
    {
        try {
            // Format all phone numbers
            $formattedRecipients = array_map([$this, 'formatPhoneNumber'], $recipients);

            $response = Http::timeout(60)->post($this->baseUrl . '/sms/quick', [
                'recipient' => $formattedRecipients,
                'sender' => $senderId ?? $this->senderId,
                'message' => $message,
                'is_schedule' => false,
                'schedule_date' => ''
            ], [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['code']) && $responseData['code'] === '2000') {
                Log::info('Bulk SMS sent successfully', [
                    'recipient_count' => count($recipients),
                    'message_id' => $responseData['message_id'] ?? null,
                    'cost' => $responseData['cost'] ?? null
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'cost' => $responseData['cost'] ?? 0,
                    'recipient_count' => count($recipients),
                    'response' => $responseData
                ];
            } else {
                Log::error('Bulk SMS sending failed', [
                    'recipient_count' => count($recipients),
                    'response' => $responseData,
                    'status_code' => $response->status()
                ]);

                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'Bulk SMS sending failed',
                    'response' => $responseData
                ];
            }

        } catch (\Exception $e) {
            Log::error('Bulk SMS service error: ' . $e->getMessage(), [
                'recipient_count' => count($recipients),
                'exception' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => 'Bulk SMS service error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Schedule SMS for future delivery.
     */
    public function scheduleSMS($recipients, $message, $scheduleDate, $senderId = null)
    {
        try {
            $formattedRecipients = is_array($recipients) 
                ? array_map([$this, 'formatPhoneNumber'], $recipients)
                : [$this->formatPhoneNumber($recipients)];

            $response = Http::timeout(30)->post($this->baseUrl . '/sms/quick', [
                'recipient' => $formattedRecipients,
                'sender' => $senderId ?? $this->senderId,
                'message' => $message,
                'is_schedule' => true,
                'schedule_date' => $scheduleDate
            ], [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['code']) && $responseData['code'] === '2000') {
                Log::info('SMS scheduled successfully', [
                    'recipient_count' => count($formattedRecipients),
                    'schedule_date' => $scheduleDate,
                    'message_id' => $responseData['message_id'] ?? null
                ]);

                return [
                    'success' => true,
                    'message_id' => $responseData['message_id'] ?? null,
                    'cost' => $responseData['cost'] ?? 0,
                    'response' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => $responseData['message'] ?? 'SMS scheduling failed',
                    'response' => $responseData
                ];
            }

        } catch (\Exception $e) {
            Log::error('SMS scheduling error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'SMS scheduling error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get SMS delivery report.
     */
    public function getDeliveryReport($messageId)
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . '/sms/report/' . $messageId, [], [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

            $responseData = $response->json();

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $responseData
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to get delivery report',
                    'response' => $responseData
                ];
            }

        } catch (\Exception $e) {
            Log::error('Delivery report error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Delivery report error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get account balance.
     */
    public function getBalance()
    {
        try {
            $response = Http::timeout(30)->get($this->baseUrl . '/balance', [], [
                'Authorization' => 'Bearer ' . $this->apiKey,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['code']) && $responseData['code'] === '2000') {
                return [
                    'success' => true,
                    'balance' => $responseData['balance'] ?? 0,
                    'currency' => $responseData['currency'] ?? 'GHS'
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Failed to get balance',
                    'response' => $responseData
                ];
            }

        } catch (\Exception $e) {
            Log::error('Balance check error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Balance check error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Format phone number to international format.
     */
    public function formatPhoneNumber($phoneNumber)
    {
        // Remove all non-numeric characters
        $cleaned = preg_replace('/[^0-9]/', '', $phoneNumber);
        
        // Handle Ghana phone numbers
        if (strlen($cleaned) === 10 && substr($cleaned, 0, 1) === '0') {
            // Convert 0XXXXXXXXX to 233XXXXXXXXX
            return '233' . substr($cleaned, 1);
        } elseif (strlen($cleaned) === 9) {
            // Convert XXXXXXXXX to 233XXXXXXXXX
            return '233' . $cleaned;
        } elseif (strlen($cleaned) === 12 && substr($cleaned, 0, 3) === '233') {
            // Already in correct format
            return $cleaned;
        }
        
        // Return as is if we can't determine the format
        return $cleaned;
    }

    /**
     * Validate phone number format.
     */
    public function isValidPhoneNumber($phoneNumber)
    {
        $formatted = $this->formatPhoneNumber($phoneNumber);
        
        // Check if it's a valid Ghana number (233XXXXXXXXX)
        return preg_match('/^233[0-9]{9}$/', $formatted);
    }

    /**
     * Calculate SMS cost estimation.
     */
    public function estimateCost($message, $recipientCount = 1)
    {
        // SMS length calculation (160 characters per SMS)
        $messageLength = strlen($message);
        $smsCount = ceil($messageLength / 160);
        
        // Estimated cost per SMS in Ghana (this should be configured)
        $costPerSms = config('services.mnotify.cost_per_sms', 0.05);
        
        return [
            'message_length' => $messageLength,
            'sms_count' => $smsCount,
            'recipient_count' => $recipientCount,
            'total_sms' => $smsCount * $recipientCount,
            'estimated_cost' => $smsCount * $recipientCount * $costPerSms,
            'currency' => 'GHS'
        ];
    }
}
