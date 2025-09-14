<?php

namespace App\Http\Controllers;

use App\Models\FinanceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinanceCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = FinanceCategory::ordered()->get();
        return view('finance.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('finance.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:finance_categories',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
            'icon' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        $category = FinanceCategory::create([
            'name' => $request->name,
            'type' => $request->type,
            'color' => $request->color,
            'icon' => $request->icon,
            'description' => $request->description,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        // If this is an AJAX request (from modal), return JSON response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Category created successfully.',
                'id' => $category->id,
                'name' => $category->name,
                'type' => $category->type,
                'color' => $category->color,
                'icon' => $category->icon
            ]);
        }

        return redirect()->route('finance.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(FinanceCategory $category)
    {
        return view('finance.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FinanceCategory $category)
    {
        return view('finance.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FinanceCategory $category)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:finance_categories,name,' . $category->id,
            'description' => 'nullable|string',
            'type' => 'required|in:income,expense',
            'color' => 'required|string|max:7',
            'icon' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'color' => $request->color,
            'icon' => $request->icon,
            'sort_order' => $request->sort_order ?? 0,
            'active' => $request->has('active')
        ]);

        return redirect()->route('finance.categories.index')
            ->with('success', 'Finance category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FinanceCategory $category)
    {
        // Check if category has transactions
        if ($category->transactions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with existing transactions.');
        }

        $category->delete();

        return redirect()->route('finance.categories.index')
            ->with('success', 'Finance category deleted successfully.');
    }

    /**
     * Toggle category status
     */
    public function toggle(FinanceCategory $category)
    {
        $category->update(['active' => !$category->active]);

        return redirect()->back()
            ->with('success', 'Category status updated successfully.');
    }
}
