<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    // Role constants
    public const ROLE_SUPER_ADMIN = 'super_admin';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_PASTOR = 'pastor';
    public const ROLE_STAFF = 'staff';
    public const ROLE_MEMBER = 'member';
    
    // Permission constants
    public const PERMISSIONS = [
        'users.view' => 'View Users',
        'users.create' => 'Create Users',
        'users.edit' => 'Edit Users',
        'users.delete' => 'Delete Users',
        'settings.view' => 'View Settings',
        'settings.edit' => 'Edit Settings',
        'logs.view' => 'View Activity Logs',
        'members.view' => 'View Members',
        'members.create' => 'Create Members',
        'members.edit' => 'Edit Members',
        'members.delete' => 'Delete Members',
        'documents.view' => 'View Documents',
        'documents.create' => 'Create Documents',
        'documents.edit' => 'Edit Documents',
        'documents.delete' => 'Delete Documents',
        'backup.create' => 'Create Backups',
        'backup.download' => 'Download Backups',
    ];
    
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($user) {
            // Set default permissions based on role
            if (empty($user->permissions)) {
                $user->permissions = self::getDefaultPermissions($user->role);
            }
            
            // Set password changed timestamp
            if ($user->password) {
                $user->password_changed_at = now();
            }
        });
        
        static::updating(function ($user) {
            // Track password changes
            if ($user->isDirty('password')) {
                $user->password_changed_at = now();
                $user->force_password_change = false;
            }
            
            // Log role changes
            if ($user->isDirty('role')) {
                ActivityLog::log([
                    'action' => 'role_changed',
                    'description' => "User role changed from {$user->getOriginal('role')} to {$user->role}",
                    'model_type' => User::class,
                    'model_id' => $user->id,
                    'severity' => 'high',
                    'properties' => [
                        'old_role' => $user->getOriginal('role'),
                        'new_role' => $user->role,
                    ]
                ]);
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
        'is_active',
        'staff_id',
        'first_login',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
            'last_login_at' => 'datetime',
            'locked_until' => 'datetime',
            'force_password_change' => 'boolean',
            'password_changed_at' => 'datetime',
        ];
    }

    /**
     * Get the student associated with the user.
     */
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    /**
     * Get the user's document print records.
     */
    public function documentPrints()
    {
        return $this->hasMany(UserDocumentPrint::class);
    }

    /**
     * Get the total number of documents printed by this user.
     */
    public function getTotalPrintsAttribute()
    {
        return $this->documentPrints()->sum('print_count');
    }

    /**
     * Get the number of unique documents printed by this user.
     */
    public function getUniquePrintedDocumentsAttribute()
    {
        return $this->documentPrints()->count();
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->role === self::ROLE_SUPER_ADMIN) {
            return true; // Super admin has all permissions
        }

        $userPermissions = $this->permissions ?? [];
        return in_array($permission, $userPermissions);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get default permissions for a role
     */
    public static function getDefaultPermissions(string $role): array
    {
        return match ($role) {
            self::ROLE_SUPER_ADMIN => array_keys(self::PERMISSIONS),
            self::ROLE_ADMIN => [
                'users.view', 'users.create', 'users.edit',
                'settings.view', 'settings.edit',
                'logs.view',
                'members.view', 'members.create', 'members.edit',
                'documents.view', 'documents.create', 'documents.edit',
                'backup.create', 'backup.download'
            ],
            self::ROLE_PASTOR => [
                'users.view',
                'settings.view',
                'logs.view',
                'members.view', 'members.create', 'members.edit',
                'documents.view', 'documents.create', 'documents.edit'
            ],
            self::ROLE_STAFF => [
                'members.view', 'members.create', 'members.edit',
                'documents.view', 'documents.create'
            ],
            self::ROLE_MEMBER => [
                'documents.view'
            ],
            default => []
        };
    }

    /**
     * Check if user account is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until && $this->locked_until->isFuture();
    }

    /**
     * Lock user account
     */
    public function lockAccount(int $minutes = 30): void
    {
        $this->update([
            'locked_until' => now()->addMinutes($minutes),
            'failed_login_attempts' => 0
        ]);

        ActivityLog::log([
            'action' => 'account_locked',
            'description' => "User account locked for {$minutes} minutes due to failed login attempts",
            'model_type' => User::class,
            'model_id' => $this->id,
            'severity' => 'high'
        ]);
    }

    /**
     * Unlock user account
     */
    public function unlockAccount(): void
    {
        $this->update([
            'locked_until' => null,
            'failed_login_attempts' => 0
        ]);

        ActivityLog::log([
            'action' => 'account_unlocked',
            'description' => 'User account unlocked',
            'model_type' => User::class,
            'model_id' => $this->id,
            'severity' => 'medium'
        ]);
    }

    /**
     * Increment failed login attempts
     */
    public function incrementFailedLogins(): void
    {
        $this->increment('failed_login_attempts');
        
        if ($this->failed_login_attempts >= 5) {
            $this->lockAccount();
        }
    }

    /**
     * Reset failed login attempts
     */
    public function resetFailedLogins(): void
    {
        $this->update(['failed_login_attempts' => 0]);
    }

    /**
     * Update last login information
     */
    public function updateLastLogin(): void
    {
        $this->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
            'failed_login_attempts' => 0
        ]);

        ActivityLog::log([
            'action' => 'login',
            'description' => 'User logged in successfully',
            'severity' => 'low'
        ]);
    }

    /**
     * Check if password needs to be changed
     */
    public function needsPasswordChange(): bool
    {
        if ($this->force_password_change) {
            return true;
        }

        // Force password change if older than 90 days
        if ($this->password_changed_at && $this->password_changed_at->diffInDays(now()) > 90) {
            return true;
        }

        return false;
    }
}
