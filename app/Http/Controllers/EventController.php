<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Ministry;
use App\Models\Member;
use App\Models\EventAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::with(['ministry', 'organizer', 'attendances']);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('location', 'LIKE', "%{$search}%")
                  ->orWhere('event_type', 'LIKE', "%{$search}%");
            });
        }

        // Filter by type
        if ($request->has('type') && $request->type !== '') {
            $query->where('event_type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'upcoming') {
                $query->where('start_datetime', '>=', now());
            } else {
                $query->where('status', $request->status);
            }
        }

        // Filter by date
        if ($request->has('date') && $request->date) {
            $date = Carbon::parse($request->date);
            $query->whereDate('start_datetime', '>=', $date);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'start_datetime');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $events = $query->paginate(12);

        // Calculate statistics
        $totalEvents = Event::count();
        $upcomingEvents = Event::where('start_datetime', '>=', now())->count();
        $thisMonthEvents = Event::whereBetween('start_datetime', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();
        $publishedEvents = Event::where('status', 'published')->count();
        $completedEvents = Event::where('status', 'completed')->count();

        // Add attendance counts to events
        $events->getCollection()->transform(function ($event) {
            $event->registered_count = $event->attendances->count();
            $event->checked_in_count = $event->attendances->where('checked_in_at', '!=', null)->count();
            return $event;
        });

        $eventStats = [
            'total' => $totalEvents,
            'upcoming' => $upcomingEvents,
            'this_month' => $thisMonthEvents,
            'published' => $publishedEvents,
            'completed' => $completedEvents
        ];

        if ($request->ajax()) {
            return response()->json([
                'events' => $events->items(),
                'stats' => $eventStats,
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'last_page' => $events->lastPage(),
                    'total' => $events->total()
                ]
            ]);
        }

        // Return public view for non-authenticated users
        if (!auth()->check()) {
            // For public, show only published events
            $events = Event::where('status', 'published')
                          ->with(['ministry', 'organizer', 'attendances'])
                          ->orderBy('start_datetime', 'asc')
                          ->paginate(12);
            
            // Add attendance counts to events
            $events->getCollection()->transform(function ($event) {
                $event->registered_count = $event->attendances->count();
                $event->checked_in_count = $event->attendances->where('checked_in_at', '!=', null)->count();
                return $event;
            });
            
            return view('events.public-index', compact('events', 'eventStats'));
        }

        return view('events.index', compact('events', 'eventStats'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create()
    {
        $ministries = Ministry::active()->orderBy('name')->get();
        $members = Member::active()->orderBy('first_name')->get();
        return view('events.create', compact('ministries', 'members'));
    }

    /**
     * Store a newly created event in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:service,meeting,conference,workshop,social,outreach,fundraising,other',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'is_all_day' => 'boolean',
            'location' => 'nullable|string|max:255',
            'ministry_id' => 'nullable|exists:ministries,id',
            'organizer_id' => 'nullable|exists:members,id',
            'max_attendees' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'requires_registration' => 'boolean',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'special_instructions' => 'nullable|string',
            'required_items' => 'nullable|array',
            'is_recurring' => 'boolean',
            'recurrence_type' => 'nullable|in:daily,weekly,monthly,yearly',
            'recurrence_interval' => 'nullable|integer|min:1',
            'recurrence_end_date' => 'nullable|date|after:start_datetime',
            'recurrence_days' => 'nullable|array',
            'send_reminders' => 'boolean',
            'reminder_days_before' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
            'flyer' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            'program_outline' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
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

        // Debug: Log all request data
        \Log::info('Event creation request data:', $request->all());
        
        $eventData = $request->except(['_token', '_method', 'flyer', 'program_outline']);
        $eventData['required_items'] = $request->get('required_items', []);
        $eventData['recurrence_days'] = $request->get('recurrence_days', []);
        
        // Debug: Log eventData before organizer check
        \Log::info('Event data before organizer check:', $eventData);
        
        // Handle organizer_id - set to current user if not provided or invalid
        if (isset($eventData['organizer_id'])) {
            if (empty($eventData['organizer_id']) || !is_numeric($eventData['organizer_id'])) {
                unset($eventData['organizer_id']);
            } else {
                // Verify the organizer exists in the members table
                $organizerExists = \App\Models\Member::where('id', $eventData['organizer_id'])->exists();
                if (!$organizerExists) {
                    unset($eventData['organizer_id']);
                }
            }
        }
        
        // Set organizer to current authenticated user if available and no organizer set
        if (!isset($eventData['organizer_id']) && auth()->check()) {
            // Check if current user has a member record
            $currentMember = \App\Models\Member::where('user_id', auth()->id())->first();
            if ($currentMember) {
                $eventData['organizer_id'] = $currentMember->id;
            }
        }
        
        // Debug: Log final eventData before creation
        \Log::info('Final event data before creation:', $eventData);

        // Handle flyer upload
        if ($request->hasFile('flyer')) {
            \Log::info('Flyer file detected, processing upload...');
            $flyer = $request->file('flyer');
            $flyerName = 'event_flyer_' . time() . '_' . uniqid() . '.' . $flyer->getClientOriginalExtension();
            $flyerPath = $flyer->storeAs('events/flyers', $flyerName, 'public');
            $eventData['flyer_path'] = $flyerPath;
            \Log::info('Flyer uploaded successfully', ['path' => $flyerPath]);
        } else {
            \Log::info('No flyer file in request');
        }

        // Handle program outline upload
        if ($request->hasFile('program_outline')) {
            \Log::info('Program outline file detected, processing upload...');
            $programOutline = $request->file('program_outline');
            $outlineName = 'program_outline_' . time() . '_' . uniqid() . '.pdf';
            $outlinePath = $programOutline->storeAs('events/program-outlines', $outlineName, 'public');
            $eventData['program_outline_path'] = $outlinePath;
            \Log::info('Program outline uploaded successfully', ['path' => $outlinePath]);
        } else {
            \Log::info('No program outline file in request');
        }
        
        \Log::info('Event data with files before creation:', $eventData);

        try {
            $event = Event::create($eventData);

            // Generate recurring events if needed
            if ($request->is_recurring && $request->recurrence_type) {
                $event->generateRecurringEvents();
            }

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'event' => $event->load(['ministry', 'organizer']),
                    'message' => 'Event created successfully!'
                ]);
            }

            return redirect()->route('events.show', $event)
                            ->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            \Log::error('Event creation failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create event. Please check all fields and try again.',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to create event. Please ensure all required fields are filled correctly.'])
                            ->withInput();
        }
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        $event->load(['ministry', 'organizer', 'attendances.member']);

        $eventStats = [
            'total_registered' => $event->attendances->count(),
            'confirmed_attendees' => $event->attendances->where('attendance_status', 'present')->count(),
            'pending_registrations' => $event->attendances->where('registration_status', 'pending')->count(),
            'total_revenue' => $event->attendances->where('payment_status', 'paid')->sum('amount_paid'),
        ];

        return view('events.show', compact('event', 'eventStats'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event)
    {
        $ministries = Ministry::active()->orderBy('name')->get();
        $members = Member::active()->orderBy('first_name')->get();
        return view('events.edit', compact('event', 'ministries', 'members'));
    }

    /**
     * Update the specified event in storage.
     */
    public function update(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_type' => 'required|in:service,meeting,conference,workshop,social,outreach,fundraising,other',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after:start_datetime',
            'is_all_day' => 'boolean',
            'location' => 'nullable|string|max:255',
            'ministry_id' => 'nullable|exists:ministries,id',
            'organizer_id' => 'nullable|exists:members,id',
            'max_attendees' => 'nullable|integer|min:1',
            'registration_fee' => 'nullable|numeric|min:0',
            'requires_registration' => 'boolean',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'special_instructions' => 'nullable|string',
            'required_items' => 'nullable|array',
            'status' => 'required|in:draft,published,cancelled,completed',
            'send_reminders' => 'boolean',
            'reminder_days_before' => 'nullable|integer|min:1',
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

        $eventData = $request->all();
        $eventData['required_items'] = $request->get('required_items', []);

        $event->update($eventData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'event' => $event->load(['ministry', 'organizer']),
                'message' => 'Event updated successfully!'
            ]);
        }

        return redirect()->route('events.show', $event)
                        ->with('success', 'Event updated successfully!');
    }

    /**
     * Remove the specified event from storage.
     */
    public function destroy(Event $event)
    {
        // Check if event has attendances
        if ($event->attendances()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete event with existing registrations. Please cancel the event instead.'
            ], 400);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully!'
        ]);
    }

    /**
     * Register a member for an event.
     */
    public function register(Request $request, Event $event)
    {
        $validator = Validator::make($request->all(), [
            'member_id' => 'required|exists:members,id',
            'guest_name' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
            'guest_phone' => 'nullable|string|max:20',
            'special_requirements' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if registration is still open
        if ($event->registration_deadline && now() > $event->registration_deadline) {
            return response()->json([
                'success' => false,
                'message' => 'Registration deadline has passed.'
            ], 400);
        }

        // Check if event is full
        if ($event->max_attendees && $event->attendances()->count() >= $event->max_attendees) {
            return response()->json([
                'success' => false,
                'message' => 'Event is full.'
            ], 400);
        }

        $member = Member::findOrFail($request->member_id);

        // Check if already registered
        if ($event->attendances()->where('member_id', $member->id)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Member is already registered for this event.'
            ], 400);
        }

        $attendance = $event->attendances()->create([
            'member_id' => $member->id,
            'guest_name' => $request->guest_name,
            'guest_email' => $request->guest_email,
            'guest_phone' => $request->guest_phone,
            'registration_date' => now(),
            'registration_status' => 'confirmed',
            'amount_paid' => $event->registration_fee ?? 0,
            'payment_status' => $event->registration_fee ? 'pending' : 'not_required',
            'special_requirements' => $request->special_requirements,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful!',
            'attendance' => $attendance
        ]);
    }

    /**
     * Check in a member for an event.
     */
    public function checkin(Request $request, Event $event, EventAttendance $attendance)
    {
        if ($attendance->event_id !== $event->id) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid attendance record for this event.'
            ], 400);
        }

        $attendance->checkIn();

        return response()->json([
            'success' => true,
            'message' => 'Member checked in successfully!',
            'attendance' => $attendance
        ]);
    }

    /**
     * Get event statistics.
     */
    public function statistics()
    {
        $stats = [
            'total_events' => Event::count(),
            'upcoming_events' => Event::upcoming()->count(),
            'published_events' => Event::published()->count(),
            'events_by_type' => Event::selectRaw('event_type, COUNT(*) as count')
                                   ->groupBy('event_type')
                                   ->pluck('count', 'event_type'),
            'total_attendances' => EventAttendance::count(),
        ];

        return response()->json($stats);
    }

    /**
     * Get calendar events for display.
     */
    public function calendar(Request $request)
    {
        $start = $request->get('start', now()->startOfMonth());
        $end = $request->get('end', now()->endOfMonth());

        $events = Event::published()
                      ->whereBetween('start_datetime', [$start, $end])
                      ->with(['ministry'])
                      ->get()
                      ->map(function ($event) {
                          return [
                              'id' => $event->id,
                              'title' => $event->title,
                              'start' => $event->start_datetime->toISOString(),
                              'end' => $event->end_datetime->toISOString(),
                              'allDay' => $event->is_all_day,
                              'backgroundColor' => $this->getEventColor($event->event_type),
                              'url' => route('events.show', $event),
                          ];
                      });

        return response()->json($events);
    }

    /**
     * Get color for event type.
     */
    private function getEventColor($type)
    {
        $colors = [
            'service' => '#007bff',
            'meeting' => '#28a745',
            'conference' => '#dc3545',
            'workshop' => '#ffc107',
            'social' => '#17a2b8',
            'outreach' => '#6f42c1',
            'fundraising' => '#fd7e14',
            'other' => '#6c757d',
        ];

        return $colors[$type] ?? '#6c757d';
    }
}
