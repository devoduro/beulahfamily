<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of announcements.
     */
    public function index(Request $request)
    {
        $query = Announcement::with(['creator']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%")
                  ->orWhere('type', 'LIKE', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('type', $request->type);
        }

        // Filter by priority
        if ($request->has('priority') && $request->priority !== '') {
            $query->where('priority', $request->priority);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'publish_date');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $announcements = $query->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'announcements' => $announcements->items(),
                'pagination' => [
                    'current_page' => $announcements->currentPage(),
                    'last_page' => $announcements->lastPage(),
                    'total' => $announcements->total()
                ]
            ]);
        }

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating a new announcement.
     */
    public function create()
    {
        return view('announcements.create');
    }

    /**
     * Store a newly created announcement in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,event,prayer_request,urgent,celebration,ministry',
            'priority' => 'required|in:low,medium,high,urgent',
            'publish_date' => 'required|date',
            'expire_date' => 'nullable|date|after:publish_date',
            'target_audience' => 'nullable|array',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'display_on_website' => 'boolean',
            'display_on_screens' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'nullable|file|max:10240',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $announcementData = $request->all();
        $announcementData['created_by'] = auth()->id();
        $announcementData['target_audience'] = $request->get('target_audience', []);

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements/images', 'public');
            $announcementData['image_path'] = $imagePath;
        }

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('announcements/attachments', 'public');
            $announcementData['attachment_path'] = $attachmentPath;
        }

        $announcement = Announcement::create($announcementData);

        // Auto-publish if publish date is now or in the past
        if ($announcement->publish_date <= now()) {
            $announcement->publish();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'announcement' => $announcement->load('creator'),
                'message' => 'Announcement created successfully!'
            ]);
        }

        return redirect()->route('announcements.show', $announcement)
                        ->with('success', 'Announcement created successfully!');
    }

    /**
     * Display the specified announcement.
     */
    public function show(Announcement $announcement)
    {
        $announcement->load(['creator']);
        
        // Increment view count
        $announcement->increment('view_count');

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing the specified announcement.
     */
    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update the specified announcement in storage.
     */
    public function update(Request $request, Announcement $announcement)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|in:general,event,prayer_request,urgent,celebration,ministry',
            'priority' => 'required|in:low,medium,high,urgent',
            'publish_date' => 'required|date',
            'expire_date' => 'nullable|date|after:publish_date',
            'target_audience' => 'nullable|array',
            'send_email' => 'boolean',
            'send_sms' => 'boolean',
            'display_on_website' => 'boolean',
            'display_on_screens' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachment' => 'nullable|file|max:10240',
            'status' => 'required|in:draft,published,expired,archived',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $announcementData = $request->all();
        $announcementData['target_audience'] = $request->get('target_audience', []);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $imagePath = $request->file('image')->store('announcements/images', 'public');
            $announcementData['image_path'] = $imagePath;
        }

        // Handle attachment upload
        if ($request->hasFile('attachment')) {
            // Delete old attachment if exists
            if ($announcement->attachment_path) {
                Storage::disk('public')->delete($announcement->attachment_path);
            }
            $attachmentPath = $request->file('attachment')->store('announcements/attachments', 'public');
            $announcementData['attachment_path'] = $attachmentPath;
        }

        $announcement->update($announcementData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'announcement' => $announcement->load('creator'),
                'message' => 'Announcement updated successfully!'
            ]);
        }

        return redirect()->route('announcements.show', $announcement)
                        ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Remove the specified announcement from storage.
     */
    public function destroy(Announcement $announcement)
    {
        // Delete associated files
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }
        if ($announcement->attachment_path) {
            Storage::disk('public')->delete($announcement->attachment_path);
        }

        $announcement->delete();

        return response()->json([
            'success' => true,
            'message' => 'Announcement deleted successfully!'
        ]);
    }

    /**
     * Publish an announcement.
     */
    public function publish(Announcement $announcement)
    {
        $announcement->publish();

        return response()->json([
            'success' => true,
            'message' => 'Announcement published successfully!',
            'announcement' => $announcement
        ]);
    }

    /**
     * Archive an announcement.
     */
    public function archive(Announcement $announcement)
    {
        $announcement->archive();

        return response()->json([
            'success' => true,
            'message' => 'Announcement archived successfully!',
            'announcement' => $announcement
        ]);
    }

    /**
     * Get public announcements for website display.
     */
    public function public(Request $request)
    {
        $announcements = Announcement::published()
                                   ->where('display_on_website', true)
                                   ->where(function ($query) {
                                       $query->whereNull('expire_date')
                                             ->orWhere('expire_date', '>', now());
                                   })
                                   ->orderBy('priority', 'desc')
                                   ->orderBy('publish_date', 'desc')
                                   ->limit(10)
                                   ->get();

        return response()->json($announcements);
    }

    /**
     * Get announcements for digital screens.
     */
    public function screens(Request $request)
    {
        $announcements = Announcement::published()
                                   ->where('display_on_screens', true)
                                   ->where(function ($query) {
                                       $query->whereNull('expire_date')
                                             ->orWhere('expire_date', '>', now());
                                   })
                                   ->orderBy('priority', 'desc')
                                   ->orderBy('publish_date', 'desc')
                                   ->get();

        return response()->json($announcements);
    }

    /**
     * Get announcement statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_announcements' => Announcement::count(),
            'published_announcements' => Announcement::published()->count(),
            'draft_announcements' => Announcement::where('status', 'draft')->count(),
            'announcements_by_type' => Announcement::selectRaw('type, COUNT(*) as count')
                                                 ->groupBy('type')
                                                 ->pluck('count', 'type'),
            'total_views' => Announcement::sum('view_count'),
            'most_viewed' => Announcement::orderBy('view_count', 'desc')->first(),
        ];

        return response()->json($stats);
    }

    /**
     * Send announcement notifications.
     */
    public function sendNotifications(Announcement $announcement)
    {
        // This would typically integrate with email and SMS services
        $emailSent = false;
        $smsSent = false;

        if ($announcement->send_email) {
            // Send email notifications
            $emailSent = true; // Placeholder
        }

        if ($announcement->send_sms) {
            // Send SMS notifications
            $smsSent = true; // Placeholder
        }

        return response()->json([
            'success' => true,
            'message' => 'Notifications sent successfully!',
            'email_sent' => $emailSent,
            'sms_sent' => $smsSent
        ]);
    }
}
