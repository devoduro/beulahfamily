<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Family;
use App\Models\Event;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberFamilyController extends Controller
{
    /**
     * Display the member's family page
     */
    public function index()
    {
        $member = Auth::guard('member')->user();
        $family = $member->family;
        
        // If member doesn't have a family, create basic data structure
        if (!$family) {
            $familyData = [
                'family' => null,
                'familyMembers' => collect([$member]),
                'familyStats' => [
                    'total_members' => 1,
                    'church_members' => $member->membership_status === 'active' ? 1 : 0,
                    'total_donations' => $member->total_donations ?? 0,
                    'events_attended' => $member->eventAttendances()->count() ?? 0,
                    'ministry_involvement' => $member->activeMinistries()->count() ?? 0,
                    'years_as_members' => $member->membership_date ? now()->diffInYears($member->membership_date) : 0
                ],
                'familyActivities' => collect(),
                'churchConnections' => collect()
            ];
        } else {
            // Get family members
            $familyMembers = $family->activeMembers()->get();
            
            // Calculate family statistics
            $totalDonations = $familyMembers->sum(function ($member) {
                return $member->donations()->sum('amount') ?? 0;
            });
            
            $totalEventsAttended = $familyMembers->sum(function ($member) {
                return $member->eventAttendances()->count() ?? 0;
            });
            
            $totalMinistryInvolvement = $familyMembers->sum(function ($member) {
                return $member->activeMinistries()->count() ?? 0;
            });
            
            $churchMembers = $familyMembers->where('membership_status', 'active')->count();
            
            // Get oldest membership date for years calculation
            $oldestMembershipDate = $familyMembers->min('membership_date');
            $yearsAsMembers = $oldestMembershipDate ? now()->diffInYears($oldestMembershipDate) : 0;
            
            $familyStats = [
                'total_members' => $familyMembers->count(),
                'church_members' => $churchMembers,
                'total_donations' => $totalDonations,
                'events_attended' => $totalEventsAttended,
                'ministry_involvement' => $totalMinistryInvolvement,
                'years_as_members' => $yearsAsMembers
            ];
            
            // Get recent family activities (events attended by family members)
            $familyActivities = collect();
            try {
                if (class_exists('\App\Models\Event')) {
                    $memberIds = $familyMembers->pluck('id');
                    $familyActivities = Event::whereHas('attendances', function ($query) use ($memberIds) {
                        $query->whereIn('member_id', $memberIds);
                    })
                    ->where('start_datetime', '<=', now())
                    ->orderBy('start_datetime', 'desc')
                    ->take(5)
                    ->get();
                }
            } catch (\Exception $e) {
                $familyActivities = collect();
            }
            
            // Get church connections (ministries and leadership roles)
            $churchConnections = collect();
            try {
                if (class_exists('\App\Models\Ministry')) {
                    $memberIds = $familyMembers->pluck('id');
                    $churchConnections = Ministry::whereHas('members', function ($query) use ($memberIds) {
                        $query->whereIn('member_id', $memberIds)
                              ->where('is_active', true);
                    })
                    ->orWhereIn('leader_id', $memberIds)
                    ->take(5)
                    ->get();
                }
            } catch (\Exception $e) {
                $churchConnections = collect();
            }
            
            $familyData = [
                'family' => $family,
                'familyMembers' => $familyMembers,
                'familyStats' => $familyStats,
                'familyActivities' => $familyActivities,
                'churchConnections' => $churchConnections
            ];
        }
        
        return view('member.family.index', $familyData);
    }
    
    /**
     * Show form to add a new family member
     */
    public function addMember()
    {
        $member = Auth::guard('member')->user();
        $family = $member->family;
        
        return view('member.family.add-member', compact('family'));
    }
    
    /**
     * Store a new family member
     */
    public function storeMember(Request $request)
    {
        $member = Auth::guard('member')->user();
        $family = $member->family;
        
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:members,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'required|in:male,female',
            'relationship_to_head' => 'required|string|max:255',
            'membership_status' => 'required|in:active,inactive,pending',
            'membership_type' => 'nullable|string|max:255'
        ]);
        
        // If no family exists, create one
        if (!$family) {
            $family = Family::create([
                'family_name' => $member->last_name . ' Family',
                'is_active' => true
            ]);
            
            // Update current member to be head of family
            $member->update([
                'family_id' => $family->id,
                'relationship_to_head' => 'head'
            ]);
        }
        
        // Create new family member
        $validated['family_id'] = $family->id;
        $validated['is_active'] = true;
        
        Member::create($validated);
        
        return redirect()->route('member.family.index')
                        ->with('success', 'Family member added successfully!');
    }
    
    /**
     * Show a specific family member
     */
    public function showMember(Member $familyMember)
    {
        $currentMember = Auth::guard('member')->user();
        
        // Check if the family member belongs to the same family
        if (!$currentMember->family || $familyMember->family_id !== $currentMember->family_id) {
            abort(403, 'Unauthorized access to family member.');
        }
        
        return view('member.family.show-member', compact('familyMember'));
    }
    
    /**
     * Export family data
     */
    public function exportFamily()
    {
        $member = Auth::guard('member')->user();
        $family = $member->family;
        
        if (!$family) {
            return redirect()->back()->with('error', 'No family data to export.');
        }
        
        $familyMembers = $family->activeMembers()->get();
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="family_export_' . date('Y-m-d') . '.csv"',
        ];
        
        $callback = function() use ($familyMembers) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Member ID', 'Full Name', 'Email', 'Phone', 'Date of Birth', 
                'Gender', 'Relationship', 'Membership Status', 'Membership Date'
            ]);
            
            // CSV data
            foreach ($familyMembers as $member) {
                fputcsv($file, [
                    $member->member_id,
                    $member->full_name,
                    $member->email,
                    $member->phone,
                    $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
                    $member->gender,
                    $member->relationship_to_head,
                    $member->membership_status,
                    $member->membership_date ? $member->membership_date->format('Y-m-d') : ''
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
