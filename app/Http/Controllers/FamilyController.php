<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Advanced search and filtering
        $query = Family::with(['members', 'head'])
            ->withCount(['members as total_members', 'members as active_members' => function($q) {
                $q->where('is_active', true);
            }]);
        
        // Search functionality - enhanced
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('family_name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('state', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('head', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('members', function($q) use ($search) {
                      $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }
        
        // Advanced filters
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }
        
        if ($request->filled('member_count')) {
            switch ($request->member_count) {
                case 'single':
                    $query->having('total_members', '=', 1);
                    break;
                case 'small':
                    $query->having('total_members', '>=', 2)->having('total_members', '<=', 4);
                    break;
                case 'large':
                    $query->having('total_members', '>', 4);
                    break;
            }
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'family_name');
        $sortDirection = $request->get('direction', 'asc');
        
        switch ($sortBy) {
            case 'member_count':
                $query->orderBy('total_members', $sortDirection);
                break;
            case 'created_at':
                $query->orderBy('created_at', $sortDirection);
                break;
            default:
                $query->orderBy('family_name', $sortDirection);
        }
        
        $families = $query->paginate(12)->withQueryString();
        
        // Calculate statistics
        $stats = [
            'total_families' => Family::count(),
            'active_families' => Family::where('is_active', true)->count(),
            'total_members' => Member::count(),
            'active_members' => Member::where('is_active', true)->count(),
            'avg_family_size' => $families->avg('total_members') ?: 0,
            'cities_count' => Family::whereNotNull('city')->distinct('city')->count(),
            'cities' => Family::whereNotNull('city')->distinct()->pluck('city')->toArray(),
            'recent_families' => Family::with('head')->latest()->take(5)->get()
        ];

        // Add active_members count to each family
        $families->getCollection()->transform(function ($family) {
            $family->active_members = $family->members()->where('is_active', true)->count();
            return $family;
        });
        
        return view('families.index', compact('families', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('families.create', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255|unique:families,family_name',
            'head_of_family_id' => 'nullable|exists:members,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $family = Family::create($validated);
        
        return redirect()->route('families.show', $family)
                        ->with('success', 'Family created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Family $family)
    {
        $family->load(['members', 'head']);
        return view('families.show', compact('family'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Family $family)
    {
        $members = Member::active()->orderBy('first_name')->get();
        return view('families.edit', compact('family', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Family $family)
    {
        $validated = $request->validate([
            'family_name' => ['required', 'string', 'max:255', Rule::unique('families')->ignore($family->id)],
            'head_of_family_id' => 'nullable|exists:members,id',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string',
            'is_active' => 'boolean'
        ]);
        
        $family->update($validated);
        
        return redirect()->route('families.show', $family)
                        ->with('success', 'Family updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Family $family)
    {
        // Check if family has members
        if ($family->members()->count() > 0) {
            return redirect()->route('families.index')
                           ->with('error', 'Cannot delete family with existing members. Please reassign or remove members first.');
        }
        
        $family->delete();
        
        return redirect()->route('families.index')
                        ->with('success', 'Family deleted successfully.');
    }

    /**
     * Handle bulk actions for families
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'families' => 'required|array',
            'families.*' => 'exists:families,id'
        ]);

        $familyIds = $request->families;
        $action = $request->action;
        $count = count($familyIds);

        switch ($action) {
            case 'activate':
                Family::whereIn('id', $familyIds)->update(['is_active' => true]);
                return redirect()->route('families.index')->with('success', "{$count} families activated successfully.");

            case 'deactivate':
                Family::whereIn('id', $familyIds)->update(['is_active' => false]);
                return redirect()->route('families.index')->with('success', "{$count} families deactivated successfully.");

            case 'delete':
                Family::whereIn('id', $familyIds)->delete();
                return redirect()->route('families.index')->with('success', "{$count} families deleted successfully.");

            default:
                return redirect()->route('families.index')->with('error', 'Invalid action.');
        }
    }

    /**
     * Get family members for API
     */
    public function getFamilyMembers(Family $family)
    {
        $members = $family->members()->with(['family'])->get()->map(function ($member) use ($family) {
            return [
                'id' => $member->id,
                'full_name' => $member->full_name,
                'email' => $member->email,
                'phone' => $member->phone,
                'photo_path' => $member->photo_path,
                'membership_type' => $member->membership_type,
                'is_head' => $family->head_of_family_id == $member->id
            ];
        });

        return response()->json($members);
    }

    /**
     * Add member to family
     */
    public function addMemberToFamily(Request $request, Family $family)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id'
        ]);

        $member = Member::findOrFail($request->member_id);

        // Check if member is already in another family
        if ($member->family_id && $member->family_id != $family->id) {
            return response()->json([
                'success' => false,
                'message' => 'Member is already assigned to another family.'
            ], 400);
        }

        // Add member to family
        $member->update(['family_id' => $family->id]);

        return response()->json([
            'success' => true,
            'message' => 'Member added to family successfully.'
        ]);
    }

    /**
     * Remove member from family
     */
    public function removeMemberFromFamily(Family $family, Member $member)
    {
        // Check if member is the family head
        if ($family->head_of_family_id == $member->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot remove family head. Please assign a new head first.'
            ], 400);
        }

        // Remove member from family
        $member->update(['family_id' => null]);

        return response()->json([
            'success' => true,
            'message' => 'Member removed from family successfully.'
        ]);
    }

    /**
     * Get available members (not assigned to any family)
     */
    public function getAvailableMembers()
    {
        $members = Member::whereNull('family_id')
            ->orWhere('family_id', 0)
            ->active()
            ->orderBy('first_name')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->id,
                    'full_name' => $member->full_name,
                    'email' => $member->email,
                    'phone' => $member->phone,
                    'photo_path' => $member->photo_path,
                    'membership_type' => $member->membership_type
                ];
            });

        return response()->json($members);
    }

    /**
     * Export families to CSV
     */
    public function export(Request $request)
    {
        $query = Family::with(['head', 'members']);

        // If specific families are requested
        if ($request->has('families')) {
            $familyIds = explode(',', $request->families);
            $query->whereIn('id', $familyIds);
        }

        $families = $query->get();

        $filename = 'families_export_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($families) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Family Name',
                'Head of Family',
                'Email',
                'Phone',
                'Address',
                'City',
                'State',
                'Zip Code',
                'Total Members',
                'Active Members',
                'Status',
                'Created Date'
            ]);

            // CSV Data
            foreach ($families as $family) {
                fputcsv($file, [
                    $family->family_name,
                    $family->head ? $family->head->full_name : 'N/A',
                    $family->email ?: 'N/A',
                    $family->phone ?: 'N/A',
                    $family->address ?: 'N/A',
                    $family->city ?: 'N/A',
                    $family->state ?: 'N/A',
                    $family->zip_code ?: 'N/A',
                    $family->total_members,
                    $family->active_members,
                    $family->is_active ? 'Active' : 'Inactive',
                    $family->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
