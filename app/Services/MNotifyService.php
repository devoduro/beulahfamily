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
        $this->baseUrl = 'https://sms.pastechsolutions.com';
    }

    /**
     * Send SMS to a single recipient.
     */
    public function sendSMS($recipient, $message, $senderId = null)
    {
        try {
            $url = $this->baseUrl . '/smsapi?' . http_build_query([
                'key' => $this->apiKey,
                'to' => $this->formatPhoneNumber($recipient),
                'msg' => $message,
                'sender_id' => $senderId ?? $this->senderId
            ]);

            $response = Http::timeout(30)->get($url);
            $responseData = $response->json();

            if ($response->successful() && isset($responseData['code']) && $responseData['code'] === '1000') {
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
                $errorMessage = isset($responseData['code']) ? 
                    $this->getPastechErrorMessage($responseData['code']) : 
                    'SMS sending failed';

                Log::error('SMS sending failed', [
                    'recipient' => $recipient,
                    'response' => $responseData,
                    'status_code' => $response->status(),
                    'error' => $errorMessage
                ]);

                return [
                    'success' => false,
                    'error' => $errorMessage,
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
            // Try both Pastech balance endpoints
            $endpoints = [
                $this->baseUrl . '/api/smsapibalance?key=' . $this->apiKey,
                $this->baseUrl . '/api/balance/sms?key=' . $this->apiKey
            ];

            foreach ($endpoints as $url) {
                $response = Http::timeout(30)->get($url);
                $responseBody = $response->body();
                
                // Try to parse as JSON first
                $responseData = json_decode($responseBody, true);
                
                Log::info('Pastech SMS API Test', [
                    'url' => $url,
                    'status_code' => $response->status(),
                    'raw_response' => $responseBody,
                    'parsed_response' => $responseData
                ]);

                if ($response->successful()) {
                    // Check if response is just a number (balance)
                    if (is_numeric($responseBody)) {
                        return [
                            'success' => true,
                            'balance' => (float) $responseBody,
                            'currency' => 'GHS',
                            'response' => $responseBody,
                            'endpoint_used' => $url
                        ];
                    }
                    
                    // Check if response contains balance information in JSON
                    if (is_array($responseData)) {
                        if (isset($responseData['balance']) || isset($responseData['credit_balance']) || isset($responseData['credits'])) {
                            $balance = $responseData['balance'] ?? $responseData['credit_balance'] ?? $responseData['credits'] ?? 0;
                            
                            return [
                                'success' => true,
                                'balance' => (float) $balance,
                                'currency' => $responseData['currency'] ?? 'GHS',
                                'response' => $responseData,
                                'endpoint_used' => $url
                            ];
                        }
                        
                        // Check for success response codes (1000 = success)
                        if (isset($responseData['code']) && $responseData['code'] == '1000') {
                            $balance = $responseData['balance'] ?? $responseData['credit_balance'] ?? $responseData['credits'] ?? 0;
                            
                            return [
                                'success' => true,
                                'balance' => (float) $balance,
                                'currency' => 'GHS',
                                'response' => $responseData,
                                'endpoint_used' => $url
                            ];
                        }
                        
                        // Check for specific error codes
                        if (isset($responseData['code'])) {
                            $errorMessage = $this->getPastechErrorMessage($responseData['code']);
                            if ($errorMessage) {
                                return [
                                    'success' => false,
                                    'error' => $errorMessage,
                                    'response' => $responseData,
                                    'balance' => 0
                                ];
                            }
                        }
                    }
                    
                    // If response is successful but no balance found, try next endpoint
                    continue;
                }
            }

            // If all endpoints failed
            return [
                'success' => false,
                'error' => 'Failed to get balance from all endpoints',
                'response' => $responseData ?? null,
                'balance' => 0
            ];

        } catch (\Exception $e) {
            Log::error('Balance check error: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString(),
                'api_key_prefix' => substr($this->apiKey ?? '', 0, 10) . '...'
            ]);
            return [
                'success' => false,
                'error' => 'Balance check error: ' . $e->getMessage(),
                'balance' => 0
            ];
        }
    }

    /**
     * Get error message for Pastech response codes
     */
    private function getPastechErrorMessage($code)
    {
        $errorCodes = [
            '1000' => null, // Success
            '1002' => 'SMS sending failed',
            '1003' => 'Insufficient balance',
            '1004' => 'Invalid API key',
            '1005' => 'Invalid phone number',
            '1006' => 'Invalid sender ID (must not be more than 11 characters)',
            '1007' => 'Message scheduled for later delivery',
            '1008' => 'Empty message'
        ];

        return $errorCodes[$code] ?? 'Unknown error code: ' . $code;
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
