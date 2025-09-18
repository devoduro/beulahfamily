<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrayerRequest;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrayerRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = PrayerRequest::with(['member'])
            ->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('member', function($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by urgency
        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by privacy
        if ($request->filled('privacy')) {
            if ($request->privacy === 'public') {
                $query->where('is_private', false);
            } elseif ($request->privacy === 'private') {
                $query->where('is_private', true);
            }
        }

        $prayerRequests = $query->paginate(12)->withQueryString();

        // Get filter options
        $categories = [
            'health' => 'Health & Healing',
            'family' => 'Family & Relationships',
            'financial' => 'Financial Needs',
            'spiritual' => 'Spiritual Growth',
            'career' => 'Career & Education',
            'relationship' => 'Relationships',
            'protection' => 'Protection & Safety',
            'guidance' => 'Guidance & Direction',
            'other' => 'Other Requests'
        ];

        $urgencyLevels = [
            'low' => 'Low Priority',
            'medium' => 'Medium Priority',
            'high' => 'High Priority',
            'urgent' => 'Urgent'
        ];

        // Get statistics
        $stats = [
            'total' => PrayerRequest::count(),
            'active' => PrayerRequest::where('status', 'active')->count(),
            'answered' => PrayerRequest::where('status', 'answered')->count(),
            'urgent' => PrayerRequest::where('urgency', 'urgent')->count(),
            'total_prayers' => PrayerRequest::sum('total_prayers'),
        ];

        return view('admin.prayer-requests.index', compact('prayerRequests', 'categories', 'urgencyLevels', 'stats'));
    }

    public function show(PrayerRequest $prayerRequest)
    {
        $prayerRequest->load(['member']);
        
        // Get related prayer requests
        $relatedRequests = PrayerRequest::where('category', $prayerRequest->category)
            ->where('id', '!=', $prayerRequest->id)
            ->where('is_private', false)
            ->where('status', 'active')
            ->with('member')
            ->latest()
            ->limit(3)
            ->get();

        return view('admin.prayer-requests.show', compact('prayerRequest', 'relatedRequests'));
    }

    public function markAnswered(Request $request, PrayerRequest $prayerRequest)
    {
        $request->validate([
            'answer_testimony' => 'required|string|min:10'
        ]);

        $prayerRequest->markAsAnswered($request->answer_testimony);

        return redirect()->back()->with('success', 'Prayer request marked as answered!');
    }

    public function reopen(PrayerRequest $prayerRequest)
    {
        $prayerRequest->update([
            'status' => 'active',
            'answered_at' => null,
            'answer_testimony' => null
        ]);

        return redirect()->back()->with('success', 'Prayer request reopened successfully!');
    }

    public function close(PrayerRequest $prayerRequest)
    {
        $prayerRequest->update([
            'status' => 'closed'
        ]);

        return redirect()->back()->with('success', 'Prayer request closed successfully!');
    }

    public function destroy(PrayerRequest $prayerRequest)
    {
        $prayerRequest->delete();
        return redirect()->route('admin.prayer-requests.index')->with('success', 'Prayer request deleted successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:close,reopen,delete',
            'prayer_requests' => 'required|array',
            'prayer_requests.*' => 'exists:prayer_requests,id'
        ]);

        $prayerRequests = PrayerRequest::whereIn('id', $request->prayer_requests);

        switch ($request->action) {
            case 'close':
                $prayerRequests->update(['status' => 'closed']);
                $message = 'Selected prayer requests closed successfully!';
                break;

            case 'reopen':
                $prayerRequests->update([
                    'status' => 'active',
                    'answered_at' => null,
                    'answer_testimony' => null
                ]);
                $message = 'Selected prayer requests reopened successfully!';
                break;

            case 'delete':
                $prayerRequests->delete();
                $message = 'Selected prayer requests deleted successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function export(Request $request)
    {
        $query = PrayerRequest::with(['member']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('member', function($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $prayerRequests = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="prayer-requests-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($prayerRequests) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Title',
                'Category',
                'Urgency',
                'Status',
                'Member Name',
                'Member ID',
                'Chapter',
                'Description Preview',
                'Total Prayers',
                'Is Anonymous',
                'Is Private',
                'Expires At',
                'Answered At',
                'Created At'
            ]);

            // CSV data
            foreach ($prayerRequests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->title,
                    $request->category_display,
                    ucfirst($request->urgency),
                    ucfirst($request->status),
                    $request->is_anonymous ? 'Anonymous' : ($request->member->full_name ?? 'N/A'),
                    $request->member->member_id ?? 'N/A',
                    $request->member->chapter ?? 'N/A',
                    substr($request->description, 0, 100) . '...',
                    $request->total_prayers,
                    $request->is_anonymous ? 'Yes' : 'No',
                    $request->is_private ? 'Yes' : 'No',
                    $request->expires_at ? $request->expires_at->format('Y-m-d') : 'N/A',
                    $request->answered_at ? $request->answered_at->format('Y-m-d H:i:s') : 'N/A',
                    $request->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function statistics()
    {
        $stats = [
            'overview' => [
                'total_requests' => PrayerRequest::count(),
                'active_requests' => PrayerRequest::where('status', 'active')->count(),
                'answered_requests' => PrayerRequest::where('status', 'answered')->count(),
                'closed_requests' => PrayerRequest::where('status', 'closed')->count(),
                'total_prayers' => PrayerRequest::sum('total_prayers'),
            ],
            'by_urgency' => PrayerRequest::select('urgency', DB::raw('count(*) as count'))
                ->groupBy('urgency')
                ->pluck('count', 'urgency')
                ->toArray(),
            'by_category' => PrayerRequest::select('category', DB::raw('count(*) as count'))
                ->groupBy('category')
                ->pluck('count', 'category')
                ->toArray(),
            'by_month' => PrayerRequest::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('count(*) as count')
            )
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->limit(12)
                ->get()
                ->map(function($item) {
                    return [
                        'period' => date('M Y', mktime(0, 0, 0, $item->month, 1, $item->year)),
                        'count' => $item->count
                    ];
                })
        ];

        return view('admin.prayer-requests.statistics', compact('stats'));
    }
}
