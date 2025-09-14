<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\Member;
use App\Models\User;
use App\Services\PaystackService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class DonationController extends Controller
{
    protected $paystackService;

    public function __construct(PaystackService $paystackService)
    {
        $this->paystackService = $paystackService;
    }

    /**
     * Display a listing of donations.
     */
    public function index(Request $request)
    {
        $query = Donation::with(['member', 'receivedBy']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('donation_number', 'LIKE', "%{$search}%")
                  ->orWhere('donor_name', 'LIKE', "%{$search}%")
                  ->orWhere('donor_email', 'LIKE', "%{$search}%")
                  ->orWhere('purpose', 'LIKE', "%{$search}%")
                  ->orWhere('reference_number', 'LIKE', "%{$search}%")
                  ->orWhereHas('member', function ($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'LIKE', "%{$search}%")
                                  ->orWhere('last_name', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('donation_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->has('payment_method') && $request->payment_method !== '') {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->where('donation_date', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->where('donation_date', '<=', $request->end_date);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'donation_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $donations = $query->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'donations' => $donations->items(),
                'pagination' => [
                    'current_page' => $donations->currentPage(),
                    'last_page' => $donations->lastPage(),
                    'total' => $donations->total()
                ]
            ]);
        }

        return view('donations.index', compact('donations'));
    }


    /**
     * Store a newly created donation in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'nullable|exists:members,id',
            'donor_name' => 'required_without:member_id|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:0.01',
            'donation_type' => 'required|in:tithe,offering,special_offering,building_fund,missions,charity,other',
            'purpose' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,check,bank_transfer,online,mobile_money,card',
            'reference_number' => 'nullable|string|max:100',
            'donation_date' => 'required|date|before_or_equal:today',
            'received_by' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'is_anonymous' => 'boolean',
            'is_recurring' => 'boolean',
            'recurring_frequency' => 'nullable|in:weekly,monthly,quarterly,annually',
            'recurring_end_date' => 'nullable|date|after:donation_date',
            'tax_deductible' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $donation = Donation::create($request->all());

        // Generate receipt if needed
        if ($request->get('generate_receipt', false)) {
            $donation->generateReceiptNumber();
            $donation->sendReceipt();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'donation' => $donation->load(['member', 'receivedBy']),
                'message' => 'Donation recorded successfully!'
            ]);
        }

        return redirect()->route('donations.show', $donation)
                        ->with('success', 'Donation recorded successfully!');
    }

    /**
     * Display the specified donation.
     */
    public function show(Donation $donation)
    {
        $donation->load(['member', 'receivedBy']);

        return view('donations.show', compact('donation'));
    }

    /**
     * Show the form for editing the specified donation.
     */
    public function edit(Donation $donation)
    {
        $members = Member::active()->orderBy('first_name')->get();
        $users = User::where('role', 'admin')->orWhere('role', 'staff')->orderBy('name')->get();
        return view('donations.edit', compact('donation', 'members', 'users'));
    }

    /**
     * Update the specified donation in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'nullable|exists:members,id',
            'donor_name' => 'required_without:member_id|string|max:255',
            'donor_email' => 'nullable|email|max:255',
            'donor_phone' => 'nullable|string|max:20',
            'amount' => 'required|numeric|min:0.01',
            'donation_type' => 'required|in:tithe,offering,special_offering,building_fund,missions,charity,other',
            'purpose' => 'nullable|string|max:255',
            'payment_method' => 'required|in:cash,check,bank_transfer,online,mobile_money,card',
            'reference_number' => 'nullable|string|max:100',
            'donation_date' => 'required|date|before_or_equal:today',
            'received_by' => 'required|exists:users,id',
            'notes' => 'nullable|string',
            'is_anonymous' => 'boolean',
            'status' => 'required|in:pending,confirmed,cancelled,refunded',
            'tax_deductible' => 'boolean',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $donation->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'donation' => $donation->load(['member', 'receivedBy']),
                'message' => 'Donation updated successfully!'
            ]);
        }

        return redirect()->route('donations.show', $donation)
                        ->with('success', 'Donation updated successfully!');
    }

    /**
     * Remove the specified donation from storage.
     */
    public function destroy(Donation $donation)
    {
        // Only allow deletion of pending donations
        if ($donation->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Only pending donations can be deleted. Use cancel instead for confirmed donations.'
            ], 400);
        }

        $donation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Donation deleted successfully!'
        ]);
    }

    /**
     * Generate receipt for donation.
     */
    public function generateReceipt(Donation $donation)
    {
        if (!$donation->receipt_number) {
            $donation->generateReceiptNumber();
        }

        // This would typically generate a PDF receipt
        return response()->json([
            'success' => true,
            'message' => 'Receipt generated successfully!',
            'receipt_number' => $donation->receipt_number,
            'download_url' => route('donations.receipt.download', $donation)
        ]);
    }

    /**
     * Send receipt via email.
     */
    public function sendReceipt(Donation $donation)
    {
        if (!$donation->receipt_number) {
            $donation->generateReceiptNumber();
        }

        $result = $donation->sendReceipt();

        return response()->json([
            'success' => $result,
            'message' => $result ? 'Receipt sent successfully!' : 'Failed to send receipt.'
        ]);
    }

    /**
     * Get donation statistics.
     */
    public function statistics(Request $request)
    {
        $year = $request->get('year', now()->year);
        $month = $request->get('month');

        $query = Donation::confirmed();

        if ($year) {
            $query->whereYear('donation_date', $year);
        }

        if ($month) {
            $query->whereMonth('donation_date', $month);
        }

        $stats = [
            'total_donations' => $query->sum('amount'),
            'total_count' => $query->count(),
            'average_donation' => $query->avg('amount'),
            'donations_by_type' => $query->selectRaw('donation_type, SUM(amount) as total, COUNT(*) as count')
                                        ->groupBy('donation_type')
                                        ->get()
                                        ->keyBy('donation_type'),
            'donations_by_method' => $query->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
                                         ->groupBy('payment_method')
                                         ->get()
                                         ->keyBy('payment_method'),
            'monthly_totals' => Donation::confirmed()
                                       ->whereYear('donation_date', $year)
                                       ->selectRaw('MONTH(donation_date) as month, SUM(amount) as total')
                                       ->groupBy('month')
                                       ->orderBy('month')
                                       ->pluck('total', 'month'),
        ];

        return response()->json($stats);
    }

    /**
     * Export donations data.
     */
    public function export(Request $request)
    {
        // Placeholder for export functionality
        return response()->json([
            'message' => 'Export functionality will be implemented with Laravel Excel package'
        ]);
    }

    /**
     * Get top donors.
     */
    public function topDonors(Request $request)
    {
        $limit = $request->get('limit', 10);
        $year = $request->get('year', now()->year);

        $topDonors = Donation::confirmed()
                           ->whereYear('donation_date', $year)
                           ->where('is_anonymous', false)
                           ->with('member')
                           ->selectRaw('member_id, donor_name, SUM(amount) as total_donated, COUNT(*) as donation_count')
                           ->groupBy('member_id', 'donor_name')
                           ->orderBy('total_donated', 'desc')
                           ->limit($limit)
                           ->get();

        return response()->json($topDonors);
    }

    /**
     * Show the donation form.
     */
    public function create()
    {
        $members = Member::select('id', 'first_name', 'last_name', 'member_id')
                        ->orderBy('first_name')
                        ->get()
                        ->map(function ($member) {
                            $member->full_name = $member->first_name . ' ' . $member->last_name;
                            return $member;
                        });

        return view('donations.create', compact('members'));
    }

    /**
     * Initialize Paystack payment.
     */
    public function initialize(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|min:1',
            'donation_type' => 'required|string',
            'is_member' => 'required|in:yes,no',
            'member_id' => 'required_if:is_member,yes|exists:members,id',
            'donor_name' => 'required_if:is_member,no|string|max:255',
            'donor_email' => 'required_if:is_member,no|email',
            'donor_phone' => 'nullable|string|max:20',
            'purpose' => 'nullable|string|max:255',
            'payment_method' => 'required|in:card,mobile_money,bank',
            'is_anonymous' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get donor information
            $donorInfo = $this->getDonorInfo($request);

            // Create donation record
            $donation = Donation::create([
                'member_id' => $request->is_member === 'yes' ? $request->member_id : null,
                'donor_name' => $donorInfo['name'],
                'donor_email' => $donorInfo['email'],
                'donor_phone' => $donorInfo['phone'],
                'amount' => $request->amount,
                'donation_type' => $request->donation_type,
                'purpose' => $request->purpose,
                'payment_method' => 'paystack',
                'payment_channel' => $request->payment_method,
                'is_anonymous' => $request->boolean('is_anonymous'),
                'donation_date' => now(),
                'status' => 'pending',
                'paystack_reference' => $this->paystackService->generateReference(),
            ]);

            // Initialize Paystack transaction
            $paystackData = [
                'email' => $donorInfo['email'],
                'amount' => $request->amount,
                'reference' => $donation->paystack_reference,
                'callback_url' => route('donations.verify'),
                'metadata' => [
                    'donation_id' => $donation->id,
                    'donor_name' => $donorInfo['name'],
                    'donation_type' => $request->donation_type,
                    'purpose' => $request->purpose,
                ]
            ];

            $response = $this->paystackService->initializeTransaction($paystackData);

            if ($response['status']) {
                // Update donation with Paystack access code
                $donation->update([
                    'paystack_access_code' => $response['data']['access_code']
                ]);

                DB::commit();

                return response()->json([
                    'status' => true,
                    'message' => 'Payment initialized successfully',
                    'reference' => $donation->paystack_reference,
                    'email' => $donorInfo['email'],
                    'amount' => $request->amount,
                    'authorization_url' => $response['data']['authorization_url']
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
            Log::error('Donation initialization failed: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing your donation. Please try again.'
            ], 500);
        }
    }

    /**
     * Verify Paystack payment.
     */
    public function verify(Request $request)
    {
        $reference = $request->query('reference');
        
        if (!$reference) {
            return redirect()->route('donations.create')->with('error', 'Invalid payment reference.');
        }

        try {
            // Find donation by reference
            $donation = Donation::where('paystack_reference', $reference)->first();
            
            if (!$donation) {
                return redirect()->route('donations.create')->with('error', 'Donation not found.');
            }

            // Verify transaction with Paystack
            $verification = $this->paystackService->verifyTransaction($reference);

            if ($verification['status'] && $verification['data']['status'] === 'success') {
                $transactionData = $verification['data'];
                
                // Calculate fees
                $transactionFee = $transactionData['fees'] / 100; // Convert from kobo
                $netAmount = ($transactionData['amount'] / 100) - $transactionFee;

                // Update donation
                $donation->update([
                    'status' => 'confirmed',
                    'paystack_transaction_id' => $transactionData['id'],
                    'payment_gateway_response' => json_encode($transactionData),
                    'transaction_fee' => $transactionFee,
                    'net_amount' => $netAmount,
                    'confirmed_at' => now(),
                ]);

                // Log successful donation
                Log::info('Donation confirmed', [
                    'donation_id' => $donation->id,
                    'reference' => $reference,
                    'amount' => $donation->amount
                ]);

                return redirect()->route('donations.success', $donation->id)
                               ->with('success', 'Thank you for your generous donation!');
            } else {
                // Payment failed
                $donation->update([
                    'status' => 'failed',
                    'payment_gateway_response' => json_encode($verification)
                ]);

                return redirect()->route('donations.create')
                               ->with('error', 'Payment verification failed. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            
            return redirect()->route('donations.create')
                           ->with('error', 'An error occurred while verifying your payment.');
        }
    }

    /**
     * Show donation success page.
     */
    public function success($donationId)
    {
        $donation = Donation::with('member')->findOrFail($donationId);
        
        // Ensure the donation belongs to the current session or is confirmed
        if ($donation->status !== 'confirmed') {
            return redirect()->route('donations.create')->with('error', 'Invalid donation.');
        }

        return view('donations.success', compact('donation'));
    }

    /**
     * Get donor information based on member selection.
     */
    private function getDonorInfo(Request $request)
    {
        if ($request->is_member === 'yes' && $request->member_id) {
            $member = Member::findOrFail($request->member_id);
            return [
                'name' => $member->first_name . ' ' . $member->last_name,
                'email' => $member->email,
                'phone' => $member->phone
            ];
        } else {
            return [
                'name' => $request->donor_name,
                'email' => $request->donor_email,
                'phone' => $request->donor_phone
            ];
        }
    }

    /**
     * Process recurring donations.
     */
    public function processRecurring()
    {
        $processedCount = Donation::processRecurringDonations();

        return response()->json([
            'success' => true,
            'message' => "Processed {$processedCount} recurring donations."
        ]);
    }

    /**
     * Download receipt PDF.
     */
    public function downloadReceipt(Donation $donation)
    {
        if (!$donation->receipt_number) {
            $donation->generateReceiptNumber();
        }

        // For now, return a simple receipt view that can be printed
        // In the future, this could generate a proper PDF using packages like DomPDF or Snappy
        return view('donations.receipt', compact('donation'));
    }
}
