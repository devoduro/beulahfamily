<?php

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
    Route::post('password/reset', [\App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');
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
    Route::get('/members/{member}', [\App\Http\Controllers\MemberController::class, 'show'])->name('members.show');
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
    
    

    
    // Admin Only Routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // User Management
        Route::resource('users', UserController::class);
        Route::get('/users/search', [UserController::class, 'search'])->name('users.search');
        Route::get('/users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::put('/users/{user}/update-password', [UserController::class, 'updatePassword'])->name('users.update-password');

        // Church Management - Admin Only Routes
        
        // Members Management (Admin Only)
        Route::post('/members', [\App\Http\Controllers\MemberController::class, 'store'])->name('members.store');
        Route::get('/members/create', [\App\Http\Controllers\MemberController::class, 'create'])->name('members.create');
        Route::get('/members/{member}/edit', [\App\Http\Controllers\MemberController::class, 'edit'])->name('members.edit');
        Route::put('/members/{member}', [\App\Http\Controllers\MemberController::class, 'update'])->name('members.update');
        Route::delete('/members/{member}', [\App\Http\Controllers\MemberController::class, 'destroy'])->name('members.destroy');
        Route::get('/members/export', [\App\Http\Controllers\MemberController::class, 'export'])->name('members.export');
        Route::get('/members/{member}/id-card', [\App\Http\Controllers\MemberController::class, 'generateIdCard'])->name('members.id-card');
        
        // Families Management (Admin Only)
        Route::resource('families', \App\Http\Controllers\FamilyController::class)->except(['index', 'show']);
        
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
