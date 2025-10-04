<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Event;
use App\Models\EventQrCode;
use App\Models\Member;
use App\Services\QrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $qrCodeService;

    public function __construct(QrCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    /**
     * Display attendance dashboard
     */
    public function index(Request $request)
    {
        $events = Event::with(['attendances.member'])
                      ->where('start_datetime', '>=', now()->subDays(30))
                      ->orderBy('start_datetime', 'desc')
                      ->paginate(10);

        $stats = [
            'today_attendance' => Attendance::today()->verified()->count(),
            'this_week_attendance' => Attendance::thisWeek()->verified()->count(),
            'this_month_attendance' => Attendance::thisMonth()->verified()->count(),
            'total_events_with_qr' => EventQrCode::active()->count(),
        ];

        return view('attendance.index', compact('events', 'stats'));
    }

    /**
     * Show event attendance details
     */
    public function show(Event $event)
    {
        $event->load(['attendances.member', 'qrCodes']);
        
        $attendanceStats = Attendance::getEventStats($event->id);
        
        $attendances = $event->attendances()
                           ->with('member')
                           ->orderBy('checked_in_at', 'desc')
                           ->get();

        return view('attendance.show', compact('event', 'attendances', 'attendanceStats'));
    }

    /**
     * Generate QR code for event
     */
    public function generateQr(Event $event, Request $request)
    {
        try {
            $expirationHours = $request->get('expiration_hours', 24);
            
            $eventQr = $this->qrCodeService->generateEventQrCode($event, $expirationHours);

            return response()->json([
                'success' => true,
                'message' => 'QR code generated successfully',
                'qr_code' => [
                    'token' => $eventQr->qr_code_token,
                    'url' => $eventQr->qr_url,
                    'image_path' => asset('storage/' . $eventQr->qr_code_path),
                    'expires_at' => $eventQr->expires_at?->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('QR Code generation failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate QR code'
            ], 500);
        }
    }

    /**
     * Display QR code for event (public access)
     */
    public function showQr(Event $event, Request $request)
    {
        $eventQr = EventQrCode::where('event_id', $event->id)
                             ->active()
                             ->first();

        // Only generate QR if user is authenticated (admin function)
        if (!$eventQr && auth()->check()) {
            $eventQr = $this->qrCodeService->generateEventQrCode($event);
        }

        // Check if this is a print request
        if ($request->has('print') || $request->has('autoprint')) {
            return view('attendance.qr-print', compact('event', 'eventQr'));
        }

        // Use public view for unauthenticated users
        if (!auth()->check()) {
            return view('attendance.qr-public', compact('event', 'eventQr'));
        }

        return view('attendance.qr-display', compact('event', 'eventQr'));
    }

    /**
     * Handle QR code scan for attendance
     */
    public function scan($token, Request $request)
    {
        $validation = $this->qrCodeService->validateToken($token);

        if (!$validation['valid']) {
            return view('attendance.scan-error', [
                'message' => $validation['message']
            ]);
        }

        $eventQr = $validation['event_qr'];
        $event = $validation['event'];

        // Log the scan attempt
        $eventQr->logScan(null, true);

        return view('attendance.scan-form-public', compact('event', 'eventQr', 'token'));
    }

    /**
     * Process attendance marking from QR scan
     */
    public function markAttendance(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'member_id' => 'required|exists:members,id'
        ]);

        try {
            DB::beginTransaction();

            $validation = $this->qrCodeService->validateToken($request->token);

            if (!$validation['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validation['message']
                ], 400);
            }

            $event = $validation['event'];
            $member = Member::findOrFail($request->member_id);

            // Check if member already has attendance for this event
            $existingAttendance = Attendance::where('event_id', $event->id)
                                          ->where('member_id', $member->id)
                                          ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance already marked for this event'
                ], 400);
            }

            // Create attendance record
            $attendance = Attendance::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'checked_in_at' => now(),
                'attendance_method' => 'qr_code',
                'device_info' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'is_verified' => true
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Attendance marked successfully',
                'attendance' => [
                    'member_name' => $member->full_name,
                    'event_title' => $event->title,
                    'checked_in_at' => $attendance->checked_in_at->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Attendance marking failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to mark attendance'
            ], 500);
        }
    }

    /**
     * Manual attendance entry
     */
    public function manualEntry(Event $event, Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'checked_in_at' => 'nullable|date',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $member = Member::findOrFail($request->member_id);

            // Check for existing attendance
            $existingAttendance = Attendance::where('event_id', $event->id)
                                          ->where('member_id', $member->id)
                                          ->first();

            if ($existingAttendance) {
                return response()->json([
                    'success' => false,
                    'message' => 'Attendance already exists for this member'
                ], 400);
            }

            $attendance = Attendance::create([
                'event_id' => $event->id,
                'member_id' => $member->id,
                'checked_in_at' => $request->checked_in_at ?? now(),
                'attendance_method' => 'manual',
                'notes' => $request->notes,
                'ip_address' => $request->ip(),
                'is_verified' => true
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Manual attendance recorded successfully',
                'attendance' => $attendance->load('member')
            ]);

        } catch (\Exception $e) {
            Log::error('Manual attendance entry failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to record attendance'
            ], 500);
        }
    }

    /**
     * Bulk attendance entry
     */
    public function bulkEntry(Event $event, Request $request)
    {
        $request->validate([
            'member_ids' => 'required|array|min:1',
            'member_ids.*' => 'exists:members,id',
            'checked_in_at' => 'nullable|date',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            DB::beginTransaction();

            $memberIds = $request->member_ids;
            $checkedInAt = $request->checked_in_at ?? now();
            $notes = $request->notes;
            $successCount = 0;
            $skippedCount = 0;
            $errors = [];

            foreach ($memberIds as $memberId) {
                // Check for existing attendance
                $existingAttendance = Attendance::where('event_id', $event->id)
                                              ->where('member_id', $memberId)
                                              ->first();

                if ($existingAttendance) {
                    $member = Member::find($memberId);
                    $skippedCount++;
                    $errors[] = "Skipped {$member->full_name} - already has attendance";
                    continue;
                }

                Attendance::create([
                    'event_id' => $event->id,
                    'member_id' => $memberId,
                    'checked_in_at' => $checkedInAt,
                    'attendance_method' => 'manual_bulk',
                    'notes' => $notes,
                    'ip_address' => $request->ip(),
                    'is_verified' => true
                ]);

                $successCount++;
            }

            DB::commit();

            $message = "Successfully added {$successCount} attendance records";
            if ($skippedCount > 0) {
                $message .= ", skipped {$skippedCount} duplicates";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'details' => [
                    'success_count' => $successCount,
                    'skipped_count' => $skippedCount,
                    'errors' => $errors
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Bulk attendance entry failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to record bulk attendance'
            ], 500);
        }
    }

    /**
     * Check out member
     */
    public function checkOut(Attendance $attendance, Request $request)
    {
        if ($attendance->checked_out_at) {
            return response()->json([
                'success' => false,
                'message' => 'Member already checked out'
            ], 400);
        }

        $attendance->checkOut();

        return response()->json([
            'success' => true,
            'message' => 'Member checked out successfully',
            'checked_out_at' => $attendance->checked_out_at->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * Get attendance statistics
     */
    public function statistics(Request $request)
    {
        $eventId = $request->get('event_id');
        $dateFrom = $request->get('date_from', now()->subMonths(3)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        // Base query for verified attendance
        $baseQuery = Attendance::verified();
        
        // Apply date range filter
        $query = clone $baseQuery;
        $query->whereBetween('checked_in_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

        if ($eventId) {
            $query->where('event_id', $eventId);
        }

        // Get method statistics
        $methodStats = (clone $query)->selectRaw('attendance_method, COUNT(*) as count')
                                    ->groupBy('attendance_method')
                                    ->pluck('count', 'attendance_method')
                                    ->toArray();

        // Get date statistics
        $dateStats = (clone $query)->selectRaw('DATE(checked_in_at) as date, COUNT(*) as count')
                                  ->groupBy('date')
                                  ->orderBy('date')
                                  ->pluck('count', 'date')
                                  ->toArray();

        // Count events in the same period
        $eventCount = Event::whereBetween('start_datetime', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])->count();

        $totalAttendance = $query->count();
        $uniqueMembers = $query->distinct('member_id')->count();

        $stats = [
            'total_attendance' => $totalAttendance,
            'unique_members' => $uniqueMembers,
            'average_per_event' => $eventCount > 0 ? round($totalAttendance / $eventCount, 2) : 0,
            'by_method' => $methodStats,
            'by_date' => $dateStats,
            'date_range' => [
                'from' => $dateFrom,
                'to' => $dateTo
            ],
            'debug_info' => [
                'total_attendance_all_time' => Attendance::verified()->count(),
                'event_count_in_range' => $eventCount,
                'query_date_range' => [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        // This would typically use Laravel Excel or similar
        return response()->json([
            'message' => 'Export functionality will be implemented with Laravel Excel package'
        ]);
    }

    /**
     * Deactivate QR code
     */
    public function deactivateQr(EventQrCode $qrCode)
    {
        $qrCode->deactivate();

        return response()->json([
            'success' => true,
            'message' => 'QR code deactivated successfully'
        ]);
    }
}
