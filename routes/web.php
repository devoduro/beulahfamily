<?php

// MEMBER CREATE, EDIT, UPDATE AND STORE ROUTES
Route::get('/add-member', [\App\Http\Controllers\MemberController::class, 'create'])->middleware(['auth', 'admin'])->name('members.create');
Route::post('/add-member', [\App\Http\Controllers\MemberController::class, 'store'])->middleware(['auth', 'admin'])->name('members.store');

// MEMBER EXPORT ROUTE (must be before dynamic {member} routes)
Route::get('/members/export', [\App\Http\Controllers\MemberController::class, 'export'])->middleware(['auth'])->name('members.export');

Route::get('/members/{member}/edit', [\App\Http\Controllers\MemberController::class, 'edit'])->middleware(['auth', 'admin'])->name('members.edit');
Route::put('/members/{member}', [\App\Http\Controllers\MemberController::class, 'update'])->middleware(['auth', 'admin'])->name('members.update');
Route::get('/members/{member}', [\App\Http\Controllers\MemberController::class, 'show'])->name('members.show');


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentCategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;



// Authentication Routes
Route::middleware('guest')->group(function () {
    // Admin/Staff Login
    Route::get('login', [\App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [\App\Http\Controllers\Auth\LoginController::class, 'login']);
    

    
    // Password Reset
    Route::get('password/reset', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [\App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.reset.update');
});

Route::post('logout', [\App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');




// Public Routes (accessible to everyone)
Route::get('/', function () {
    return redirect()->route('programs.index');
});

// Dashboard Routes (Role-based access)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// Admin-only Routes
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});

// Donation routes
Route::get('/donations', [\App\Http\Controllers\DonationController::class, 'index'])->name('donations.index');
Route::get('/donations/create', [\App\Http\Controllers\DonationController::class, 'create'])->name('donations.create');
Route::post('/donations/initialize', [\App\Http\Controllers\DonationController::class, 'initialize'])->name('donations.initialize');
Route::get('/donations/verify', [\App\Http\Controllers\DonationController::class, 'verify'])->name('donations.verify');
Route::get('/donations/success/{donation}', [\App\Http\Controllers\DonationController::class, 'success'])->name('donations.success');
Route::get('/donations/stats', [\App\Http\Controllers\DonationController::class, 'stats'])->name('donations.stats');
Route::get('/donations/export', [\App\Http\Controllers\DonationController::class, 'export'])->name('donations.export');
Route::get('/donations/top-donors', [\App\Http\Controllers\DonationController::class, 'topDonors'])->name('donations.top-donors');

// SMS routes
Route::middleware(['auth'])->group(function () {
    Route::get('/sms', [App\Http\Controllers\SmsController::class, 'index'])->name('sms.index');
    Route::get('/sms/create', [App\Http\Controllers\SmsController::class, 'create'])->name('sms.create');
    Route::post('/sms', [App\Http\Controllers\SmsController::class, 'store'])->name('sms.store');
    Route::get('/sms/{id}', [App\Http\Controllers\SmsController::class, 'show'])->name('sms.show');
    Route::post('/sms/{id}/cancel', [App\Http\Controllers\SmsController::class, 'cancel'])->name('sms.cancel');
    Route::get('/sms/{id}/delivery-report', [App\Http\Controllers\SmsController::class, 'deliveryReport'])->name('sms.delivery-report');
    Route::get('/sms/stats', [App\Http\Controllers\SmsController::class, 'stats'])->name('sms.stats');
    Route::post('/sms/process-scheduled', [App\Http\Controllers\SmsController::class, 'processScheduled'])->name('sms.process-scheduled');
});

// SMS Templates routes (Alternative path)
Route::middleware(['auth'])->group(function () {
    Route::get('/message-templates', [App\Http\Controllers\SmsTemplateController::class, 'index'])->name('message.templates.index');
    Route::get('/message-templates/create', [App\Http\Controllers\SmsTemplateController::class, 'create'])->name('message.templates.create');
    Route::post('/message-templates', [App\Http\Controllers\SmsTemplateController::class, 'store'])->name('message.templates.store');
    Route::get('/message-templates/{template}', [App\Http\Controllers\SmsTemplateController::class, 'show'])->name('message.templates.show');
    Route::get('/message-templates/{template}/edit', [App\Http\Controllers\SmsTemplateController::class, 'edit'])->name('message.templates.edit');
    Route::put('/message-templates/{template}', [App\Http\Controllers\SmsTemplateController::class, 'update'])->name('message.templates.update');
    Route::delete('/message-templates/{template}', [App\Http\Controllers\SmsTemplateController::class, 'destroy'])->name('message.templates.destroy');
    Route::post('/message-templates/{template}/toggle-status', [App\Http\Controllers\SmsTemplateController::class, 'toggleStatus'])->name('message.templates.toggle-status');
    Route::post('/message-templates/{template}/duplicate', [App\Http\Controllers\SmsTemplateController::class, 'duplicate'])->name('message.templates.duplicate');
    Route::post('/message-templates/{template}/preview', [App\Http\Controllers\SmsTemplateController::class, 'preview'])->name('message.templates.preview');
});

// SMS Templates routes (Original path - keep for backward compatibility)
Route::prefix('sms/templates')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\SmsTemplateController::class, 'index'])->name('sms.templates.index');
    Route::get('/create', [App\Http\Controllers\SmsTemplateController::class, 'create'])->name('sms.templates.create');
    Route::post('/', [App\Http\Controllers\SmsTemplateController::class, 'store'])->name('sms.templates.store');
    Route::get('/{template}', [App\Http\Controllers\SmsTemplateController::class, 'show'])->name('sms.templates.show');
    Route::get('/{template}/edit', [App\Http\Controllers\SmsTemplateController::class, 'edit'])->name('sms.templates.edit');
    Route::put('/{template}', [App\Http\Controllers\SmsTemplateController::class, 'update'])->name('sms.templates.update');
    Route::delete('/{template}', [App\Http\Controllers\SmsTemplateController::class, 'destroy'])->name('sms.templates.destroy');
    Route::post('/{template}/toggle-status', [App\Http\Controllers\SmsTemplateController::class, 'toggleStatus'])->name('sms.templates.toggle-status');
    Route::post('/{template}/duplicate', [App\Http\Controllers\SmsTemplateController::class, 'duplicate'])->name('sms.templates.duplicate');
    Route::post('/{template}/preview', [App\Http\Controllers\SmsTemplateController::class, 'preview'])->name('sms.templates.preview');
});

