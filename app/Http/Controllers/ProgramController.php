<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProgramController extends Controller
{
    /**
     * Display a listing of programs
     */
    public function index(Request $request)
    {
        $query = Program::with(['registrations']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('venue', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Sort
        $sortBy = $request->get('sort', 'start_date');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $programs = $query->paginate(10)->withQueryString();

        // Statistics
        $stats = [
            'total' => Program::count(),
            'published' => Program::where('status', 'published')->count(),
            'draft' => Program::where('status', 'draft')->count(),
            'ongoing' => Program::ongoing()->count(),
            'upcoming' => Program::upcoming()->published()->count(),
            'total_registrations' => ProgramRegistration::count(),
            'pending_registrations' => ProgramRegistration::pending()->count(),
        ];

        return view('admin.programs.index', compact('programs', 'stats'));
    }

    /**
     * Show the form for creating a new program
     */
    public function create()
    {
        return view('admin.programs.create');
    }

    /**
     * Store a newly created program
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:ergates_conference,annual_retreat,conference,workshop,seminar,retreat,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'venue' => 'nullable|string|max:255',
            'registration_fee' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'terms_and_conditions' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'allow_file_uploads' => 'boolean',
            'allowed_file_types' => 'nullable|array',
            'max_file_size' => 'nullable|integer|min:1|max:500',
            'max_files' => 'nullable|integer|min:1|max:20',
            'flyer' => 'nullable|file|mimes:jpg,jpeg,png,pdf,gif|max:10240',
            'program_category' => 'nullable|string|max:255',
            'registration_fields' => 'nullable|array',
        ]);

        // Handle flyer upload
        if ($request->hasFile('flyer')) {
            $flyerPath = $request->file('flyer')->store('program_flyers', 'public');
            $validated['flyer_path'] = $flyerPath;
        }

        $program = Program::create($validated);

        return redirect()->route('admin.programs.show', $program)
                        ->with('success', 'Program created successfully.');
    }

    /**
     * Display the specified program
     */
    public function show(Program $program)
    {
        $program->load(['registrations.member']);
        
        $stats = $program->getRegistrationStats();
        $businessTypeDistribution = $program->getBusinessTypeDistribution();
        
        $recentRegistrations = $program->registrations()
                                      ->with('member')
                                      ->latest('registered_at')
                                      ->take(10)
                                      ->get();

        return view('admin.programs.show', compact('program', 'stats', 'businessTypeDistribution', 'recentRegistrations'));
    }

    /**
     * Show the form for editing the specified program
     */
    public function edit(Program $program)
    {
        return view('admin.programs.edit', compact('program'));
    }

    /**
     * Update the specified program
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:ergates_conference,annual_retreat,conference,workshop,seminar,retreat,other',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'venue' => 'nullable|string|max:255',
            'registration_fee' => 'nullable|numeric|min:0',
            'max_participants' => 'nullable|integer|min:1',
            'registration_deadline' => 'nullable|date|before_or_equal:start_date',
            'status' => 'required|in:draft,published,ongoing,completed,cancelled',
            'terms_and_conditions' => 'nullable|string',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'allow_file_uploads' => 'boolean',
            'allowed_file_types' => 'nullable|array',
            'max_file_size' => 'nullable|integer|min:1|max:500',
            'max_files' => 'nullable|integer|min:1|max:20',
            'flyer' => 'nullable|file|mimes:jpg,jpeg,png,pdf,gif|max:10240',
            'program_category' => 'nullable|string|max:255',
            'registration_fields' => 'nullable|array',
        ]);

        // Handle flyer upload
        if ($request->hasFile('flyer')) {
            // Delete old flyer if exists
            if ($program->flyer_path && Storage::exists('public/' . $program->flyer_path)) {
                Storage::delete('public/' . $program->flyer_path);
            }
            
            $flyerPath = $request->file('flyer')->store('program_flyers', 'public');
            $validated['flyer_path'] = $flyerPath;
        }

        $program->update($validated);

        return redirect()->route('admin.programs.show', $program)
                        ->with('success', 'Program updated successfully.');
    }

    /**
     * Remove the specified program
     */
    public function destroy(Program $program)
    {
        // Delete program flyer
        if ($program->flyer_path && Storage::exists('public/' . $program->flyer_path)) {
            Storage::delete('public/' . $program->flyer_path);
        }

        // Delete associated files
        if ($program->registrations()->exists()) {
            foreach ($program->registrations as $registration) {
                if ($registration->uploaded_files) {
                    foreach ($registration->uploaded_files as $file) {
                        if (isset($file['path']) && Storage::exists($file['path'])) {
                            Storage::delete($file['path']);
                        }
                    }
                }
            }
        }

        $program->delete();

        return redirect()->route('admin.programs.index')
                        ->with('success', 'Program deleted successfully.');
    }

    /**
     * Show registrations for a program
     */
    public function registrations(Request $request, Program $program)
    {
        $query = $program->registrations()->with('member');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('business_phone', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by business type
        if ($request->filled('business_type')) {
            $query->where('business_type', $request->business_type);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $registrations = $query->latest('registered_at')->paginate(15)->withQueryString();

        $stats = $program->getRegistrationStats();
        $businessTypes = ProgramRegistration::getBusinessTypeOptions();

        return view('admin.programs.registrations', compact('program', 'registrations', 'stats', 'businessTypes'));
    }

    /**
     * Export registrations to CSV
     */
    public function exportRegistrations(Program $program)
    {
        $registrations = $program->registrations()->with('member')->get();

        $filename = 'program_registrations_' . $program->id . '_' . now()->format('Y_m_d_H_i_s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($registrations) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Registration ID',
                'Business Name',
                'Business Type',
                'Services Offered',
                'Business Address',
                'Contact Name',
                'Business Phone',
                'WhatsApp',
                'Email',
                'Special Offers',
                'Additional Info',
                'Member Name',
                'Status',
                'Payment Status',
                'Amount Paid',
                'Registered At',
                'Files Count'
            ]);

            // Data rows
            foreach ($registrations as $registration) {
                fputcsv($file, [
                    $registration->id,
                    $registration->business_name,
                    $registration->formatted_business_type,
                    $registration->services_offered,
                    $registration->business_address,
                    $registration->contact_name,
                    $registration->business_phone,
                    $registration->whatsapp_number,
                    $registration->email,
                    $registration->special_offers,
                    $registration->additional_info,
                    $registration->member ? $registration->member->full_name : 'N/A',
                    ucfirst($registration->status),
                    ucfirst($registration->payment_status),
                    $registration->amount_paid,
                    $registration->registered_at->format('Y-m-d H:i:s'),
                    $registration->getUploadedFilesCount()
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Bulk update registration status
     */
    public function bulkUpdateRegistrations(Request $request, Program $program)
    {
        $validated = $request->validate([
            'registration_ids' => 'required|array',
            'registration_ids.*' => 'exists:program_registrations,id',
            'bulk_status' => 'required|in:pending,approved,rejected,cancelled',
        ]);

        $updated = $program->registrations()
                          ->whereIn('id', $validated['registration_ids'])
                          ->update(['status' => $validated['bulk_status']]);

        return back()->with('success', "Updated {$updated} registration(s) to {$validated['bulk_status']} status.");
    }

    /**
     * Show a specific registration
     */
    public function showRegistration(Program $program, ProgramRegistration $registration)
    {
        // Ensure the registration belongs to this program
        if ($registration->program_id !== $program->id) {
            abort(404);
        }

        $registration->load('member');
        
        return view('admin.programs.registration-details', compact('program', 'registration'));
    }
}
