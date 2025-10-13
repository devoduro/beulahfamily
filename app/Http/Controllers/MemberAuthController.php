<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MemberAuthController extends Controller
{
    /**
     * Show the member login form
     */
    public function showLoginForm()
    {
        return view('member.auth.login');
    }

    /**
     * Handle member login
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = $request->login;
        $password = $request->password;

        // Determine if login field is email or member ID
        $member = Member::where(function ($query) use ($loginField) {
            $query->where('email', $loginField)
                  ->orWhere('member_id', $loginField);
        })
        ->where('is_active', true)
        ->first();

        if (!$member || !Hash::check($password, $member->password)) {
            throw ValidationException::withMessages([
                'login' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Check if member account is approved
        if ($member->approval_status === 'pending') {
            throw ValidationException::withMessages([
                'login' => ['Your account is pending approval. Please wait for admin approval.'],
            ]);
        }

        if ($member->approval_status === 'rejected') {
            throw ValidationException::withMessages([
                'login' => ['Your account registration was rejected. Please contact the church office.'],
            ]);
        }

        Auth::guard('member')->login($member, $request->boolean('remember'));

        $request->session()->regenerate();

        // Check if password change is required
        if ($member->force_password_change) {
            return redirect()->route('member.password.change.form')
                ->with('warning', 'You must change your password before continuing.');
        }

        return redirect()->intended(route('member.dashboard'));
    }

    /**
     * Handle member logout
     */
    public function logout(Request $request)
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('member.login');
    }

    /**
     * Show member dashboard
     */
    public function dashboard()
    {
        $member = Auth::guard('member')->user();
        
        // Get member statistics (with safe defaults for missing relationships)
        $stats = [
            'total_donations' => 0, // $member->donations()->sum('amount') ?? 0,
            'yearly_donations' => 0, // $member->donations()->whereYear('donation_date', now()->year)->sum('amount') ?? 0,
            'donation_count' => 0, // $member->donations()->count() ?? 0,
            'events_attended' => 0, // $member->eventAttendances()->count() ?? 0,
            'active_ministries' => 0, // $member->activeMinistries()->count() ?? 0,
        ];

        // Get recent activities (with safe defaults)
        $recentDonations = collect(); // Empty collection for now
        $recentEvents = collect(); // Empty collection for now

        // Get upcoming events (with safe defaults)
        $upcomingEvents = collect(); // Empty collection for now
        
        // Try to get events if they exist
        try {
            if (class_exists('\App\Models\Event')) {
                $upcomingEvents = \App\Models\Event::where('start_datetime', '>=', now())
                                              ->where('status', 'published')
                                              ->orderBy('start_datetime')
                                              ->take(3)
                                              ->get();
            }
        } catch (\Exception $e) {
            $upcomingEvents = collect();
        }

        // Get active programs (with safe defaults)
        $activePrograms = collect(); // Empty collection for now
        
        // Try to get programs if the model exists
        try {
            if (class_exists('\App\Models\Program')) {
                $activePrograms = \App\Models\Program::where('status', 'published')
                                               ->where('end_date', '>=', now())
                                               ->orderBy('start_date')
                                               ->take(3)
                                               ->get();
            }
        } catch (\Exception $e) {
            $activePrograms = collect();
        }

        return view('member.dashboard', compact(
            'member', 
            'stats', 
            'recentDonations', 
            'recentEvents', 
            'upcomingEvents',
            'activePrograms'
        ));
    }

    /**
     * Show member profile
     */
    public function profile()
    {
        $member = Auth::guard('member')->user();
        return view('member.profile', compact('member'));
    }

    /**
     * Update member profile
     */
    public function updateProfile(Request $request)
    {
        $member = Auth::guard('member')->user();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'whatsapp_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:20',
            'occupation' => 'nullable|string|max:255',
            'employer' => 'nullable|string|max:255',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'receive_newsletter' => 'boolean',
            'receive_sms' => 'boolean',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo && Storage::disk('public')->exists($member->photo)) {
                Storage::disk('public')->delete($member->photo);
            }
            
            // Store new photo
            $path = $request->file('photo')->store('member-photos', 'public');
            $validated['photo'] = $path;
        }

        $member->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }

    /**
     * Change member password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $member = Auth::guard('member')->user();

        if (!Hash::check($request->current_password, $member->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $member->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'force_password_change' => false,
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    /**
     * Show member registration form
     */
    public function showRegistrationForm()
    {
        return view('member.auth.register');
    }

    /**
     * Handle member registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:members,email',
            'phone' => 'required|string|max:20',
            'whatsapp_phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'marital_status' => 'nullable|in:single,married,divorced,widowed',
            'address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'occupation' => 'nullable|string|max:255',
            'chapter' => 'required|in:ACCRA,KUMASI,NEW JESSY,STUDENTS',
            'membership_type' => 'required|in:member,visitor,friend,associate',
        ]);

        // Generate default password (first name + last 4 digits of phone)
        $defaultPassword = strtolower($validated['first_name']) . substr($validated['phone'], -4);

        // Create member with pending approval
        $member = Member::create(array_merge($validated, [
            'password' => Hash::make($defaultPassword),
            'membership_status' => 'active',
            'approval_status' => 'pending',
            'force_password_change' => true,
            'is_active' => false, // Inactive until approved
            'receive_newsletter' => true,
            'receive_sms' => true,
        ]));

        // Send welcome email with credentials
        try {
            Mail::send('emails.member-registration', [
                'member' => $member,
                'password' => $defaultPassword,
            ], function ($message) use ($member) {
                $message->to($member->email)
                    ->subject('Member Registration - Pending Approval');
            });
        } catch (\Exception $e) {
            // Log email error but continue
            \Log::error('Failed to send registration email: ' . $e->getMessage());
        }

        // Notify admins (optional - can be implemented later)

        return redirect()->route('member.login')
            ->with('success', '🎉 Registration Successful! Check your email for login credentials. Your account is pending admin approval.');
    }

    /**
     * Show password change form
     */
    public function showPasswordChangeForm()
    {
        $member = Auth::guard('member')->user();
        return view('member.auth.change-password', compact('member'));
    }

    /**
     * Handle forced password change
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        $member = Auth::guard('member')->user();

        if (!Hash::check($request->current_password, $member->password)) {
            throw ValidationException::withMessages([
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $member->update([
            'password' => Hash::make($request->password),
            'password_changed_at' => now(),
            'force_password_change' => false,
        ]);

        return redirect()->route('member.dashboard')
            ->with('success', 'Password changed successfully. You can now access your dashboard.');
    }
}
