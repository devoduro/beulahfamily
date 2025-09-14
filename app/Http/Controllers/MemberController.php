<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\MembersExport;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Member::with(['family', 'ministries']);
        
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
            $query->where('membership_status', $request->status);
        }
        
        // Filter by membership type
        if ($request->filled('type')) {
            $query->where('membership_type', $request->type);
        }
        
        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        // Filter by age range
        if ($request->filled('age_range')) {
            $ageRange = $request->age_range;
            if ($ageRange === '0-12') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 12');
            } elseif ($ageRange === '13-25') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 13 AND 25');
            } elseif ($ageRange === '26-59') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 59');
            } elseif ($ageRange === '60-120') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60');
            }
        }
        
        // Filter by family
        if ($request->filled('family_id')) {
            $query->where('family_id', $request->family_id);
        }
        
        // Filter by ministry
        if ($request->filled('ministry_id')) {
            $query->whereHas('ministries', function ($q) use ($request) {
                $q->where('ministries.id', $request->ministry_id);
            });
        }
        
        // Filter by chapter
        if ($request->filled('chapter')) {
            $query->where('chapter', $request->chapter);
        }
        
        // Sorting
        $sortBy = $request->get('sort', 'first_name');
        $sortDirection = $request->get('direction', 'asc');
        
        if ($sortBy === 'name') {
            $query->orderBy('first_name', $sortDirection)->orderBy('last_name', $sortDirection);
        } elseif ($sortBy === 'membership_date') {
            $query->orderBy('membership_date', $sortDirection);
        } elseif ($sortBy === 'family') {
            $query->join('families', 'members.family_id', '=', 'families.id')
                  ->orderBy('families.family_name', $sortDirection)
                  ->select('members.*');
        } else {
            $query->orderBy($sortBy, $sortDirection);
        }
        
        $members = $query->paginate(20)->withQueryString();
        
        // Calculate statistics
        $stats = [
            'total_members' => Member::count(),
            'active_members' => Member::where('membership_status', 'active')->count(),
            'new_members' => Member::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count(),
            'total_families' => Family::count(),
            'active_ministries' => Ministry::where('is_active', true)->count(),
            'chapter_stats' => [
                'ACCRA' => Member::where('chapter', 'ACCRA')->count(),
                'KUMASI' => Member::where('chapter', 'KUMASI')->count(),
                'NEW JESSY' => Member::where('chapter', 'NEW JESSY')->count(),
            ],
        ];
        
        $families = Family::orderBy('family_name')->get();
        $ministries = Ministry::where('is_active', true)->orderBy('name')->get();
        
        return view('members.index', compact('members', 'families', 'ministries', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $families = \App\Models\Family::where('is_active', true)->orderBy('family_name')->get();
        } catch (\Exception $e) {
            $families = collect();
        }
        
        try {
            $ministries = \App\Models\Ministry::where('is_active', true)->orderBy('name')->get();
        } catch (\Exception $e) {
            $ministries = collect();
        }
        
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
            'whatsapp_phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'church_affiliation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'membership_date' => 'nullable|date',
            'membership_status' => 'required|in:active,inactive,transferred,deceased',
            'membership_type' => 'nullable|in:member,visitor,friend,associate',
            'chapter' => 'required|in:ACCRA,KUMASI,NEW JESSY',
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
            'skills_talents' => 'nullable|string',
            'interests' => 'nullable|string',
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
            'whatsapp_phone' => 'nullable|string|max:20',
            'alternate_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'church_affiliation' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'membership_date' => 'nullable|date',
            'membership_status' => 'required|in:active,inactive,transferred,deceased',
            'membership_type' => 'nullable|in:member,visitor,friend,associate',
            'chapter' => 'required|in:ACCRA,KUMASI,NEW JESSY',
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
            'skills_talents' => 'nullable|string',
            'interests' => 'nullable|string',
            'receive_newsletter' => 'boolean',
            'receive_sms' => 'boolean',
            'is_active' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'ministries' => 'nullable|array',
            'ministries.*' => 'exists:ministries,id'
        ]);
        
        // Set default membership_type if not provided
        if (empty($validated['membership_type'])) {
            $validated['membership_type'] = 'member';
        }
        
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
     * Export members data in multiple formats
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv'); // Default to CSV
        
        $query = Member::with(['family', 'ministries']);
        
        // Apply same filters as index method
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
        
        if ($request->filled('status')) {
            $query->where('membership_status', $request->status);
        }
        
        if ($request->filled('type')) {
            $query->where('membership_type', $request->type);
        }
        
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        
        if ($request->filled('age_range')) {
            $ageRange = $request->age_range;
            if ($ageRange === '0-12') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 0 AND 12');
            } elseif ($ageRange === '13-25') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 13 AND 25');
            } elseif ($ageRange === '26-59') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 59');
            } elseif ($ageRange === '60-120') {
                $query->whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) >= 60');
            }
        }
        
        if ($request->filled('family_id')) {
            $query->where('family_id', $request->family_id);
        }
        
        if ($request->filled('ministry_id')) {
            $query->whereHas('ministries', function ($q) use ($request) {
                $q->where('ministries.id', $request->ministry_id);
            });
        }
        
        if ($request->filled('chapter')) {
            $query->where('chapter', $request->chapter);
        }
        
        $members = $query->orderBy('first_name')->orderBy('last_name')->get();
        
        $timestamp = now()->format('Y-m-d_H-i-s');
        
        switch ($format) {
            case 'excel':
                return Excel::download(new MembersExport($members), "members_export_{$timestamp}.xlsx");
                
            case 'pdf':
                $pdf = Pdf::loadView('exports.members-pdf', compact('members'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions([
                        'defaultFont' => 'sans-serif',
                        'isRemoteEnabled' => true,
                        'isHtml5ParserEnabled' => true,
                    ]);
                return $pdf->download("members_export_{$timestamp}.pdf");
                
            case 'csv':
            default:
                $filename = "members_export_{$timestamp}.csv";
                
                $headers = [
                    'Content-Type' => 'text/csv',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '"',
                ];
                
                $callback = function() use ($members) {
                    $file = fopen('php://output', 'w');
                    
                    // CSV Headers
                    fputcsv($file, [
                        'Member ID',
                        'First Name',
                        'Last Name',
                        'Email',
                        'Phone',
                        'Gender',
                        'Date of Birth',
                        'Age',
                        'Address',
                        'City',
                        'State',
                        'Postal Code',
                        'Family Name',
                        'Chapter',
                        'Membership Status',
                        'Membership Type',
                        'Membership Date',
                        'Ministries',
                        'Created At'
                    ]);
                    
                    // CSV Data
                    foreach ($members as $member) {
                        $age = $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->age : '';
                        $ministries = $member->ministries->pluck('name')->join(', ');
                        
                        fputcsv($file, [
                            $member->member_id,
                            $member->first_name,
                            $member->last_name,
                            $member->email,
                            $member->phone,
                            ucfirst($member->gender ?? ''),
                            $member->date_of_birth ? \Carbon\Carbon::parse($member->date_of_birth)->format('Y-m-d') : '',
                            $age,
                            $member->address,
                            $member->city,
                            $member->state,
                            $member->postal_code,
                            $member->family ? $member->family->family_name : '',
                            $member->chapter ?? 'ACCRA',
                            ucfirst($member->membership_status),
                            ucfirst($member->membership_type),
                            $member->membership_date ? \Carbon\Carbon::parse($member->membership_date)->format('Y-m-d') : '',
                            $ministries,
                            $member->created_at->format('Y-m-d H:i:s')
                        ]);
                    }
                    
                    fclose($file);
                };
                
                return response()->stream($callback, 200, $headers);
        }
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
