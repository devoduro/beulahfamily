<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrayerRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:member');
    }

    /**
     * Display a listing of prayer requests
     */
    public function index(Request $request)
    {
        $query = PrayerRequest::with('member')->public()->active()->latest();

        // Filter by category
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Filter by urgency
        if ($request->filled('urgency')) {
            $query->byUrgency($request->urgency);
        }

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $prayerRequests = $query->paginate(12)->withQueryString();

        // Get filter options
        $categories = [
            'health' => 'Health & Healing',
            'family' => 'Family & Relationships',
            'financial' => 'Financial Needs',
            'spiritual' => 'Spiritual Growth',
            'career' => 'Career & Education',
            'relationship' => 'Relationships',
            'protection' => 'Protection & Safety',
            'guidance' => 'Guidance & Direction',
            'other' => 'Other Requests'
        ];

        $urgencyLevels = [
            'low' => 'Low Priority',
            'medium' => 'Medium Priority',
            'high' => 'High Priority',
            'urgent' => 'Urgent'
        ];

        return view('member.prayer-requests.index', compact('prayerRequests', 'categories', 'urgencyLevels'));
    }

    /**
     * Show the form for creating a new prayer request
     */
    public function create()
    {
        $categories = [
            'health' => 'Health & Healing',
            'family' => 'Family & Relationships',
            'financial' => 'Financial Needs',
            'spiritual' => 'Spiritual Growth',
            'career' => 'Career & Education',
            'relationship' => 'Relationships',
            'protection' => 'Protection & Safety',
            'guidance' => 'Guidance & Direction',
            'other' => 'Other Requests'
        ];

        $urgencyLevels = [
            'low' => 'Low Priority',
            'medium' => 'Medium Priority',
            'high' => 'High Priority',
            'urgent' => 'Urgent'
        ];

        return view('member.prayer-requests.create', compact('categories', 'urgencyLevels'));
    }

    /**
     * Store a newly created prayer request
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|in:health,family,financial,spiritual,career,relationship,protection,guidance,other',
            'urgency' => 'required|in:low,medium,high,urgent',
            'is_anonymous' => 'boolean',
            'is_private' => 'boolean',
            'expires_at' => 'nullable|date|after:today'
        ]);

        $prayerRequest = PrayerRequest::create([
            'member_id' => Auth::guard('member')->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'urgency' => $validated['urgency'],
            'is_anonymous' => $request->boolean('is_anonymous', false),
            'is_private' => $request->boolean('is_private', false),
            'expires_at' => $validated['expires_at'] ?? null,
            'status' => 'active'
        ]);

        return redirect()->route('member.prayer-requests.show', $prayerRequest)
                        ->with('success', 'Your prayer request has been submitted. The Beulah family will pray for you!');
    }

    /**
     * Display the specified prayer request
     */
    public function show(PrayerRequest $prayerRequest)
    {
        // Check if user can view this prayer request
        if ($prayerRequest->is_private && $prayerRequest->member_id !== Auth::guard('member')->id()) {
            abort(404);
        }

        $prayerRequest->load('member');

        // Check if current member has prayed
        $hasPrayed = $prayerRequest->hasPrayed(Auth::guard('member')->id());

        // Get related prayer requests
        $relatedRequests = PrayerRequest::public()
            ->active()
            ->where('category', $prayerRequest->category)
            ->where('id', '!=', $prayerRequest->id)
            ->limit(3)
            ->get();

        return view('member.prayer-requests.show', compact('prayerRequest', 'hasPrayed', 'relatedRequests'));
    }

    /**
     * Show my prayer requests
     */
    public function myRequests()
    {
        $prayerRequests = PrayerRequest::where('member_id', Auth::guard('member')->id())
            ->latest()
            ->paginate(10);

        return view('member.prayer-requests.my-requests', compact('prayerRequests'));
    }

    /**
     * Add prayer for a request
     */
    public function pray(PrayerRequest $prayerRequest)
    {
        $memberId = Auth::guard('member')->id();
        
        if (!$prayerRequest->hasPrayed($memberId)) {
            $prayerRequest->addPrayer($memberId);
            
            return response()->json([
                'success' => true,
                'message' => 'Thank you for praying!',
                'total_prayers' => $prayerRequest->fresh()->total_prayers
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'You have already prayed for this request.'
        ]);
    }

    /**
     * Mark prayer request as answered
     */
    public function markAnswered(Request $request, PrayerRequest $prayerRequest)
    {
        // Check if user owns this prayer request
        if ($prayerRequest->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'answer_testimony' => 'required|string|min:20'
        ]);

        $prayerRequest->markAsAnswered($validated['answer_testimony']);

        return redirect()->route('member.prayer-requests.show', $prayerRequest)
                        ->with('success', 'Praise God! Your prayer has been marked as answered.');
    }

    /**
     * Show the form for editing the specified prayer request
     */
    public function edit(PrayerRequest $prayerRequest)
    {
        // Check if user owns this prayer request
        if ($prayerRequest->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        // Can't edit answered requests
        if ($prayerRequest->status === 'answered') {
            return redirect()->route('member.prayer-requests.show', $prayerRequest)
                           ->with('error', 'Cannot edit answered prayer requests.');
        }

        $categories = [
            'health' => 'Health & Healing',
            'family' => 'Family & Relationships',
            'financial' => 'Financial Needs',
            'spiritual' => 'Spiritual Growth',
            'career' => 'Career & Education',
            'relationship' => 'Relationships',
            'protection' => 'Protection & Safety',
            'guidance' => 'Guidance & Direction',
            'other' => 'Other Requests'
        ];

        $urgencyLevels = [
            'low' => 'Low Priority',
            'medium' => 'Medium Priority',
            'high' => 'High Priority',
            'urgent' => 'Urgent'
        ];

        return view('member.prayer-requests.edit', compact('prayerRequest', 'categories', 'urgencyLevels'));
    }

    /**
     * Update the specified prayer request
     */
    public function update(Request $request, PrayerRequest $prayerRequest)
    {
        // Check if user owns this prayer request
        if ($prayerRequest->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        // Can't edit answered requests
        if ($prayerRequest->status === 'answered') {
            return redirect()->route('member.prayer-requests.show', $prayerRequest)
                           ->with('error', 'Cannot edit answered prayer requests.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:20',
            'category' => 'required|in:health,family,financial,spiritual,career,relationship,protection,guidance,other',
            'urgency' => 'required|in:low,medium,high,urgent',
            'is_anonymous' => 'boolean',
            'is_private' => 'boolean',
            'expires_at' => 'nullable|date|after:today'
        ]);

        $prayerRequest->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'urgency' => $validated['urgency'],
            'is_anonymous' => $request->boolean('is_anonymous', false),
            'is_private' => $request->boolean('is_private', false),
            'expires_at' => $validated['expires_at'] ?? null
        ]);

        return redirect()->route('member.prayer-requests.show', $prayerRequest)
                        ->with('success', 'Prayer request updated successfully.');
    }

    /**
     * Remove the specified prayer request
     */
    public function destroy(PrayerRequest $prayerRequest)
    {
        // Check if user owns this prayer request
        if ($prayerRequest->member_id !== Auth::guard('member')->id()) {
            abort(403);
        }

        $prayerRequest->delete();

        return redirect()->route('member.prayer-requests.my-requests')
                        ->with('success', 'Prayer request deleted successfully.');
    }
}
