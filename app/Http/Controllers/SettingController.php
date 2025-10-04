<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Document;
use App\Models\DocumentCategory;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // System statistics for settings overview
        $systemStats = [
            'total_users' => User::count(),
            'total_documents' => Document::count(),
            'total_categories' => DocumentCategory::count(),
            'total_storage_mb' => round(Document::sum('file_size') / 1024 / 1024, 2),
            'total_prints' => Document::sum('print_count'),
            'active_documents' => Document::where('is_active', true)->count(),
            'active_categories' => DocumentCategory::where('is_active', true)->count(),
        ];

        // Storage usage by category
        $categoryStorage = DocumentCategory::withSum('documents', 'file_size')
            ->having('documents_sum_file_size', '>', 0)
            ->orderByDesc('documents_sum_file_size')
            ->take(10)
            ->get()
            ->map(function ($category) {
                return [
                    'name' => $category->name,
                    'size_mb' => round(($category->documents_sum_file_size ?? 0) / 1024 / 1024, 2),
                    'color' => $category->color,
                    'icon' => $category->icon
                ];
            });

        // Recent system activities for monitoring
        $recentActivities = collect([
            [
                'type' => 'system_info',
                'message' => 'System cache size: ' . $this->getCacheSize(),
                'time' => now()->subMinutes(5)->diffForHumans(),
                'icon' => 'fas fa-server',
                'color' => 'text-blue-600'
            ],
            [
                'type' => 'storage_info',
                'message' => 'Total storage used: ' . $systemStats['total_storage_mb'] . ' MB',
                'time' => now()->subMinutes(10)->diffForHumans(),
                'icon' => 'fas fa-hdd',
                'color' => 'text-orange-600'
            ]
        ]);
        
        return view('settings.index', compact('systemStats', 'categoryStorage', 'recentActivities'));
    }

    /**
     * Get cache size information
     */
    private function getCacheSize()
    {
        try {
            $cacheSize = Cache::get('system_cache_size', '0 MB');
            return $cacheSize;
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }
    
    /**
     * Display academic years settings.
     */
   
    
    /**
     * Delete an academic year.
     */
    public function destroyAcademicYear(string $id)
    {
        $academicYear = AcademicYear::findOrFail($id);
        
        // Check if there are any results associated with this academic year
        if ($academicYear->results()->count() > 0) {
            return redirect()->route('settings.index')
                ->with('error', 'Cannot delete academic year with associated results.');
        }
        
        $academicYear->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Academic year deleted successfully.');
    }
    
    /**
     * Delete a grade scheme.
     */
    public function destroyGradeScheme(string $id)
    {
        $gradeScheme = GradeScheme::findOrFail($id);
        $gradeScheme->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Grade scheme deleted successfully.');
    }
    
    /**
     * Delete a classification.
     */
    public function destroyClassification(string $id)
    {
        $classification = Classification::findOrFail($id);
        $classification->delete();
        
        return redirect()->route('settings.index')
            ->with('success', 'Classification deleted successfully.');
    }
    
    /**
     * Backup the database.
     */
    public function backupDatabase(Request $request)
    {
        \Log::info('Backup request details:', [
            'method' => $request->method(),
            'url' => $request->url(),
            'path' => $request->path(),
            'ajax' => $request->ajax(),
            'headers' => $request->headers->all()
        ]);
        try {
            // Generate a timestamp for the backup file
            $timestamp = now()->format('Y-m-d_H-i-s');
            $filename = "backup_{$timestamp}.sql";
            
            // Get database configuration
            $host = config('database.connections.mysql.host');
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            
            // Create backup command
            $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > storage/app/backups/{$filename}";
            
            // Create backups directory if it doesn't exist
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }
            
            // Execute the command
            exec($command, $output, $returnVar);
            
            if ($returnVar !== 0) {
                throw new \Exception('Database backup failed.');
            }
            
            return redirect()->route('settings.index')
                ->with('success', 'Database backup created successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('settings.index')
                ->with('error', 'Database backup failed: ' . $e->getMessage());
        }
    }
    
    /**
     * List database backups.
     */
    public function listBackups()
    {
        $backups = Storage::files('backups');
        
        $backupFiles = [];
        foreach ($backups as $backup) {
            $backupFiles[] = [
                'name' => basename($backup),
                'size' => Storage::size($backup),
                'created_at' => Storage::lastModified($backup),
            ];
        }
        
        return view('settings.backups.index', compact('backupFiles'));
    }
    
    /**
     * List database backups.
     */
    public function backup()
    {
        $backupPath = storage_path('app/backups');
        $backups = [];
        
        // Initialize debug information with default values
        $debug = [
            'backup_path' => $backupPath,
            'backup_count' => 0,
            'directory_exists' => false,
            'directory_writable' => false,
            'directory_readable' => false,
            'directory_permissions' => 'N/A',
            'found_files' => '',
            'raw_files' => '',
            'php_user' => get_current_user(),
            'storage_path' => storage_path(),
            'public_path' => public_path()
        ];
        
        try {
            // Update directory status
            $debug['directory_exists'] = is_dir($backupPath);
            if ($debug['directory_exists']) {
                $debug['directory_writable'] = is_writable($backupPath);
                $debug['directory_readable'] = is_readable($backupPath);
                $debug['directory_permissions'] = substr(sprintf('%o', fileperms($backupPath)), -4);
            }
            
            // Ensure backup directory exists
            if (!Storage::exists('backups')) {
                Storage::makeDirectory('backups');
            }

            // Get all backup files
            $files = glob($backupPath . '/*.sql') ?: [];
            $debug['raw_files'] = implode(', ', $files);
            
            foreach ($files as $file) {
                if (is_file($file) && is_readable($file)) {
                    $filename = basename($file);
                    $backups[] = [
                        'filename' => $filename,
                        'size' => filesize($file),
                        'created_at' => filemtime($file),
                    ];
                }
            }
            
            // Sort backups by creation date (newest first)
            if (!empty($backups)) {
                usort($backups, function($a, $b) {
                    return $b['created_at'] - $a['created_at'];
                });
            }
            
            // Update backup-related debug info
            $debug['backup_count'] = count($backups);
            $debug['found_files'] = implode(', ', array_column($backups, 'filename'));
            
            return view('settings.backup', compact('backups', 'debug'));
            
        } catch (\Exception $e) {
            $debug['error'] = $e->getMessage();
            $debug['trace'] = $e->getTraceAsString();
            
            return view('settings.backup', [
                'backups' => [],
                'debug' => $debug
            ])->with('error', 'Error listing backups: ' . $e->getMessage());
        }
    }
    
    

    

    
    
    /**
     * Download a database backup.
     */
    public function downloadBackup(string $filename)
    {
        try {
            $backupPath = storage_path('app/backups');
            $filePath = $backupPath . '/' . $filename;
            
            // Security check: ensure the file is within the backups directory
            if (!str_starts_with(realpath($filePath), realpath($backupPath))) {
                return redirect()->route('settings.backup')
                    ->with('error', 'Invalid backup file path.');
            }
            
            // Validate file exists and is readable
            if (!file_exists($filePath) || !is_readable($filePath)) {
                return redirect()->route('settings.backup')
                    ->with('error', 'Backup file not found or not readable.');
            }
            
            // Validate file extension
            if (pathinfo($filename, PATHINFO_EXTENSION) !== 'sql') {
                return redirect()->route('settings.backup')
                    ->with('error', 'Invalid backup file type.');
            }
            
            // Log debug information
            \Log::debug('Downloading backup', [
                'filename' => $filename,
                'path' => $filePath,
                'exists' => file_exists($filePath),
                'readable' => is_readable($filePath),
                'size' => filesize($filePath)
            ]);
            
            return response()->download($filePath, $filename, [
                'Content-Type' => 'application/sql',
                'Content-Disposition' => 'attachment; filename=' . $filename,
                'Cache-Control' => 'no-cache, no-store, must-revalidate',
                'Pragma' => 'no-cache',
                'Expires' => '0'
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Backup download failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('settings.backup')
                ->with('error', 'Failed to download backup: ' . $e->getMessage());
        }
    }

    /**
     * Delete a database backup.
     */
    public function destroyBackup(string $filename)
    {
        try {
            $backupPath = storage_path('app/backups');
            $filePath = $backupPath . '/' . $filename;
            
            // Security check: ensure the file is within the backups directory
            if (!str_starts_with(realpath($filePath), realpath($backupPath))) {
                return redirect()->route('settings.backup')
                    ->with('error', 'Invalid backup file path.');
            }
            
            // Validate file exists
            if (!file_exists($filePath)) {
                return redirect()->route('settings.backup')
                    ->with('error', 'Backup file not found.');
            }
            
            // Attempt to delete the file
            if (!unlink($filePath)) {
                throw new \Exception('Failed to delete file');
            }
            
            return redirect()->route('settings.backup')
                ->with('success', 'Backup deleted successfully.');
                
        } catch (\Exception $e) {
            \Log::error('Backup deletion failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('settings.backup')
                ->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    /**
     * Set an academic year as current.
     */
    public function setCurrentAcademicYear(AcademicYear $academicYear)
    {
        try {
            DB::beginTransaction();
            
            // Unset all current academic years
            DB::statement('UPDATE academic_years SET is_current = 0');
            
            // Set this one as current
            DB::statement('UPDATE academic_years SET is_current = 1 WHERE id = ?', [$academicYear->id]);
            
            DB::commit();
            
            return redirect()->route('settings.academic-years')
                ->with('success', 'Academic year set as current successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->route('settings.academic-years')
                ->with('error', 'Failed to set academic year as current: ' . $e->getMessage());
        }
    }

    /**
     * Display general settings.
     */
    public function general()
    {
        // Get both general and system settings for the merged view using consistent DB query
        $generalSettings = DB::table('settings')->where('category', 'general')->get();
        $systemSettings = DB::table('settings')->where('category', 'system')->get();
        $settings = $generalSettings->merge($systemSettings);
        
        return view('settings.general', compact('settings'));
    }

    /**
     * Update general settings.
     */
    public function updateGeneral(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'organization_name' => 'required|string|max:255',
                'organization_slogan' => 'nullable|string|max:255',
                'organization_description' => 'nullable|string|max:1000',
                'organization_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'primary_color' => 'nullable|string|max:7',
                'secondary_color' => 'nullable|string|max:7',
                'organization_address' => 'nullable|string|max:500',
                'organization_phone' => 'nullable|string|max:20',
                'organization_email' => 'nullable|email|max:255',
                'organization_website' => 'nullable|url|max:255',
                'organization_city' => 'nullable|string|max:100',
                'organization_state' => 'nullable|string|max:100',
                'organization_country' => 'nullable|string|max:100',
                'organization_postal_code' => 'nullable|string|max:20',
                // System settings fields
                'church_code' => 'nullable|string|max:50',
                'bulletin_prefix' => 'nullable|string|max:10',
                'bulletin_footer' => 'nullable|string',
                'pastor_signature' => 'nullable|string|max:100',
                'bulletin_watermark' => 'nullable|string|max:50',
                'email_from_address' => 'nullable|email',
                'email_from_name' => 'nullable|string|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()->route('settings.general')
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update general settings
            $generalSettings = [
                'organization_name',
                'organization_slogan', 
                'organization_description',
                'primary_color',
                'secondary_color',
                'organization_address',
                'organization_phone',
                'organization_email',
                'organization_website',
                'organization_city',
                'organization_state',
                'organization_country',
                'organization_postal_code'
            ];

            // Update system settings (church-focused)
            $systemSettings = [
                'church_code',
                'bulletin_prefix',
                'bulletin_footer',
                'pastor_signature',
                'bulletin_watermark',
                'email_from_address',
                'email_from_name'
            ];

            // Save general settings
            foreach ($generalSettings as $key) {
                if ($request->has($key)) {
                    Setting::setValue($key, $request->input($key), 'general', 'text');
                }
            }

            // Save system settings
            foreach ($systemSettings as $key) {
                if ($request->has($key)) {
                    DB::table('settings')->updateOrInsert(
                        ['key' => $key, 'category' => 'system'],
                        ['value' => $request->input($key), 'updated_at' => now()]
                    );
                }
            }

            // Handle logo upload
            if ($request->hasFile('organization_logo')) {
                $logo = $request->file('organization_logo');
                $path = $logo->store('logos', 'public');
                
                Setting::setValue(
                    'organization_logo', 
                    $path, 
                    'general', 
                    'image',
                    'Organization logo image'
                );
            }

            return redirect()->route('settings.general')
                ->with('success', 'General settings updated successfully.');
                
        } catch (\Exception $e) {
            return redirect()->route('settings.general')
                ->with('error', 'Failed to update general settings: ' . $e->getMessage());
        }
    }
}
