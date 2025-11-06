<?php

namespace App\Http\Controllers;

use App\Models\SmsMessage;
use App\Models\SmsTemplate;
use App\Models\SmsRecipient;
use App\Models\Member;
use App\Services\MNotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SmsController extends Controller
{
    protected $mnotifyService;

    public function __construct(MNotifyService $mnotifyService)
    {
        $this->mnotifyService = $mnotifyService;
    }

    /**
     * Display SMS messages list.
     */
    public function index(Request $request)
    {
        $query = SmsMessage::with(['sender', 'template'])
                          ->orderBy('created_at', 'desc');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('message', 'like', "%{$search}%")
                  ->orWhereHas('sender', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Status filter
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $smsMessages = $query->paginate(20);

        return view('sms.index', compact('smsMessages'));
    }

    /**
     * Show the form for creating a new SMS.
     */
    public function create()
    {
        $templates = SmsTemplate::active()->orderBy('name')->get();
        $members = Member::select('id', 'first_name', 'last_name', 'phone', 'chapter', 'gender', 'marital_status', 'date_of_birth', 'photo')
                        ->whereNotNull('phone')
                        ->orderBy('first_name')
                        ->get();

        // Get filter options
        $chapters = Member::distinct()->pluck('chapter')->filter()->sort()->values();
        $genders = ['male' => 'Male', 'female' => 'Female'];
        $maritalStatuses = [
            'single' => 'Single',
            'married' => 'Married', 
            'divorced' => 'Divorced',
            'widowed' => 'Widowed'
        ];

        // Get balance from MNotify
        $balance = $this->mnotifyService->getBalance();

        return view('sms.create', compact('templates', 'members', 'balance', 'chapters', 'genders', 'maritalStatuses'));
    }

    /**
     * Store a newly created SMS.
     */
    public function store(Request $request)
    {
        // Get valid chapters from database
        $validChapters = Member::distinct()->pluck('chapter')->filter()->toArray();
        $chapterValidation = !empty($validChapters) ? 'nullable|required_if:recipient_type,chapter|in:' . implode(',', $validChapters) : 'nullable|required_if:recipient_type,chapter|string';
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1600',
            'recipient_type' => 'required|in:all,members,chapter,custom',
            'chapter' => $chapterValidation,
            'custom_recipients' => 'nullable|required_if:recipient_type,custom|array',
            'custom_recipients.*' => 'exists:members,id',
            'template_id' => 'nullable|exists:sms_templates,id',
            'sender_name' => 'nullable|string|max:11',
            'is_scheduled' => 'boolean',
            'scheduled_at' => 'required_if:is_scheduled,true|date|after:now',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get recipients based on type
            $recipients = $this->getRecipients($request);

            if (empty($recipients)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No valid recipients found'
                ], 400);
            }

            // Calculate cost estimation
            $costEstimate = $this->mnotifyService->estimateCost($request->message, count($recipients));

            // Create SMS message record
            $smsMessage = SmsMessage::create([
                'sender_id' => Auth::id(),
                'template_id' => $request->template_id,
                'title' => $request->title,
                'message' => $request->message,
                'recipient_type' => $request->recipient_type,
                'recipient_filter' => $this->getRecipientFilter($request),
                'total_recipients' => count($recipients),
                'status' => 'pending',
                'scheduled_at' => $request->is_scheduled ? $request->scheduled_at : null,
                'estimated_cost' => $costEstimate['estimated_cost'],
                'sender_name' => $request->sender_name,
                'is_scheduled' => $request->boolean('is_scheduled'),
            ]);

            // Create recipient records
            foreach ($recipients as $recipient) {
                SmsRecipient::create([
                    'sms_message_id' => $smsMessage->id,
                    'member_id' => $recipient['member_id'] ?? null,
                    'phone_number' => $recipient['phone'],
                    'recipient_name' => $recipient['name'],
                    'status' => 'pending',
                ]);
            }

            // If not scheduled, send immediately
            if (!$request->boolean('is_scheduled')) {
                $this->sendSmsMessage($smsMessage);
            }

            // Update template usage count
            if ($request->template_id) {
                SmsTemplate::find($request->template_id)->incrementUsage();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $request->boolean('is_scheduled') ? 'SMS scheduled successfully' : 'SMS sent successfully',
                'sms_id' => $smsMessage->id,
                'cost_estimate' => $costEstimate
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SMS creation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create SMS. Please try again.'
            ], 500);
        }
    }

    /**
     * Display the specified SMS message.
     */
    public function show($id)
    {
        $smsMessage = SmsMessage::with(['sender', 'template', 'recipients.member'])
                               ->findOrFail($id);

        return view('sms.show', compact('smsMessage'));
    }

    /**
     * Send SMS message.
     */
    private function sendSmsMessage(SmsMessage $smsMessage)
    {
        try {
            $smsMessage->update(['status' => 'sending']);

            $recipients = $smsMessage->recipients()->with('member')->get();
            $successCount = 0;
            $failCount = 0;
            $totalCost = 0;
            
            // Check if message has placeholders
            $hasPlaceholders = preg_match('/\{\{[^}]+\}\}/', $smsMessage->message);
            
            if ($hasPlaceholders) {
                // Send personalized messages individually
                foreach ($recipients as $recipient) {
                    $personalizedMessage = $this->replacePlaceholders(
                        $smsMessage->message, 
                        $recipient->member
                    );
                    
                    $result = $this->mnotifyService->sendSMS(
                        $recipient->phone_number,
                        $personalizedMessage,
                        $smsMessage->sender_name
                    );
                    
                    if ($result['success']) {
                        $recipient->update([
                            'status' => 'sent',
                            'sent_at' => now(),
                            'cost' => $result['cost']
                        ]);
                        $successCount++;
                        $totalCost += $result['cost'];
                    } else {
                        $recipient->update([
                            'status' => 'failed',
                            'failed_at' => now(),
                            'failure_reason' => $result['error']
                        ]);
                        $failCount++;
                    }
                }
                
                // Update message with aggregated results
                if ($successCount > 0) {
                    $smsMessage->markAsSent(null, $totalCost);
                } else {
                    $smsMessage->markAsFailed('All messages failed to send');
                }
                
            } else {
                // Send bulk SMS without personalization
                $phoneNumbers = $recipients->pluck('phone_number')->toArray();
                
                $result = $this->mnotifyService->sendBulkSMS(
                    $phoneNumbers,
                    $smsMessage->message,
                    $smsMessage->sender_name
                );

                if ($result['success']) {
                    $smsMessage->markAsSent($result['message_id'], $result['cost']);
                    
                    // Update all recipients as sent
                    $smsMessage->recipients()->update([
                        'status' => 'sent',
                        'sent_at' => now(),
                        'cost' => $result['cost'] / count($phoneNumbers)
                    ]);
                    $successCount = count($phoneNumbers);
                } else {
                    $smsMessage->markAsFailed($result['error']);
                    
                    // Mark all recipients as failed
                    $smsMessage->recipients()->update([
                        'status' => 'failed',
                        'failed_at' => now(),
                        'failure_reason' => $result['error']
                    ]);
                    $failCount = count($phoneNumbers);
                }
            }

            $smsMessage->updateDeliveryStats($successCount, $failCount);

        } catch (\Exception $e) {
            Log::error('SMS sending failed: ' . $e->getMessage());
            $smsMessage->markAsFailed($e->getMessage());
        }
    }
    
    /**
     * Replace placeholders in message with actual member data.
     */
    private function replacePlaceholders($message, $member)
    {
        if (!$member) {
            return $message;
        }
        
        // Get church name from settings
        $churchName = \App\Models\Setting::getValue('organization_name', 'general', config('app.name', 'Beulah Family'));
        
        $replacements = [
            '{{first_name}}' => $member->first_name ?? '',
            '{{last_name}}' => $member->last_name ?? '',
            '{{full_name}}' => trim(($member->first_name ?? '') . ' ' . ($member->last_name ?? '')),
            '{{phone}}' => $member->phone ?? '',
            '{{email}}' => $member->email ?? '',
            '{{chapter}}' => $member->chapter ?? '',
            '{{gender}}' => $member->gender ?? '',
            '{{marital_status}}' => $member->marital_status ?? '',
            '{{church_name}}' => $churchName,
            '{{organization_name}}' => $churchName,
        ];
        
        return str_replace(array_keys($replacements), array_values($replacements), $message);
    }

    /**
     * Get recipients based on request type.
     */
    private function getRecipients(Request $request)
    {
        $recipients = [];

        switch ($request->recipient_type) {
            case 'all':
                $members = Member::whereNotNull('phone')->get();
                break;
            case 'members':
                $members = Member::whereNotNull('phone')->get();
                break;
            case 'chapter':
                $members = Member::where('chapter', $request->chapter)
                                ->whereNotNull('phone')
                                ->get();
                break;
            case 'custom':
                $members = Member::whereIn('id', $request->custom_recipients)
                                ->whereNotNull('phone')
                                ->get();
                break;
            default:
                $members = collect();
        }

        foreach ($members as $member) {
            if ($this->mnotifyService->isValidPhoneNumber($member->phone)) {
                $recipients[] = [
                    'member_id' => $member->id,
                    'phone' => $member->phone,
                    'name' => $member->first_name . ' ' . $member->last_name
                ];
            }
        }

        return $recipients;
    }

    /**
     * Get recipient filter for storage.
     */
    private function getRecipientFilter(Request $request)
    {
        $filter = ['type' => $request->recipient_type];

        switch ($request->recipient_type) {
            case 'chapter':
                $filter['chapter'] = $request->chapter;
                break;
            case 'custom':
                $filter['member_ids'] = $request->custom_recipients;
                break;
        }

        return $filter;
    }

    /**
     * Get SMS statistics.
     */
    public function stats(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $stats = [
            'total_sent' => SmsMessage::sent()->count(),
            'total_pending' => SmsMessage::pending()->count(),
            'total_failed' => SmsMessage::failed()->count(),
            'total_scheduled' => SmsMessage::scheduled()->count(),
            'monthly_sent' => SmsMessage::sent()
                                    ->whereYear('sent_at', $year)
                                    ->whereMonth('sent_at', $month)
                                    ->count(),
            'total_cost' => SmsMessage::sent()->sum('actual_cost'),
            'success_rate' => $this->calculateSuccessRate(),
            'monthly_stats' => $this->getMonthlyStats($year),
        ];

        return response()->json($stats);
    }

    /**
     * Calculate overall success rate.
     */
    private function calculateSuccessRate()
    {
        $total = SmsMessage::whereIn('status', ['sent', 'failed'])->sum('total_recipients');
        $successful = SmsMessage::sent()->sum('successful_sends');

        return $total > 0 ? round(($successful / $total) * 100, 2) : 0;
    }

    /**
     * Get monthly statistics.
     */
    private function getMonthlyStats($year)
    {
        return SmsMessage::sent()
                        ->whereYear('sent_at', $year)
                        ->selectRaw('MONTH(sent_at) as month, COUNT(*) as count, SUM(actual_cost) as cost')
                        ->groupBy('month')
                        ->orderBy('month')
                        ->get()
                        ->keyBy('month');
    }

    /**
     * Process scheduled SMS messages.
     */
    public function processScheduled()
    {
        $scheduledMessages = SmsMessage::scheduled()
                                     ->where('scheduled_at', '<=', now())
                                     ->get();

        $processedCount = 0;

        foreach ($scheduledMessages as $message) {
            $this->sendSmsMessage($message);
            $processedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "Processed {$processedCount} scheduled SMS messages"
        ]);
    }

    /**
     * Cancel scheduled SMS.
     */
    public function cancel($id)
    {
        $smsMessage = SmsMessage::findOrFail($id);

        if (!$smsMessage->canBeCancelled()) {
            return response()->json([
                'success' => false,
                'message' => 'This SMS cannot be cancelled'
            ], 400);
        }

        $smsMessage->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'SMS cancelled successfully'
        ]);
    }

    /**
     * Get delivery report.
     */
    public function deliveryReport($id)
    {
        $smsMessage = SmsMessage::with('recipients')->findOrFail($id);

        if ($smsMessage->mnotify_message_id) {
            $report = $this->mnotifyService->getDeliveryReport($smsMessage->mnotify_message_id);
            
            if ($report['success']) {
                $smsMessage->update([
                    'delivery_report' => $report['data']
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'data' => [
                'sms_message' => $smsMessage,
                'recipients' => $smsMessage->recipients,
                'delivery_report' => $smsMessage->delivery_report
            ]
        ]);
    }
}
