<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\MemberPayment;
use App\Services\MNotifyService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MemberPaymentController extends Controller
{
    protected $mnotifyService;

    public function __construct(MNotifyService $mnotifyService)
    {
        $this->middleware('auth');
        $this->mnotifyService = $mnotifyService;
    }

    /**
     * Display member payments dashboard
     */
    public function index(Request $request)
    {
        $query = MemberPayment::with(['member', 'recordedBy']);

        // Filters
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('member', function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }

        $payments = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'total_payments' => MemberPayment::confirmed()->count(),
            'payments_this_month' => MemberPayment::confirmed()->thisMonth()->count(),
            'total_amount' => MemberPayment::confirmed()->sum('amount'),
            'amount_this_month' => MemberPayment::confirmed()->thisMonth()->sum('amount'),
        ];

        $paymentTypes = MemberPayment::getPaymentTypes();

        return view('member-payments.index', compact('payments', 'stats', 'paymentTypes'));
    }

    /**
     * Show create payment form
     */
    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        $paymentTypes = MemberPayment::getPaymentTypes();
        
        return view('member-payments.create', compact('members', 'paymentTypes'));
    }

    /**
     * Store new payment
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'payment_type' => 'required|in:tithe,offering,welfare,building_fund,special_offering,thanksgiving,other',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque,card,online',
            'receipt_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $payment = MemberPayment::create([
                'member_id' => $request->member_id,
                'recorded_by' => Auth::id(),
                'payment_reference' => MemberPayment::generateReference(),
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'receipt_number' => $request->filled('receipt_number') ? $request->receipt_number : MemberPayment::generateReceiptNumber(),
                'invoice_number' => MemberPayment::generateInvoiceNumber(),
                'description' => $request->description,
                'notes' => $request->notes,
                'status' => 'confirmed'
            ]);

            // Send SMS notification
            $this->sendPaymentSms($payment);

            DB::commit();

            return redirect()->route('member-payments.index')
                ->with('success', 'Payment recorded successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Member payment creation failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to record payment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show payment details
     */
    public function show(MemberPayment $payment)
    {
        $payment->load(['member', 'recordedBy']);
        return view('member-payments.show', compact('payment'));
    }

    /**
     * Show edit payment form
     */
    public function edit(MemberPayment $payment)
    {
        $members = Member::active()->orderBy('first_name')->get();
        $paymentTypes = MemberPayment::getPaymentTypes();
        
        return view('member-payments.edit', compact('payment', 'members', 'paymentTypes'));
    }

    /**
     * Update payment
     */
    public function update(Request $request, MemberPayment $payment)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'payment_type' => 'required|in:tithe,offering,welfare,building_fund,special_offering,thanksgiving,other',
            'amount' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque,card,online',
            'receipt_number' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $payment->update([
                'member_id' => $request->member_id,
                'payment_type' => $request->payment_type,
                'amount' => $request->amount,
                'payment_date' => $request->payment_date,
                'payment_method' => $request->payment_method,
                'receipt_number' => $request->receipt_number,
                'description' => $request->description,
                'notes' => $request->notes,
            ]);

            return redirect()->route('member-payments.show', $payment)
                ->with('success', 'Payment updated successfully.');

        } catch (\Exception $e) {
            Log::error('Member payment update failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Failed to update payment. Please try again.')
                ->withInput();
        }
    }

    /**
     * Delete payment
     */
    public function destroy(MemberPayment $payment)
    {
        try {
            $payment->delete();
            return redirect()->route('member-payments.index')
                ->with('success', 'Payment deleted successfully.');
        } catch (\Exception $e) {
            Log::error('Member payment deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to delete payment.');
        }
    }

    /**
     * Send SMS notification for payment
     */
    private function sendPaymentSms(MemberPayment $payment)
    {
        try {
            $member = $payment->member;
            
            if (!$member->phone) {
                return;
            }

            $message = "Dear {$member->first_name}, your {$payment->payment_type_display} payment of GHS " . 
                      number_format($payment->amount, 2) . " has been received. " .
                      "Reference: {$payment->payment_reference}. Thank you! - Beulah Family";

            $result = $this->mnotifyService->sendSms($member->phone, $message);

            if ($result['success']) {
                $payment->update([
                    'sms_sent' => true,
                    'sms_sent_at' => now()
                ]);
                
                Log::info("Payment SMS sent successfully to {$member->phone} for payment {$payment->id}");
            } else {
                Log::error("Failed to send payment SMS: " . $result['message']);
            }

        } catch (\Exception $e) {
            Log::error('Payment SMS sending failed: ' . $e->getMessage());
        }
    }

    /**
     * Resend SMS notification
     */
    public function resendSms(MemberPayment $payment)
    {
        $this->sendPaymentSms($payment);
        
        return redirect()->back()->with('success', 'SMS notification sent.');
    }

    /**
     * Export payments to CSV
     */
    public function export(Request $request)
    {
        $query = MemberPayment::with(['member', 'recordedBy']);

        // Apply same filters as index
        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        $payments = $query->latest()->get();

        $filename = 'member-payments-' . date('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Payment Reference',
                'Member ID',
                'Member Name',
                'Payment Type',
                'Amount',
                'Payment Date',
                'Payment Method',
                'Receipt Number',
                'Status',
                'SMS Sent',
                'Recorded By',
                'Created At'
            ]);

            // CSV data
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->payment_reference,
                    $payment->member->member_id,
                    $payment->member->full_name,
                    $payment->payment_type_display,
                    $payment->amount,
                    $payment->payment_date->format('Y-m-d'),
                    ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    $payment->receipt_number,
                    ucfirst($payment->status),
                    $payment->sms_sent ? 'Yes' : 'No',
                    $payment->recordedBy->name,
                    $payment->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
