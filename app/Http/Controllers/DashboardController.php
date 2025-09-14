<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display the dashboard with key metrics and analytics.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        // Check user role and redirect accordingly
        if ($user->role === User::ROLE_STAFF) {
            return redirect()->route('users.portal');
        }
        
        // Get church management statistics
        $churchStats = [
            'total_members' => \App\Models\Member::count(),
            'active_members' => \App\Models\Member::active()->count(),
            'total_families' => \App\Models\Family::count(),
            'total_ministries' => \App\Models\Ministry::active()->count(),
            'upcoming_events' => \App\Models\Event::upcoming()->published()->count(),
            'total_donations_this_year' => \App\Models\Donation::confirmed()
                ->whereYear('donation_date', now()->year)
                ->sum('amount'),
        ];
        
        // Get legacy document management statistics (still available)
        $documentStats = [
            'total_users' => User::count(),
            'total_documents' => Document::count(),
            'total_categories' => DocumentCategory::count(),
            'total_prints' => \App\Models\UserDocumentPrint::sum('print_count'),
        ];

        // Legacy variables for backward compatibility
        $totalUsers = $documentStats['total_users'];
        $totalDocuments = $documentStats['total_documents'];
        $totalCategories = $documentStats['total_categories'];
        $totalPrints = $documentStats['total_prints'];
        
        // Additional legacy variables
        $userGrowth = 0; // Placeholder for growth calculation
        $activeDocuments = Document::count(); // All documents are considered active
        $documentGrowth = 0; // Placeholder for growth calculation
        $totalStorageBytes = Document::sum('file_size') ?? 0;
        $averageFileSize = $totalDocuments > 0 ? round(($totalStorageBytes / 1024 / 1024) / $totalDocuments, 1) : 0;
        
        // Get recent documents for legacy dashboard
        $recentDocuments = Document::with('category')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        // Get top printed documents for legacy dashboard
        $topPrintedDocuments = Document::with('category')
            ->where('print_count', '>', 0)
            ->orderBy('print_count', 'desc')
            ->limit(6)
            ->get();
        
        // Get recent church activities
        $recentMembers = \App\Models\Member::with('family')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $upcomingEvents = \App\Models\Event::with(['ministry', 'organizer'])
            ->upcoming()
            ->published()
            ->orderBy('start_datetime')
            ->limit(5)
            ->get();
            
        $recentDonations = \App\Models\Donation::with('member')
            ->confirmed()
            ->orderBy('donation_date', 'desc')
            ->limit(5)
            ->get();
        
        // Get recent activities (legacy)
        $recentActivities = \App\Models\ActivityLog::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        // Get monthly donation statistics
        $monthlyDonations = \App\Models\Donation::selectRaw('MONTH(donation_date) as month, SUM(amount) as total')
            ->confirmed()
            ->whereYear('donation_date', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
        
        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($monthlyDonations[$i])) {
                $monthlyDonations[$i] = 0;
            }
        }
        ksort($monthlyDonations);
        
        // Get membership growth statistics
        $membershipGrowth = \App\Models\Member::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();
            
        // Fill missing months with 0
        for ($i = 1; $i <= 12; $i++) {
            if (!isset($membershipGrowth[$i])) {
                $membershipGrowth[$i] = 0;
            }
        }
        ksort($membershipGrowth);
        
        // Get age demographics
        $ageDemographics = [
            'children' => \App\Models\Member::byAgeRange(0, 12)->count(),
            'youth' => \App\Models\Member::byAgeRange(13, 25)->count(),
            'adults' => \App\Models\Member::byAgeRange(26, 59)->count(),
            'seniors' => \App\Models\Member::byAgeRange(60, 120)->count(),
        ];
        
        return view('dashboard', compact(
            'churchStats',
            'documentStats', 
            'recentActivities', 
            'recentMembers',
            'upcomingEvents',
            'recentDonations',
            'monthlyDonations',
            'membershipGrowth',
            'ageDemographics',
            'totalUsers',
            'totalDocuments',
            'totalCategories',
            'totalPrints',
            'userGrowth',
            'activeDocuments',
            'documentGrowth',
            'totalStorageBytes',
            'averageFileSize',
            'recentDocuments',
            'topPrintedDocuments'
        ));
    }
}
