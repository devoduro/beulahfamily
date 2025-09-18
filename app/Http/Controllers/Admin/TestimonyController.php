<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimony;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestimonyController extends Controller
{
    public function index(Request $request)
    {
        $query = Testimony::with(['member'])
            ->latest();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
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

        // Filter by approval status
        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        // Filter by visibility
        if ($request->filled('visibility')) {
            if ($request->visibility === 'public') {
                $query->where('is_public', true);
            } elseif ($request->visibility === 'private') {
                $query->where('is_public', false);
            }
        }

        $testimonies = $query->paginate(12)->withQueryString();

        // Get filter options
        $categories = [
            'healing' => 'Healing & Health',
            'financial' => 'Financial Breakthrough',
            'spiritual' => 'Spiritual Growth',
            'family' => 'Family & Relationships',
            'career' => 'Career & Education',
            'protection' => 'Divine Protection',
            'other' => 'Other Blessings'
        ];

        // Get statistics
        $stats = [
            'total' => Testimony::count(),
            'approved' => Testimony::where('is_approved', true)->count(),
            'pending' => Testimony::where('is_approved', false)->count(),
            'public' => Testimony::where('is_public', true)->count(),
        ];

        return view('admin.testimonies.index', compact('testimonies', 'categories', 'stats'));
    }

    public function show(Testimony $testimony)
    {
        $testimony->load(['member', 'approvedBy']);
        
        // Get related testimonies
        $relatedTestimonies = Testimony::where('category', $testimony->category)
            ->where('id', '!=', $testimony->id)
            ->where('is_public', true)
            ->where('is_approved', true)
            ->with('member')
            ->latest()
            ->limit(3)
            ->get();

        return view('admin.testimonies.show', compact('testimony', 'relatedTestimonies'));
    }

    public function approve(Request $request, Testimony $testimony)
    {
        $testimony->update([
            'is_approved' => true,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()->back()->with('success', 'Testimony approved successfully!');
    }

    public function reject(Request $request, Testimony $testimony)
    {
        $testimony->update([
            'is_approved' => false,
            'approved_at' => null,
            'approved_by' => null,
        ]);

        return redirect()->back()->with('success', 'Testimony approval revoked.');
    }

    public function destroy(Testimony $testimony)
    {
        $testimony->delete();
        return redirect()->route('admin.testimonies.index')->with('success', 'Testimony deleted successfully!');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'testimonies' => 'required|array',
            'testimonies.*' => 'exists:testimonies,id'
        ]);

        $testimonies = Testimony::whereIn('id', $request->testimonies);

        switch ($request->action) {
            case 'approve':
                $testimonies->update([
                    'is_approved' => true,
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
                $message = 'Selected testimonies approved successfully!';
                break;

            case 'reject':
                $testimonies->update([
                    'is_approved' => false,
                    'approved_at' => null,
                    'approved_by' => null,
                ]);
                $message = 'Selected testimonies rejected successfully!';
                break;

            case 'delete':
                $testimonies->delete();
                $message = 'Selected testimonies deleted successfully!';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    public function export(Request $request)
    {
        $query = Testimony::with(['member', 'approvedBy']);

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhereHas('member', function($memberQuery) use ($search) {
                      $memberQuery->where('first_name', 'like', "%{$search}%")
                                  ->orWhere('last_name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('status')) {
            if ($request->status === 'approved') {
                $query->where('is_approved', true);
            } elseif ($request->status === 'pending') {
                $query->where('is_approved', false);
            }
        }

        $testimonies = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="testimonies-' . date('Y-m-d') . '.csv"',
        ];

        $callback = function() use ($testimonies) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Title',
                'Category',
                'Member Name',
                'Member ID',
                'Chapter',
                'Content Preview',
                'Status',
                'Visibility',
                'Approved By',
                'Approved At',
                'Created At'
            ]);

            // CSV data
            foreach ($testimonies as $testimony) {
                fputcsv($file, [
                    $testimony->id,
                    $testimony->title,
                    $testimony->category_display,
                    $testimony->member->full_name ?? 'N/A',
                    $testimony->member->member_id ?? 'N/A',
                    $testimony->member->chapter ?? 'N/A',
                    substr($testimony->content, 0, 100) . '...',
                    $testimony->is_approved ? 'Approved' : 'Pending',
                    $testimony->is_public ? 'Public' : 'Private',
                    $testimony->approvedBy->name ?? 'N/A',
                    $testimony->approved_at ? $testimony->approved_at->format('Y-m-d H:i:s') : 'N/A',
                    $testimony->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
