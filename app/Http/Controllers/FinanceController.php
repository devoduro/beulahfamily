<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display finance dashboard
     */
    public function index()
    {
        $stats = [
            'total_income_this_month' => FinanceTransaction::income()->approved()->thisMonth()->sum('amount'),
            'total_expense_this_month' => FinanceTransaction::expense()->approved()->thisMonth()->sum('amount'),
            'total_income_this_year' => FinanceTransaction::income()->approved()->thisYear()->sum('amount'),
            'total_expense_this_year' => FinanceTransaction::expense()->approved()->thisYear()->sum('amount'),
            'pending_transactions' => FinanceTransaction::pending()->count(),
            'total_transactions' => FinanceTransaction::count(),
        ];

        $stats['net_income_this_month'] = $stats['total_income_this_month'] - $stats['total_expense_this_month'];
        $stats['net_income_this_year'] = $stats['total_income_this_year'] - $stats['total_expense_this_year'];

        $recentTransactions = FinanceTransaction::with(['category', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $monthlyData = $this->getMonthlyFinanceData();

        return view('finance.index', compact('stats', 'recentTransactions', 'monthlyData'));
    }

    /**
     * Display transactions list
     */
    public function transactions(Request $request)
    {
        $query = FinanceTransaction::with(['category', 'user', 'approver']);

        // Filters
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('transaction_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('transaction_date', '<=', $request->date_to);
        }

        $transactions = $query->latest()->paginate(20);
        $categories = FinanceCategory::active()->ordered()->get();

        return view('finance.transactions', compact('transactions', 'categories'));
    }

    /**
     * Show create transaction form
     */
    public function create()
    {
        $categories = FinanceCategory::active()->ordered()->get();
        return view('finance.create', compact('categories'));
    }

    /**
     * Store new transaction
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:finance_categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'transaction_date' => 'required|date',
            'payment_method' => 'required|in:cash,bank_transfer,mobile_money,cheque,card,other',
            'receipt_number' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $category = FinanceCategory::findOrFail($request->category_id);

            $transaction = FinanceTransaction::create([
                'category_id' => $request->category_id,
                'user_id' => Auth::id(),
                'reference_number' => FinanceTransaction::generateReference(),
                'title' => $request->title,
                'description' => $request->description,
                'amount' => $request->amount,
                'type' => $category->type,
                'transaction_date' => $request->transaction_date,
                'payment_method' => $request->payment_method,
                'receipt_number' => $request->receipt_number,
                'notes' => $request->notes,
                'status' => 'pending'
            ]);

            DB::commit();

            return redirect()->route('finance.transactions')
                ->with('success', 'Transaction recorded successfully and is pending approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to record transaction. Please try again.')
                ->withInput();
        }
    }

    /**
     * Show transaction details
     */
    public function show(FinanceTransaction $transaction)
    {
        $transaction->load(['category', 'user', 'approver']);
        return view('finance.show', compact('transaction'));
    }

    /**
     * Approve transaction
     */
    public function approve(FinanceTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaction is not pending approval.');
        }

        $transaction->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Transaction approved successfully.');
    }

    /**
     * Reject transaction
     */
    public function reject(FinanceTransaction $transaction)
    {
        if ($transaction->status !== 'pending') {
            return redirect()->back()->with('error', 'Transaction is not pending approval.');
        }

        $transaction->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now()
        ]);

        return redirect()->back()->with('success', 'Transaction rejected.');
    }

    /**
     * Get monthly finance data for charts
     */
    private function getMonthlyFinanceData()
    {
        $monthlyIncome = FinanceTransaction::selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->income()
            ->approved()
            ->whereYear('transaction_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        $monthlyExpense = FinanceTransaction::selectRaw('MONTH(transaction_date) as month, SUM(amount) as total')
            ->expense()
            ->approved()
            ->whereYear('transaction_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyIncome[$i])) {
                $monthlyIncome[$i] = 0;
            }
            if (!isset($monthlyExpense[$i])) {
                $monthlyExpense[$i] = 0;
            }
        }

        ksort($monthlyIncome);
        ksort($monthlyExpense);

        return [
            'income' => array_values($monthlyIncome),
            'expense' => array_values($monthlyExpense)
        ];
    }
}
