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
        // Check user role and redirect accordingly
        if (auth()->user()->role === User::ROLE_STAFF) {
            return redirect()->route('users.portal');
        }
        
        // Admin dashboard logic below
        // Get basic user metrics
        $totalUsers = User::count();
        $lastMonthUsers = User::where('created_at', '<', Carbon::now()->subMonth())->count();
        $userGrowth = $lastMonthUsers > 0 ? 
            round((($totalUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1) : 0;

        // Get document metrics
        $totalDocuments = Document::count();
        $activeDocuments = Document::where('is_active', true)->count();
        
        // Get legacy document management statistics (still available)
        $documentStats = [
            'total_users' => User::count(),
            'total_documents' => Document::count(),
            'total_categories' => DocumentCategory::count(),
            'total_prints' => UserDocumentPrint::sum('print_count'),
        ];
        
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
        $recentActivities = ActivityLog::with('user')
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
            'ageDemographics'
        ));
                ];
            });

        $recentActivities = $recentUserActivities->merge($recentDocumentActivities)
            ->sortByDesc(function ($activity) {
                return $activity['time'];
            })
            ->take(6)
            ->values();

        return view('dashboard', [
            'totalUsers' => $totalUsers,
            'userGrowth' => $userGrowth,
            'totalDocuments' => $totalDocuments,
            'activeDocuments' => $activeDocuments,
            'totalPrints' => $totalPrints,
            'documentsThisMonth' => $documentsThisMonth,
            'documentGrowth' => $documentGrowth,
            'totalCategories' => $totalCategories,
            'activeCategories' => $activeCategories,
            'categoriesWithDocuments' => $categoriesWithDocuments,
            'totalStorageBytes' => $totalStorageBytes,
            'averageFileSize' => $averageFileSize,
            'recentDocuments' => $recentDocuments,
            'topPrintedDocuments' => $topPrintedDocuments,
            'recentActivities' => $recentActivities
        ]);
    }
}
