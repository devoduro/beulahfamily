<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

        Auth::guard('member')->login($member, $request->boolean('remember'));

        $request->session()->regenerate();

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
        ]);

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
        ]);

        return back()->with('success', 'Password changed successfully.');
    }
}