// SMS Credits routes (Alternative path)
Route::middleware(['auth'])->group(function () {
    Route::get('/message-credits', [App\Http\Controllers\SmsCreditController::class, 'index'])->name('message.credits.index');
    Route::get('/message-credits/purchase', [App\Http\Controllers\SmsCreditController::class, 'purchase'])->name('message.credits.purchase');
    Route::post('/message-credits/initialize', [App\Http\Controllers\SmsCreditController::class, 'initialize'])->name('message.credits.initialize');
    Route::get('/message-credits/verify', [App\Http\Controllers\SmsCreditController::class, 'verify'])->name('message.credits.verify');
    Route::get('/message-credits/transactions', [App\Http\Controllers\SmsCreditController::class, 'transactions'])->name('message.credits.transactions');
    Route::get('/message-credits/balance', [App\Http\Controllers\SmsCreditController::class, 'balance'])->name('message.credits.balance');
    Route::post('/message-credits/add-credits', [App\Http\Controllers\SmsCreditController::class, 'addCredits'])->middleware(['admin'])->name('message.credits.add');
});

// SMS Credits routes (Original path - keep for backward compatibility)
Route::prefix('sms/credits')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\SmsCreditController::class, 'index'])->name('sms.credits.index');
    Route::get('/purchase', [App\Http\Controllers\SmsCreditController::class, 'purchase'])->name('sms.credits.purchase');
    Route::post('/initialize', [App\Http\Controllers\SmsCreditController::class, 'initialize'])->name('sms.credits.initialize');
    Route::get('/verify', [App\Http\Controllers\SmsCreditController::class, 'verify'])->name('sms.credits.verify');
    Route::get('/transactions', [App\Http\Controllers\SmsCreditController::class, 'transactions'])->name('sms.credits.transactions');
    Route::get('/balance', [App\Http\Controllers\SmsCreditController::class, 'balance'])->name('sms.credits.balance');
    Route::post('/add-credits', [App\Http\Controllers\SmsCreditController::class, 'addCredits'])->middleware(['admin'])->name('sms.credits.add');
});

