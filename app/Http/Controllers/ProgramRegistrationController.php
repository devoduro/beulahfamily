<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\ProgramRegistration;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramRegistrationController extends Controller
{
    /**
     * Show the registration form for a program
     */
    public function create(Program $program)
    {
        if (!$program->isRegistrationOpen()) {
            return redirect()->route('programs.show', $program)
                           ->with('error', 'Registration is closed for this program.');
        }

        $members = Member::where('membership_status', 'active')->orderBy('first_name')->get();
        $businessTypes = ProgramRegistration::getBusinessTypeOptions();

        return view('programs.register', compact('program', 'members', 'businessTypes'));
    }

    /**
     * Store a new registration
     */
    public function store(Request $request, Program $program)
    {
        if (!$program->isRegistrationOpen()) {
            return back()->with('error', 'Registration is closed for this program.');
        }

        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:' . implode(',', array_keys(ProgramRegistration::getBusinessTypeOptions())),
            'business_type_other' => 'required_if:business_type,other|nullable|string|max:255',
            'services_offered' => 'required|string',
            'business_address' => 'required|string',
            'contact_name' => 'required|string|max:255',
            'business_phone' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'special_offers' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,mp4,mov,avi,mp3,wav|max:' . ($program->max_file_size * 1024),
        ]);

        // Handle file uploads
        $uploadedFiles = [];
        if ($request->hasFile('files') && $program->allow_file_uploads) {
            $files = $request->file('files');
            
            // Check file count limit
            if (count($files) > $program->max_files) {
                return back()->withErrors(['files' => "Maximum {$program->max_files} files allowed."])->withInput();
            }

            foreach ($files as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $file->storeAs('program-registrations/' . $program->id, $filename, 'public');
                
                $uploadedFiles[] = [
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toISOString(),
                ];
            }
        }

        $validated['uploaded_files'] = $uploadedFiles;
        $validated['program_id'] = $program->id;
        $validated['registered_at'] = now();
        $validated['status'] = 'pending';
        $validated['payment_status'] = 'pending';

        $registration = ProgramRegistration::create($validated);

        return redirect()->route('programs.registration.success', [$program, $registration])
                        ->with('success', 'Registration submitted successfully!');
    }

    /**
     * Show registration success page
     */
    public function success(Program $program, ProgramRegistration $registration)
    {
        return view('programs.registration-success', compact('program', 'registration'));
    }

    /**
     * Show a specific registration
     */
    public function showRegistration(Program $program, ProgramRegistration $registration)
    {
        $registration->load('member');
        return view('programs.registration-details', compact('program', 'registration'));
    }

    /**
     * Show the form for editing a registration
     */
    public function edit(Program $program, ProgramRegistration $registration)
    {
        $members = Member::where('membership_status', 'active')->orderBy('first_name')->get();
        $businessTypes = ProgramRegistration::getBusinessTypeOptions();

        return view('programs.edit-registration', compact('program', 'registration', 'members', 'businessTypes'));
    }

    /**
     * Update a registration
     */
    public function update(Request $request, Program $program, ProgramRegistration $registration)
    {
        $validated = $request->validate([
            'member_id' => 'nullable|exists:members,id',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:' . implode(',', array_keys(ProgramRegistration::getBusinessTypeOptions())),
            'business_type_other' => 'required_if:business_type,other|nullable|string|max:255',
            'services_offered' => 'required|string',
            'business_address' => 'required|string',
            'contact_name' => 'required|string|max:255',
            'business_phone' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email' => 'required|email|max:255',
            'special_offers' => 'nullable|string',
            'additional_info' => 'nullable|string',
            'files.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png,gif,mp4,mov,avi,mp3,wav|max:' . ($program->max_file_size * 1024),
            'remove_files' => 'nullable|array',
        ]);

        // Handle file removals
        $currentFiles = $registration->uploaded_files ?? [];
        if ($request->filled('remove_files')) {
            foreach ($request->remove_files as $fileIndex) {
                if (isset($currentFiles[$fileIndex])) {
                    $filePath = $currentFiles[$fileIndex]['path'];
                    if (Storage::disk('public')->exists($filePath)) {
                        Storage::disk('public')->delete($filePath);
                    }
                    unset($currentFiles[$fileIndex]);
                }
            }
            $currentFiles = array_values($currentFiles); // Re-index array
        }

        // Handle new file uploads
        if ($request->hasFile('files') && $program->allow_file_uploads) {
            $newFiles = $request->file('files');
            $totalFiles = count($currentFiles) + count($newFiles);
            
            // Check file count limit
            if ($totalFiles > $program->max_files) {
                return back()->withErrors(['files' => "Maximum {$program->max_files} files allowed in total."])->withInput();
            }

            foreach ($newFiles as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;
                $path = $file->storeAs('program-registrations/' . $program->id, $filename, 'public');
                
                $currentFiles[] = [
                    'original_name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'uploaded_at' => now()->toISOString(),
                ];
            }
        }

        $validated['uploaded_files'] = $currentFiles;
        $registration->update($validated);

        return redirect()->route('programs.registration.show', [$program, $registration])
                        ->with('success', 'Registration updated successfully!');
    }

    /**
     * Cancel a registration
     */
    public function cancel(Program $program, ProgramRegistration $registration)
    {
        $registration->update(['status' => 'cancelled']);

        return redirect()->route('programs.show', $program)
                        ->with('success', 'Registration cancelled successfully.');
    }

    /**
     * Download a file from registration
     */
    public function downloadFile(Program $program, ProgramRegistration $registration, $fileIndex)
    {
        $files = $registration->uploaded_files ?? [];
        
        if (!isset($files[$fileIndex])) {
            abort(404, 'File not found.');
        }

        $file = $files[$fileIndex];
        $filePath = $file['path'];

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404, 'File not found on disk.');
        }

        return Storage::disk('public')->download($filePath, $file['original_name']);
    }

    /**
     * Admin: Update registration status
     */
    public function updateStatus(Request $request, Program $program, ProgramRegistration $registration)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled',
            'admin_notes' => 'nullable|string',
        ]);

        $registration->update($validated);

        return back()->with('success', 'Registration status updated successfully.');
    }

    /**
     * Admin: Update payment status
     */
    public function updatePayment(Request $request, Program $program, ProgramRegistration $registration)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:pending,paid,partial,refunded',
            'amount_paid' => 'required|numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $registration->update($validated);

        return back()->with('success', 'Payment information updated successfully.');
    }

    /**
     * Show the list of programs
     */
    public function programs()
    {
        $programs = Program::published()
                          ->with('registrations')
                          ->orderBy('start_date')
                          ->get();

        return view('programs.index', compact('programs'));
    }

    /**
     * Show a specific program
     */
    public function show(Program $program)
    {
        // Only show published programs to public
        if ($program->status !== 'published') {
            abort(404);
        }

        $program->load(['registrations']);

        return view('programs.show', compact('program'));
    }
}
