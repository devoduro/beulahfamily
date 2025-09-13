<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::with(['family', 'activeMinistries']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('member_id', 'like', "%{$search}%");
            });
        }
        
        // Filter by membership status
        if ($request->filled('status')) {
            $query->byMembershipStatus($request->status);
        }
        
        // Filter by membership type
        if ($request->filled('type')) {
            $query->byMembershipType($request->type);
        }
        
        // Filter by gender
        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }
        
        // Filter by age range
        if ($request->filled('min_age') && $request->filled('max_age')) {
            $query->byAgeRange($request->min_age, $request->max_age);
        }
        
        // Filter by family
        if ($request->filled('family_id')) {
            $query->where('family_id', $request->family_id);
        }
        
        // Filter by ministry
        if ($request->filled('ministry_id')) {
            $query->whereHas('ministries', function ($q) use ($request) {
                $q->where('ministry_id', $request->ministry_id)
                  ->where('member_ministry.is_active', true);
            });
        }
        
        // Only show active members by default
        if (!$request->filled('show_inactive')) {
            $query->active();
        }
        
        $members = $query->orderBy('first_name')
                        ->orderBy('last_name')
                        ->paginate(20);
        
        $families = Family::active()->orderBy('family_name')->get();
        $ministries = Ministry::active()->orderBy('name')->get();
        
        return view('members.index', compact('members', 'families', 'ministries'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $families = Family::active()->orderBy('family_name')->get();
        $ministries = Ministry::active()->orderBy('name')->get();
        
        return view('members.create', compact('families', 'ministries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:50',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'membership_date' => 'nullable|date',
            'membership_status' => 'required|in:active,inactive,transferred,deceased',
            'membership_type' => 'required|in:member,visitor,friend,associate',
            'family_id' => 'nullable|exists:families,id',
            'relationship_to_head' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_baptized' => 'boolean',
            'baptism_date' => 'nullable|date',
            'is_confirmed' => 'boolean',
            'confirmation_date' => 'nullable|date',
            'skills_talents' => 'nullable|array',
            'interests' => 'nullable|array',
            'receive_newsletter' => 'boolean',
            'receive_sms' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ministries' => 'nullable|array',
            'ministries.*' => 'exists:ministries,id'
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('members/photos', 'public');
        }
        
        $member = Member::create($validated);
        
        // Attach ministries if provided
        if ($request->filled('ministries')) {
            foreach ($request->ministries as $ministryId) {
                $member->ministries()->attach($ministryId, [
                    'role' => 'member',
                    'joined_date' => now(),
                    'is_active' => true
                ]);
            }
        }
        
        return redirect()->route('members.show', $member)
                        ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        $member->load([
            'family',
            'ministries' => function ($query) {
                $query->withPivot(['role', 'joined_date', 'left_date', 'is_active', 'notes']);
            },
            'donations' => function ($query) {
                $query->orderBy('donation_date', 'desc')->limit(10);
            },
            'eventAttendances.event',
            'organizedEvents' => function ($query) {
                $query->upcoming()->limit(5);
            }
        ]);
        
        return view('members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        $families = Family::active()->orderBy('family_name')->get();
        $ministries = Ministry::active()->orderBy('name')->get();
        $memberMinistries = $member->ministries->pluck('id')->toArray();
        
        return view('members.edit', compact('member', 'families', 'ministries', 'memberMinistries'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:50',
            'email' => ['nullable', 'email', Rule::unique('members')->ignore($member->id)],
            'phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'membership_date' => 'nullable|date',
            'membership_status' => 'required|in:active,inactive,transferred,deceased',
            'membership_type' => 'required|in:member,visitor,friend,associate',
            'family_id' => 'nullable|exists:families,id',
            'relationship_to_head' => 'nullable|string|max:50',
            'emergency_contact_name' => 'nullable|string',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'special_needs' => 'nullable|string',
            'notes' => 'nullable|string',
            'is_baptized' => 'boolean',
            'baptism_date' => 'nullable|date',
            'is_confirmed' => 'boolean',
            'confirmation_date' => 'nullable|date',
            'skills_talents' => 'nullable|array',
            'interests' => 'nullable|array',
            'receive_newsletter' => 'boolean',
            'receive_sms' => 'boolean',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ministries' => 'nullable|array',
            'ministries.*' => 'exists:ministries,id'
        ]);
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo_path) {
                Storage::disk('public')->delete($member->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('members/photos', 'public');
        }
        
        $member->update($validated);
        
        // Sync ministries
        if ($request->has('ministries')) {
            $member->ministries()->detach();
            foreach ($request->ministries as $ministryId) {
                $member->ministries()->attach($ministryId, [
                    'role' => 'member',
                    'joined_date' => now(),
                    'is_active' => true
                ]);
            }
        }
        
        return redirect()->route('members.show', $member)
                        ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // Delete photo if exists
        if ($member->photo_path) {
            Storage::disk('public')->delete($member->photo_path);
        }
        
        $member->delete();
        
        return redirect()->route('members.index')
                        ->with('success', 'Member deleted successfully.');
    }

    /**
     * Export members data
     */
    public function export(Request $request)
    {
        // This would implement Excel export functionality
        // For now, return a simple response
        return response()->json(['message' => 'Export functionality to be implemented']);
    }

    /**
     * Generate member ID card
     */
    public function generateIdCard(Member $member)
    {
        // This would generate a PDF ID card
        // For now, return a simple response
        return response()->json(['message' => 'ID card generation to be implemented']);
    }

    /**
     * Get member statistics
     */
    public function statistics()
    {
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::active()->count(),
            'new_this_month' => Member::whereMonth('created_at', now()->month)->count(),
            'by_gender' => Member::selectRaw('gender, COUNT(*) as count')
                                ->groupBy('gender')
                                ->pluck('count', 'gender'),
            'by_age_group' => [
                'children' => Member::byAgeRange(0, 12)->count(),
                'youth' => Member::byAgeRange(13, 25)->count(),
                'adults' => Member::byAgeRange(26, 59)->count(),
                'seniors' => Member::byAgeRange(60, 120)->count(),
            ],
            'by_membership_status' => Member::selectRaw('membership_status, COUNT(*) as count')
                                          ->groupBy('membership_status')
                                          ->pluck('count', 'membership_status'),
        ];
        
        return response()->json($stats);
    }
}