// Finance Management Routes
Route::prefix('finance')->name('finance.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\FinanceController::class, 'index'])->name('index');
    Route::get('/transactions', [App\Http\Controllers\FinanceController::class, 'transactions'])->name('transactions');
    Route::get('/create', [App\Http\Controllers\FinanceController::class, 'create'])->name('create');
    Route::post('/store', [App\Http\Controllers\FinanceController::class, 'store'])->name('store');
    Route::get('/{transaction}', [App\Http\Controllers\FinanceController::class, 'show'])->name('show');
    Route::post('/{transaction}/approve', [App\Http\Controllers\FinanceController::class, 'approve'])->name('approve');
    Route::post('/{transaction}/reject', [App\Http\Controllers\FinanceController::class, 'reject'])->name('reject');
    
    // Finance Categories Routes
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [App\Http\Controllers\FinanceCategoryController::class, 'index'])->name('index');
        Route::get('/create', [App\Http\Controllers\FinanceCategoryController::class, 'create'])->name('create');
        Route::post('/', [App\Http\Controllers\FinanceCategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [App\Http\Controllers\FinanceCategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [App\Http\Controllers\FinanceCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [App\Http\Controllers\FinanceCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [App\Http\Controllers\FinanceCategoryController::class, 'destroy'])->name('destroy');
        Route::patch('/{category}/toggle', [App\Http\Controllers\FinanceCategoryController::class, 'toggle'])->name('toggle');
    });
});

// Member Payments Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/member-payments', [App\Http\Controllers\MemberPaymentController::class, 'index'])->name('member-payments.index');
    Route::get('/member-payments/create', [App\Http\Controllers\MemberPaymentController::class, 'create'])->name('member-payments.create');
    Route::post('/member-payments', [App\Http\Controllers\MemberPaymentController::class, 'store'])->name('member-payments.store');
    Route::get('/member-payments/{payment}', [App\Http\Controllers\MemberPaymentController::class, 'show'])->name('member-payments.show');
    Route::get('/member-payments/{payment}/edit', [App\Http\Controllers\MemberPaymentController::class, 'edit'])->name('member-payments.edit');
    Route::put('/member-payments/{payment}', [App\Http\Controllers\MemberPaymentController::class, 'update'])->name('member-payments.update');
    Route::delete('/member-payments/{payment}', [App\Http\Controllers\MemberPaymentController::class, 'destroy'])->name('member-payments.destroy');
    Route::post('/member-payments/{payment}/resend-sms', [App\Http\Controllers\MemberPaymentController::class, 'resendSms'])->name('member-payments.resend-sms');
    Route::get('/member-payments-export', [App\Http\Controllers\MemberPaymentController::class, 'export'])->name('member-payments.export');
});

// System Configuration Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/system-config', [App\Http\Controllers\GatewaySettingController::class, 'index'])->name('system.config.index');
    Route::put('/system-config/{gateway}', [App\Http\Controllers\GatewaySettingController::class, 'update'])->name('system.config.update');
    Route::post('/system-config/{gateway}/test', [App\Http\Controllers\GatewaySettingController::class, 'test'])->name('system.config.test');
});

