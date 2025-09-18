<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MinistryController extends Controller
{
    /**
     * Display a listing of ministries.
     */
    public function index(Request $request)
    {
        $query = Ministry::with(['leader', 'members'])->withCount('members');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('ministry_type', 'LIKE', "%{$search}%")
                  ->orWhere('meeting_location', 'LIKE', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('ministry_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'active') {
                $query->active();
            } else {
                $query->where('is_active', false);
            }
        }

        // Sort
        $sortBy = $request->get('sort_by', 'name');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $ministries = $query->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'ministries' => $ministries->items(),
                'pagination' => [
                    'current_page' => $ministries->currentPage(),
                    'last_page' => $ministries->lastPage(),
                    'total' => $ministries->total()
                ]
            ]);
        }

        return view('ministries.index', compact('ministries'));
    }

    /**
     * Show the form for creating a new ministry.
     */
    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('ministries.create', compact('members'));
    }

    /**
     * Store a newly created ministry in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ministries,name',
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:members,id',
            'meeting_day' => 'nullable|string|max:20',
            'meeting_time' => 'nullable|date_format:H:i',
            'meeting_location' => 'nullable|string|max:255',
            'ministry_type' => 'required|in:worship,youth,children,men,women,seniors,outreach,education,administration,other',
            'target_age_min' => 'nullable|integer|min:0',
            'target_age_max' => 'nullable|integer|min:0|gte:target_age_min',
            'target_gender' => 'required|in:all,male,female',
            'requirements' => 'nullable|string',
            'goals' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
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

        $ministry = Ministry::create($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'ministry' => $ministry->load(['leader', 'members']),
                'message' => 'Ministry created successfully!'
            ]);
        }

        return redirect()->route('ministries.show', $ministry)
                        ->with('success', 'Ministry created successfully!');
    }

    /**
     * Display the specified ministry.
     */
    public function show(Ministry $ministry)
    {
        $ministry->load(['leader', 'members' => function ($query) {
            $query->orderByPivot('role', 'desc')
                  ->orderBy('first_name');
        }]);

        $ministryStats = [
            'total_members' => $ministry->members->count(),
            'leaders' => $ministry->members->where('pivot.role', 'leader')->count(),
            'active_members' => $ministry->members->where('pivot.is_active', true)->count(),
            'average_age' => $ministry->members->avg('age'),
        ];

        return view('ministries.show', compact('ministry', 'ministryStats'));
    }

    /**
     * Show the form for editing the specified ministry.
     */
    public function edit(Ministry $ministry)
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('ministries.edit', compact('ministry', 'members'));
    }

    /**
     * Update the specified ministry in storage.
     */
    public function update(Request $request, Ministry $ministry)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:ministries,name,' . $ministry->id,
            'description' => 'nullable|string',
            'leader_id' => 'nullable|exists:members,id',
            'meeting_day' => 'nullable|string|max:20',
            'meeting_time' => 'nullable|date_format:H:i',
            'meeting_location' => 'nullable|string|max:255',
            'ministry_type' => 'required|in:worship,youth,children,men,women,seniors,outreach,education,administration,other',
            'target_age_min' => 'nullable|integer|min:0',
            'target_age_max' => 'nullable|integer|min:0|gte:target_age_min',
            'target_gender' => 'required|in:all,male,female',
            'requirements' => 'nullable|string',
            'goals' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
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

        $ministry->update($request->all());

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'ministry' => $ministry->load(['leader', 'members']),
                'message' => 'Ministry updated successfully!'
            ]);
        }

        return redirect()->route('ministries.show', $ministry)
                        ->with('success', 'Ministry updated successfully!');
    }

    /**
     * Remove the specified ministry from storage.
     */
    public function destroy(Ministry $ministry)
    {
        // Check if ministry has members
        if ($ministry->members()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete ministry with existing members. Please remove members first.'
            ], 400);
        }

        $ministry->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ministry deleted successfully!'
        ]);
    }

    /**
     * Add a member to the ministry.
     */
    public function addMember(Request $request, Ministry $ministry)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'role' => 'required|string|in:leader,member,coordinator,assistant_leader'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $member = Member::findOrFail($request->member_id);
        
        // Check if member is already in this ministry
        if ($ministry->members()->where('member_ministry.member_id', $member->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Member is already part of this ministry.'
            ], 400);
        }

        $ministry->members()->attach($member->id, [
            'role' => $request->role,
            'joined_date' => now(),
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member added to ministry successfully!',
            'member' => $member
        ]);
    }

    /**
     * Remove a member from the ministry.
     */
    public function removeMember(Request $request, Ministry $ministry, Member $member)
    {
        if (!$ministry->members()->where('member_ministry.member_id', $member->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not part of this ministry.'
            ], 400);
        }

        $ministry->members()->detach($member->id);

        return response()->json([
            'success' => true,
            'message' => 'Member removed from ministry successfully!'
        ]);
    }

    /**
     * Update member role in ministry.
     */
    public function updateMemberRole(Request $request, Ministry $ministry, Member $member)
    {
        $validator = Validator::make($request->all(), [
            'role' => 'required|string|in:leader,member,coordinator,assistant_leader',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$ministry->members()->where('member_ministry.member_id', $member->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Member is not part of this ministry.'
            ], 400);
        }

        $ministry->members()->updateExistingPivot($member->id, [
            'role' => $request->role,
            'is_active' => $request->get('is_active', true)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Member role updated successfully!'
        ]);
    }

    /**
     * Show the member management page for a ministry.
     */
    public function manageMembers(Ministry $ministry)
    {
        $ministry->load(['members' => function ($query) {
            $query->orderByPivot('role', 'desc')
                  ->orderBy('first_name');
        }]);

        // Get all members not in this ministry with additional info
        $availableMembers = Member::active()
            ->whereNotIn('id', $ministry->members->pluck('id'))
            ->select('id', 'first_name', 'last_name', 'email', 'phone', 'gender', 'marital_status', 'date_of_birth', 'photo')
            ->orderBy('first_name')
            ->get();

        // Get filter options
        $genders = ['male' => 'Male', 'female' => 'Female'];
        $maritalStatuses = [
            'single' => 'Single',
            'married' => 'Married', 
            'divorced' => 'Divorced',
            'widowed' => 'Widowed'
        ];

        return view('ministries.manage-members', compact('ministry', 'availableMembers', 'genders', 'maritalStatuses'));
    }

    /**
     * Get ministry statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_ministries' => Ministry::count(),
            'active_ministries' => Ministry::active()->count(),
            'ministry_types' => Ministry::selectRaw('ministry_type, COUNT(*) as count')
                                     ->groupBy('ministry_type')
                                     ->pluck('count', 'ministry_type'),
            'total_budget' => Ministry::sum('budget'),
        ];

        return response()->json($stats);
    }
}
