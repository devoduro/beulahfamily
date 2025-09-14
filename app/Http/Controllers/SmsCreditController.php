<?php

namespace App\Http\Controllers;

use App\Models\SmsCredit;
use App\Models\SmsCreditTransaction;
use App\Models\SmsPricing;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SmsCreditController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Display SMS credit dashboard.
     */
    public function index()
    {
        $userCredit = SmsCredit::getOrCreateForUser(Auth::id());
        $pricingPlans = SmsPricing::active()->ordered()->get();
        $recentTransactions = SmsCreditTransaction::where('user_id', Auth::id())
            ->latest()
            ->limit(10)
            ->get();

        return view('sms.credits.index', compact('userCredit', 'pricingPlans', 'recentTransactions'));
    }

    /**
     * Show credit purchase form.
     */
    public function purchase()
    {
        $pricingPlans = SmsPricing::active()->ordered()->get();
        return view('sms.credits.purchase', compact('pricingPlans'));
    }

    /**
     * Initialize credit purchase with Paystack.
     */
    public function initializePurchase(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pricing_id' => 'required|exists:sms_pricing,id',
            'payment_method' => 'required|in:card,bank,mobile_money'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid request data.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            $pricing = SmsPricing::findOrFail($request->pricing_id);
            $user = Auth::user();
            $reference = 'SMS_' . time() . '_' . $user->id;

            // Create pending transaction
            $transaction = SmsCreditTransaction::create([
                'user_id' => $user->id,
                'type' => 'purchase',
                'credits' => $pricing->total_credits,
                'amount' => $pricing->price,
                'description' => "Purchase of {$pricing->credits} SMS credits ({$pricing->name})",
                'paystack_reference' => $reference,
                'status' => 'pending',
                'metadata' => [
                    'pricing_plan' => $pricing->toArray(),
                    'payment_method' => $request->payment_method
                ]
            ]);

            // Initialize Paystack payment
            $paymentData = [
                'email' => $user->email,
                'amount' => $pricing->price,
                'reference' => $reference,
                'callback_url' => route('sms.credits.verify'),
                'metadata' => [
                    'user_id' => $user->id,
                    'transaction_id' => $transaction->id,
                    'credits' => $pricing->total_credits,
                    'pricing_plan' => $pricing->name
                ]
            ];

            $response = $this->paystackService->initializeTransaction($paymentData);

            if ($response['status']) {
                DB::commit();
                return response()->json([
                    'status' => true,
                    'message' => 'Payment initialized successfully',
                    'data' => [
                        'authorization_url' => $response['data']['authorization_url'],
                        'access_code' => $response['data']['access_code'],
                        'reference' => $reference
                    ]
                ]);
            } else {
                DB::rollBack();
                return response()->json([
                    'status' => false,
                    'message' => 'Failed to initialize payment: ' . $response['message']
                ], 400);
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('SMS Credit purchase initialization failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your request. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify Paystack payment and credit account.
     */
    public function verifyPurchase(Request $request)
    {
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('sms.credits.index')->with('error', 'Invalid payment reference.');
        }

        try {
            // Find transaction by reference
            $transaction = SmsCreditTransaction::where('paystack_reference', $reference)->first();
            
            if (!$transaction) {
                return redirect()->route('sms.credits.index')->with('error', 'Transaction not found.');
            }

            // Verify transaction with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if ($verification['status'] && $verification['data']['status'] === 'success') {
                DB::beginTransaction();

                // Update transaction
                $transaction->update([
                    'status' => 'completed',
                    'paystack_transaction_id' => $verification['data']['id'],
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'paystack_response' => $verification['data']
                    ])
                ]);

                // Credit user account
                $userCredit = SmsCredit::getOrCreateForUser($transaction->user_id);
                $userCredit->addCredits(
                    $transaction->credits,
                    "SMS credits purchased - {$transaction->description}"
                );

                DB::commit();

                return redirect()->route('sms.credits.success', $transaction)
                               ->with('success', 'Payment successful! Your SMS credits have been added.');

            } else {
                // Payment failed
                $transaction->update([
                    'status' => 'failed',
                    'metadata' => array_merge($transaction->metadata ?? [], [
                        'paystack_response' => $verification
                    ])
                ]);

                return redirect()->route('sms.credits.index')
                               ->with('error', 'Payment verification failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('SMS Credit payment verification failed: ' . $e->getMessage());
            
            return redirect()->route('sms.credits.index')
                           ->with('error', 'An error occurred while verifying your payment.');
        }
    }

    /**
     * Show purchase success page.
     */
    public function success(SmsCreditTransaction $transaction)
    {
        if ($transaction->user_id !== Auth::id() || $transaction->status !== 'completed') {
            return redirect()->route('sms.credits.index')->with('error', 'Invalid transaction.');
        }

        $userCredit = SmsCredit::getOrCreateForUser(Auth::id());
        return view('sms.credits.success', compact('transaction', 'userCredit'));
    }

    /**
     * Display transaction history.
     */
    public function transactions(Request $request)
    {
        $userCredit = SmsCredit::getOrCreateForUser(Auth::id());
        $query = SmsCreditTransaction::where('user_id', Auth::id());

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);

        // Calculate statistics
        $allTransactions = SmsCreditTransaction::where('user_id', Auth::id())->where('status', 'completed');
        $totalPurchased = $allTransactions->where('type', 'purchase')->sum('credits');
        $totalUsed = $allTransactions->where('type', 'usage')->sum('credits');
        $totalSpent = $allTransactions->where('type', 'purchase')->sum('amount');

        return view('sms.credits.transactions', compact('transactions', 'userCredit', 'totalPurchased', 'totalUsed', 'totalSpent'));
    }

    /**
     * Get current user's credit balance.
     */
    public function balance()
    {
        $userCredit = SmsCredit::getOrCreateForUser(Auth::id());
        
        return response()->json([
            'credits' => $userCredit->credits,
            'balance' => $userCredit->balance
        ]);
    }

    /**
     * Admin: Manually add credits to user.
     */
    public function addCredits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'credits' => 'required|integer|min:1',
            'description' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $userCredit = SmsCredit::getOrCreateForUser($request->user_id);
            $userCredit->addCredits($request->credits, $request->description);

            return response()->json([
                'success' => true,
                'message' => 'Credits added successfully!',
                'new_balance' => $userCredit->credits
            ]);

        } catch (\Exception $e) {
            Log::error('Manual credit addition failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to add credits. Please try again.'
            ], 500);
        }
    }
}