// Attendance Routes
Route::prefix('attendance')->name('attendance.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\AttendanceController::class, 'index'])->name('index');
    Route::get('/statistics', [App\Http\Controllers\AttendanceController::class, 'statistics'])->name('statistics');
    Route::get('/export', [App\Http\Controllers\AttendanceController::class, 'export'])->name('export');
    
    // Event-specific attendance routes
    Route::get('/event/{event}', [App\Http\Controllers\AttendanceController::class, 'show'])->name('show');
    Route::post('/event/{event}/manual', [App\Http\Controllers\AttendanceController::class, 'manualEntry'])->name('manual-entry');
    Route::post('/event/{event}/bulk', [App\Http\Controllers\AttendanceController::class, 'bulkEntry'])->name('bulk-entry');
    
    // QR Code management routes (admin only)
    Route::post('/event/{event}/qr/generate', [App\Http\Controllers\AttendanceController::class, 'generateQr'])->name('qr.generate');
    Route::post('/qr/{qrCode}/deactivate', [App\Http\Controllers\AttendanceController::class, 'deactivateQr'])->name('qr.deactivate');
    
    // Attendance marking routes
    Route::post('/checkout/{attendance}', [App\Http\Controllers\AttendanceController::class, 'checkOut'])->name('checkout');
});

// Public attendance scanning routes (no auth required)
Route::get('/scan/{token}', [App\Http\Controllers\AttendanceController::class, 'scan'])->name('attendance.scan');
Route::post('/mark-attendance', [App\Http\Controllers\AttendanceController::class, 'markAttendance'])->name('attendance.mark');

// Public QR code display (no auth required for members to view QR codes)
Route::get('/attendance/event/{event}/qr', [App\Http\Controllers\AttendanceController::class, 'showQr'])->name('attendance.qr.show');
Route::get('/attendance/qr-display/{qrCode}', [App\Http\Controllers\AttendanceController::class, 'displayQr'])->name('attendance.qr-display');


// Program Registration Routes (Public Access)
Route::get('/programs', [App\Http\Controllers\ProgramRegistrationController::class, 'programs'])->name('programs.index');
Route::get('/programs/{program}', [App\Http\Controllers\ProgramRegistrationController::class, 'show'])->name('programs.show');
Route::get('/programs/{program}/register', [App\Http\Controllers\ProgramRegistrationController::class, 'create'])->name('programs.register');
Route::post('/programs/{program}/register', [App\Http\Controllers\ProgramRegistrationController::class, 'store'])->name('programs.register.store');
Route::get('/programs/{program}/registration/{registration}/success', [App\Http\Controllers\ProgramRegistrationController::class, 'success'])->name('programs.registration.success');
Route::get('/programs/{program}/registration/{registration}', [App\Http\Controllers\ProgramRegistrationController::class, 'showRegistration'])->name('programs.registration.show');

// File download routes for program registrations
Route::get('/programs/files/{registration}/{filename}', [App\Http\Controllers\ProgramRegistrationController::class, 'downloadFile'])
    ->name('programs.files.download')
    ->where('filename', '.*');
Route::get('/programs/{program}/registration/{registration}/edit', [App\Http\Controllers\ProgramRegistrationController::class, 'edit'])->name('programs.registration.edit');
Route::put('/programs/{program}/registration/{registration}', [App\Http\Controllers\ProgramRegistrationController::class, 'update'])->name('programs.registration.update');
Route::post('/programs/{program}/registration/{registration}/cancel', [App\Http\Controllers\ProgramRegistrationController::class, 'cancel'])->name('programs.registration.cancel');
Route::get('/programs/{program}/registration/{registration}/file/{fileIndex}', [App\Http\Controllers\ProgramRegistrationController::class, 'downloadFile'])->name('programs.registration.file.download');

