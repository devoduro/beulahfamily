<?php

// MEMBER CREATE, EDIT, UPDATE AND STORE ROUTES
Route::get('/add-member', [\App\Http\Controllers\MemberController::class, 'create'])->middleware(['auth', 'admin'])->name('members.create');
Route::post('/add-member', [\App\Http\Controllers\MemberController::class, 'store'])->middleware(['auth', 'admin'])->name('members.store');
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




// Guest Routes
Route::middleware(['guest'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
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
Route::get('/donations', [DonationController::class, 'index'])->name('donations.index');
Route::get('/donations/create', [DonationController::class, 'create'])->name('donations.create');
Route::post('/donations/initialize', [DonationController::class, 'initialize'])->name('donations.initialize');
Route::get('/donations/verify', [DonationController::class, 'verify'])->name('donations.verify');
Route::get('/donations/success/{donation}', [DonationController::class, 'success'])->name('donations.success');
Route::get('/donations/stats', [DonationController::class, 'stats'])->name('donations.stats');
Route::get('/donations/export', [DonationController::class, 'export'])->name('donations.export');
Route::get('/donations/top-donors', [DonationController::class, 'topDonors'])->name('donations.top-donors');

// SMS routes
Route::get('/sms', [App\Http\Controllers\SmsController::class, 'index'])->name('sms.index');
Route::get('/sms/create', [App\Http\Controllers\SmsController::class, 'create'])->name('sms.create');
Route::post('/sms', [App\Http\Controllers\SmsController::class, 'store'])->name('sms.store');
Route::get('/sms/{id}', [App\Http\Controllers\SmsController::class, 'show'])->name('sms.show');
Route::post('/sms/{id}/cancel', [App\Http\Controllers\SmsController::class, 'cancel'])->name('sms.cancel');
Route::get('/sms/{id}/delivery-report', [App\Http\Controllers\SmsController::class, 'deliveryReport'])->name('sms.delivery-report');
Route::get('/sms/stats', [App\Http\Controllers\SmsController::class, 'stats'])->name('sms.stats');
Route::post('/sms/process-scheduled', [App\Http\Controllers\SmsController::class, 'processScheduled'])->name('sms.process-scheduled');

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
    Route::get('/events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show');
    Route::post('/events/{event}/register', [\App\Http\Controllers\EventController::class, 'register'])->name('events.register');
    
    // Ministries - View access for all users
    Route::get('/ministries', [\App\Http\Controllers\MinistryController::class, 'index'])->name('ministries.index');
    Route::get('/ministries/{ministry}', [\App\Http\Controllers\MinistryController::class, 'show'])->name('ministries.show');
    
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
        Route::get('/members/export', [\App\Http\Controllers\MemberController::class, 'export'])->name('members.export');
        Route::get('/members/{member}/id-card', [\App\Http\Controllers\MemberController::class, 'generateIdCard'])->name('members.id-card');
        
        // Families Management (Admin Only)
        Route::resource('families', \App\Http\Controllers\FamilyController::class)->except(['index', 'show']);
        Route::post('families/bulk-action', [\App\Http\Controllers\FamilyController::class, 'bulkAction'])->name('families.bulk-action');
        Route::get('families/export', [\App\Http\Controllers\FamilyController::class, 'export'])->name('families.export');
        
        // Ministries Management (Admin Only)
        Route::resource('ministries', \App\Http\Controllers\MinistryController::class)->except(['index', 'show']);
        
        // Events Management (Admin Only)
        Route::resource('events', \App\Http\Controllers\EventController::class)->except(['index', 'show']);
        Route::post('/events/{event}/check-in/{attendance}', [\App\Http\Controllers\EventController::class, 'checkIn'])->name('events.check-in');
        
        // Donations Management (Admin Only)
        Route::resource('donations', \App\Http\Controllers\DonationController::class);
        Route::post('/donations/{donation}/send-receipt', [\App\Http\Controllers\DonationController::class, 'sendReceipt'])->name('donations.send-receipt');
        
        // Announcements Management (Admin Only)
        Route::resource('announcements', \App\Http\Controllers\AnnouncementController::class)->except(['index', 'show']);

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
        
        // System Settings
        Route::get('/settings/system', [SettingController::class, 'system'])->name('settings.system');
        Route::put('/settings/system', [SettingController::class, 'updateSystem'])->name('settings.system.update');
        
        // Institution Settings
        Route::get('/settings/institution', [SettingController::class, 'institution'])->name('settings.institution');
        Route::put('/settings/institution', [SettingController::class, 'updateInstitution'])->name('settings.institution.update');
    });
});
