<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Testimony;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    /**
     * Display a listing of testimonies
     */
    public function index(Request $request)
    {
        $query = Testimony::with('member')->published()->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $testimonies = $query->paginate(12)->withQueryString();

        // Get categories for filter
        $categories = [
            'healing' => 'Healing & Health',
            'financial' => 'Financial Breakthrough',
            'spiritual' => 'Spiritual Growth',
            'family' => 'Family & Relationships',
            'career' => 'Career & Education',
            'protection' => 'Divine Protection',
            'other' => 'Other Blessings'
        ];

        return view('member.testimonies.index', compact('testimonies', 'categories'));
    }

    /**
     * Show the form for creating a new testimony
     */
    public function create()
    {
        $categories = [
            'healing' => 'Healing & Health',
            'financial' => 'Financial Breakthrough',
            'spiritual' => 'Spiritual Growth',
            'family' => 'Family & Relationships',
            'career' => 'Career & Education',
            'protection' => 'Divine Protection',
            'other' => 'Other Blessings'
        ];

        return view('member.testimonies.create', compact('categories'));
    }

    /**
     * Store a newly created testimony
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:50',
            'category' => 'required|in:healing,financial,spiritual,family,career,protection,other',
            'is_public' => 'boolean',
            'tags' => 'nullable|string'
        ]);

        // Process tags
        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
        }

        $testimony = Testimony::create([
            'member_id' => Auth::guard('member')->id(),
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_public' => $request->boolean('is_public', true),
            'tags' => $tags,
            'is_approved' => false // Requires admin approval
        ]);

        return redirect()->route('member.testimonies.show', $testimony)
                        ->with('success', 'Your testimony has been submitted and is pending approval. Thank you for sharing!');
    }

    /**
     * Display the specified testimony
     */
    public function show(Testimony $testimony)
    {
        // Check if user can view this testimony
        if (!$testimony->is_public && $testimony->member_id !== Auth::guard('member')->id()) {
            abort(404);
        }

        $testimony->load('member');

        // Get related testimonies
        $relatedTestimonies = Testimony::published()
            ->where('category', $testimony->category)
            ->where('id', '!=', $testimony->id)
            ->limit(3)
            ->get();

        return view('member.testimonies.show', compact('testimony', 'relatedTestimonies'));
    }

    /**
     * Show my testimonies
     */
    public function myTestimonies()
    {
        $testimonies = Testimony::where('member_id', Auth::guard('member')->id())
            ->latest()
            ->paginate(10);

        return view('member.testimonies.my-testimonies', compact('testimonies'));
    }

    /**
     * Show the form for editing the specified testimony
     */
    public function edit(Testimony $testimony)
    {
        // Check if user owns this testimony
        if ($testimony->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        $categories = [
            'healing' => 'Healing & Health',
            'financial' => 'Financial Breakthrough',
            'spiritual' => 'Spiritual Growth',
            'family' => 'Family & Relationships',
            'career' => 'Career & Education',
            'protection' => 'Divine Protection',
            'other' => 'Other Blessings'
        ];

        return view('member.testimonies.edit', compact('testimony', 'categories'));
    }

    /**
     * Update the specified testimony
     */
    public function update(Request $request, Testimony $testimony)
    {
        // Check if user owns this testimony
        if ($testimony->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:50',
            'category' => 'required|in:healing,financial,spiritual,family,career,protection,other',
            'is_public' => 'boolean',
            'tags' => 'nullable|string'
        ]);

        // Process tags
        $tags = null;
        if ($request->filled('tags')) {
            $tags = array_map('trim', explode(',', $request->tags));
        }

        $testimony->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_public' => $request->boolean('is_public', true),
            'tags' => $tags,
            'is_approved' => false // Requires re-approval after edit
        ]);

        return redirect()->route('member.testimonies.show', $testimony)
                        ->with('success', 'Your testimony has been updated and is pending re-approval.');
    }

    /**
     * Remove the specified testimony
     */
    public function destroy(Testimony $testimony)
    {
        // Check if user owns this testimony
        if ($testimony->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        $testimony->delete();

        return redirect()->route('member.testimonies.my-testimonies')
                        ->with('success', 'Testimony deleted successfully.');
    }
}