// Member Authentication Routes
Route::prefix('member')->name('member.')->group(function () {
    // Guest routes
    Route::middleware('guest:member')->group(function () {
        Route::get('/login', [App\Http\Controllers\MemberAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [App\Http\Controllers\MemberAuthController::class, 'login']);
    });

    // Authenticated member routes
    Route::middleware('auth:member')->group(function () {
        Route::post('/logout', [App\Http\Controllers\MemberAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [App\Http\Controllers\MemberAuthController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [App\Http\Controllers\MemberAuthController::class, 'profile'])->name('profile');
        Route::put('/profile', [App\Http\Controllers\MemberAuthController::class, 'updateProfile'])->name('profile.update');
        Route::post('/password/change', [App\Http\Controllers\MemberAuthController::class, 'changePassword'])->name('password.change');
        
        // Member portal routes
        Route::get('/donations', function () { return view('member.donations.index'); })->name('donations.index');
        Route::get('/donations/create', function () { return view('member.donations.create'); })->name('donations.create');
        Route::get('/events', function () { return view('member.events.index'); })->name('events.index');
        Route::get('/events/{event}', function () { return view('member.events.show'); })->name('events.show');
        Route::get('/ministries', function () { return view('member.ministries.index'); })->name('ministries.index');
        
        // Family management routes
        Route::get('/family', [App\Http\Controllers\Member\MemberFamilyController::class, 'index'])->name('family.index');
        Route::get('/family/create', [App\Http\Controllers\Member\MemberFamilyController::class, 'create'])->name('family.create');
        Route::post('/family', [App\Http\Controllers\Member\MemberFamilyController::class, 'store'])->name('family.store');
        Route::get('/family/member/{familyMember}', [App\Http\Controllers\Member\MemberFamilyController::class, 'showMember'])->name('family.show-member');
        Route::get('/family/export', [App\Http\Controllers\Member\MemberFamilyController::class, 'exportFamily'])->name('family.export');
        
        // Testimonies routes
        Route::resource('testimonies', App\Http\Controllers\Member\TestimonyController::class);
        Route::get('/my-testimonies', [App\Http\Controllers\Member\TestimonyController::class, 'myTestimonies'])->name('testimonies.my-testimonies');
        
        // Prayer Requests routes
        Route::resource('prayer-requests', App\Http\Controllers\Member\PrayerRequestController::class);
        Route::get('/my-prayer-requests', [App\Http\Controllers\Member\PrayerRequestController::class, 'myRequests'])->name('prayer-requests.my-requests');
        Route::post('/prayer-requests/{prayerRequest}/pray', [App\Http\Controllers\Member\PrayerRequestController::class, 'pray'])->name('prayer-requests.pray');
        Route::post('/prayer-requests/{prayerRequest}/mark-answered', [App\Http\Controllers\Member\PrayerRequestController::class, 'markAnswered'])->name('prayer-requests.mark-answered');
        
        Route::get('/settings', function () { return view('member.settings.index'); })->name('settings.index');
        Route::get('/help', function () { return view('member.help.index'); })->name('help.index');
    });
});

// Protected Routes
Route::middleware(['auth'])->group(function () {
    // Password Change Routes
    Route::get('/password/change', [\App\Http\Controllers\PasswordController::class, 'edit'])->name('password.change');
    Route::put('/password/update', [\App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

    
    // User Profile
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    
    // Church Management Routes (Available to all authenticated users)
    
    // Members - View access for all users
    Route::get('/members', [\App\Http\Controllers\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/statistics', [\App\Http\Controllers\MemberController::class, 'statistics'])->name('members.statistics');
    
    // Events - View and register access for all users
    Route::get('/events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index');
    Route::get('/events/create', [\App\Http\Controllers\EventController::class, 'create'])->name('events.create');
    Route::post('/events', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store');
    Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [\App\Http\Controllers\EventController::class, 'register'])->name('events.register');
    
    // Simple event creation routes
    Route::get('/create-event', function() {
        $ministries = \App\Models\Ministry::orderBy('name')->get();
        return view('events.create', compact('ministries'));
    })->name('create.event');
    
    Route::post('/create-event', function(\Illuminate\Http\Request $request) {
        try {
            // Basic validation
            $request->validate([
                'title' => 'required|string|max:255',
                'event_type' => 'required|string',
                'start_datetime' => 'required|date|after:now',
                'end_datetime' => 'nullable|date|after:start_datetime',
                'location' => 'nullable|string|max:255',
                'description' => 'nullable|string|max:1000',
                'ministry_id' => 'nullable|exists:ministries,id',
                'status' => 'required|in:draft,published',
            ]);
            
            $event = \App\Models\Event::create([
                'title' => $request->title,
                'description' => $request->description,
                'event_type' => $request->event_type,
                'start_datetime' => $request->start_datetime,
                'end_datetime' => $request->end_datetime,
                'location' => $request->location,
                'ministry_id' => $request->ministry_id ?: null,
                'organizer_id' => auth()->id() ?: 1, // Use authenticated user or default
                'status' => $request->status, // User-selected status: draft or published
                'requires_registration' => false,
                'is_all_day' => false,
            ]);
            
            return redirect('/events')->with('success', 'Event "' . $event->title . '" created successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        } catch (\Exception $e) {
            \Log::error('Event creation failed: ' . $e->getMessage());
            return back()->with('error', 'Error creating event. Please try again.')->withInput();
        }
    })->name('store.event');
    
    // Ministries - View access for all users
    Route::get('/ministries', [\App\Http\Controllers\MinistryController::class, 'index'])->name('ministries.index');
    Route::get('/ministries/{ministry}', [\App\Http\Controllers\MinistryController::class, 'show'])->name('ministries.show');
    
    // Ministry creation - accessible to all authenticated users
    Route::get('/new-ministry', [\App\Http\Controllers\MinistryController::class, 'create'])->name('ministries.create.public');
    Route::post('/new-ministry', [\App\Http\Controllers\MinistryController::class, 'store'])->name('ministries.store.public');
    
    // Ministry member management - accessible to all authenticated users
    Route::get('/ministries/{ministry}/members', [\App\Http\Controllers\MinistryController::class, 'manageMembers'])->name('ministries.members.manage');
    Route::post('/ministries/{ministry}/members', [\App\Http\Controllers\MinistryController::class, 'addMember'])->name('ministries.members.add');
    Route::delete('/ministries/{ministry}/members/{member}', [\App\Http\Controllers\MinistryController::class, 'removeMember'])->name('ministries.members.remove');
    Route::patch('/ministries/{ministry}/members/{member}', [\App\Http\Controllers\MinistryController::class, 'updateMemberRole'])->name('ministries.members.update');
    
    // Families - View access for all users
    Route::get('/families', [\App\Http\Controllers\FamilyController::class, 'index'])->name('families.index');
    Route::get('/families/{family}', [\App\Http\Controllers\FamilyController::class, 'show'])->name('families.show');
    
    // Announcements - View access for all users
    Route::get('/announcements', [\App\Http\Controllers\AnnouncementController::class, 'index'])->name('announcements.index');
    Route::get('/announcements/{announcement}', [\App\Http\Controllers\AnnouncementController::class, 'show'])->name('announcements.show');
    
    // User Dashboard and Portal Routes
    Route::get('/user/dashboard', [UserController::class, 'dashboard'])->name('users.dashboard');
    Route::get('/portal', [UserController::class, 'portal'])->name('users.portal');
    Route::get('/portal/documents', [UserController::class, 'portalDocuments'])->name('users.portal.documents');
    Route::get('/portal/bulk-upload', [UserController::class, 'bulkUploadForm'])->name('users.portal.bulk-upload');
    Route::post('/portal/bulk-upload', [UserController::class, 'bulkUploadStore'])->name('users.portal.bulk-upload.store');
    
    // Document Access Routes (for all authenticated users)
    Route::get('/documents/{document}/view', [DocumentController::class, 'view'])->name('documents.view');
    Route::get('/documents/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
    Route::post('/documents/{document}/print', [DocumentController::class, 'print'])->name('documents.print');
    
    // API Routes for AJAX requests
    Route::prefix('api')->group(function () {
        Route::get('/documents/{document}/preview', [DocumentController::class, 'preview'])->name('api.documents.preview');
        Route::get('/categories', [DocumentCategoryController::class, 'apiIndex'])->name('api.categories.index');
        Route::get('/members/statistics', [\App\Http\Controllers\MemberController::class, 'statistics'])->name('api.members.statistics');
    });
    
    // Old problematic route - removed

    // Admin Only Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');

        // Church Management - Admin Only Routes
        
        // Members Management (Admin Only) - Edit/Update routes moved to top level
        Route::delete('/members/{member}', [\App\Http\Controllers\MemberController::class, 'destroy'])->name('members.destroy');
        Route::get('/members/{member}/id-card', [\App\Http\Controllers\MemberController::class, 'generateIdCard'])->name('members.id-card');
        
        // Families Management (Admin Only)
        Route::resource('families', \App\Http\Controllers\FamilyController::class)->except(['index', 'show']);
        Route::post('families/bulk-action', [\App\Http\Controllers\FamilyController::class, 'bulkAction'])->name('families.bulk-action');
        Route::get('families/export', [\App\Http\Controllers\FamilyController::class, 'export'])->name('families.export');
        
        // Alternative family creation route
        Route::get('family/new', [\App\Http\Controllers\FamilyController::class, 'create'])->name('family.new');
        Route::post('family/new', [\App\Http\Controllers\FamilyController::class, 'store'])->name('family.store');
        
        // Family member management API routes
        Route::get('/api/families/{family}/members', [\App\Http\Controllers\FamilyController::class, 'getFamilyMembers']);
        Route::post('/api/families/{family}/members', [\App\Http\Controllers\FamilyController::class, 'addMemberToFamily']);
        Route::delete('/api/families/{family}/members/{member}', [\App\Http\Controllers\FamilyController::class, 'removeMemberFromFamily']);
        Route::get('/api/members/available', [\App\Http\Controllers\FamilyController::class, 'getAvailableMembers']);
        
        // Ministries Management (Admin Only)
        Route::resource('ministries', \App\Http\Controllers\MinistryController::class)->except(['index', 'show']);
        
        // Events Management (Admin Only)
        Route::get('/admin/events/create', [\App\Http\Controllers\EventController::class, 'create'])->name('admin.events.create');
        Route::post('/admin/events', [\App\Http\Controllers\EventController::class, 'store'])->name('admin.events.store');
        Route::get('/events/{event}/edit', [\App\Http\Controllers\EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy');
        Route::post('/events/{event}/check-in/{attendance}', [\App\Http\Controllers\EventController::class, 'checkIn'])->name('events.check-in');
        
        // Donations Management (Admin Only)
        Route::resource('donations', \App\Http\Controllers\DonationController::class);
        Route::post('/donations/{donation}/send-receipt', [\App\Http\Controllers\DonationController::class, 'sendReceipt'])->name('donations.send-receipt');
        Route::post('/donations/{donation}/generate-receipt', [\App\Http\Controllers\DonationController::class, 'generateReceipt'])->name('donations.generate-receipt');
        Route::get('/donations/{donation}/receipt/download', [\App\Http\Controllers\DonationController::class, 'downloadReceipt'])->name('donations.receipt.download');
        
        // Announcements Management (Admin Only)
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->except(['index', 'show']);
        
        // Testimonies Management (Admin Only)
        Route::prefix('admin/testimonies')->name('admin.testimonies.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\TestimonyController::class, 'index'])->name('index');
            Route::get('/{testimony}', [\App\Http\Controllers\Admin\TestimonyController::class, 'show'])->name('show');
            Route::post('/{testimony}/approve', [\App\Http\Controllers\Admin\TestimonyController::class, 'approve'])->name('approve');
            Route::post('/{testimony}/reject', [\App\Http\Controllers\Admin\TestimonyController::class, 'reject'])->name('reject');
            Route::delete('/{testimony}', [\App\Http\Controllers\Admin\TestimonyController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [\App\Http\Controllers\Admin\TestimonyController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\TestimonyController::class, 'export'])->name('export');
        });
        
        // Prayer Requests Management (Admin Only)
        Route::prefix('admin/prayer-requests')->name('admin.prayer-requests.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'index'])->name('index');
            Route::get('/{prayerRequest}', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'show'])->name('show');
            Route::post('/{prayerRequest}/mark-answered', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'markAnswered'])->name('mark-answered');
            Route::post('/{prayerRequest}/reopen', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'reopen'])->name('reopen');
            Route::post('/{prayerRequest}/close', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'close'])->name('close');
            Route::delete('/{prayerRequest}', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'destroy'])->name('destroy');
            Route::post('/bulk-action', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'bulkAction'])->name('bulk-action');
            Route::get('/export/csv', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'export'])->name('export');
            Route::get('/statistics/overview', [\App\Http\Controllers\Admin\PrayerRequestController::class, 'statistics'])->name('statistics');
        });

        // Admin Program Management Routes
        Route::prefix('admin/programs')->name('admin.programs.')->middleware(['auth', 'admin'])->group(function () {
            Route::get('/', [App\Http\Controllers\ProgramController::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\ProgramController::class, 'create'])->name('create');
            Route::post('/', [App\Http\Controllers\ProgramController::class, 'store'])->name('store');
            Route::get('/{program}', [App\Http\Controllers\ProgramController::class, 'show'])->name('show');
            Route::get('/{program}/edit', [App\Http\Controllers\ProgramController::class, 'edit'])->name('edit');
            Route::put('/{program}', [App\Http\Controllers\ProgramController::class, 'update'])->name('update');
            Route::delete('/{program}', [App\Http\Controllers\ProgramController::class, 'destroy'])->name('destroy');
            
            // Program registrations management
            Route::get('/{program}/registrations', [App\Http\Controllers\ProgramController::class, 'registrations'])->name('registrations');
            Route::get('/{program}/registrations/export', [App\Http\Controllers\ProgramController::class, 'exportRegistrations'])->name('registrations.export');
            Route::post('/{program}/registrations/bulk-update', [App\Http\Controllers\ProgramController::class, 'bulkUpdateRegistrations'])->name('registrations.bulk-update');
            Route::get('/{program}/registrations/{registration}', [App\Http\Controllers\ProgramController::class, 'showRegistration'])->name('registrations.show');
            
            // Registration status updates
            Route::patch('/{program}/registrations/{registration}/status', [App\Http\Controllers\ProgramRegistrationController::class, 'updateStatus'])->name('registrations.update-status');
        });

        // Document Categories Management (Admin Only)
        Route::resource('document-categories', DocumentCategoryController::class);
        Route::patch('/document-categories/{documentCategory}/toggle-status', [DocumentCategoryController::class, 'toggleStatus'])->name('document-categories.toggle-status');

        // Documents Management (Admin Only)
        Route::resource('documents', DocumentController::class);
        Route::get('/documents/bulk/create', [DocumentController::class, 'bulkCreate'])->name('documents.bulk.create');
        Route::post('/documents/bulk/store', [DocumentController::class, 'bulkStore'])->name('documents.bulk.store');
        Route::patch('/documents/{document}/toggle-status', [DocumentController::class, 'toggleStatus'])->name('documents.toggle-status');
        
        // Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.general');
        Route::put('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');

        // Activity Logs & Security
        Route::prefix('security')->name('security.')->group(function () {
            Route::get('/dashboard', [ActivityLogController::class, 'dashboard'])->name('dashboard');
            Route::get('/logs', [ActivityLogController::class, 'index'])->name('logs.index');
            Route::get('/logs/{log}', [ActivityLogController::class, 'show'])->name('logs.show');
            Route::post('/logs/cleanup', [ActivityLogController::class, 'cleanup'])->name('logs.cleanup');
        });
               
        // Database Backup
        Route::get('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
        Route::post('/settings/backup/create', function(\Illuminate\Http\Request $request) {
            \Log::info('Route debug info:', [
                'method' => $request->method(),
                'url' => $request->url(),
                'path' => $request->path(),
                'ajax' => $request->ajax(),
                'headers' => $request->headers->all(),
                'middleware' => Route::current()->middleware()
            ]);
            return app()->call([app(SettingController::class), 'backupDatabase'], ['request' => $request]);
        })->name('settings.backup.create');
        Route::get('/settings/backup/{filename}/download', [SettingController::class, 'downloadBackup'])->name('settings.backup.download');
        Route::delete('/settings/backup/{filename}', [SettingController::class, 'destroyBackup'])->name('settings.backup.destroy');
        
        
        
        // SMS Configuration
        Route::get('/sms-config', [\App\Http\Controllers\Admin\SmsConfigController::class, 'index'])->name('admin.sms-config');
        Route::get('/sms-config/test', [\App\Http\Controllers\Admin\SmsConfigController::class, 'test'])->name('admin.sms-config.test');
    });
});
