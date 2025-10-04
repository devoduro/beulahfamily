<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        if (!auth()->check()) {
            ActivityLog::log([
                'action' => 'unauthorized_access_attempt',
                'description' => "Unauthorized access attempt to {$request->path()}",
                'severity' => 'medium'
            ]);
            
            return redirect()->route('login')->with('error', 'Please log in to access this page.');
        }

        $user = auth()->user();

        // Check if user is active
        if (!$user->is_active) {
            ActivityLog::log([
                'action' => 'inactive_user_access_attempt',
                'description' => "Inactive user attempted to access {$request->path()}",
                'severity' => 'medium'
            ]);
            
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
        }

        // Check if user is locked
        if ($user->isLocked()) {
            ActivityLog::log([
                'action' => 'locked_user_access_attempt',
                'description' => "Locked user attempted to access {$request->path()}",
                'severity' => 'high'
            ]);
            
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is temporarily locked due to security reasons.');
        }

        // Check if password change is required
        if ($user->needsPasswordChange()) {
            return redirect()->route('password.change')->with('warning', 'You must change your password before continuing.');
        }

        // Check permission
        if (!$user->hasPermission($permission)) {
            ActivityLog::log([
                'action' => 'permission_denied',
                'description' => "User attempted to access {$request->path()} without {$permission} permission",
                'severity' => 'medium',
                'properties' => [
                    'required_permission' => $permission,
                    'user_permissions' => $user->permissions ?? [],
                    'requested_path' => $request->path()
                ]
            ]);
            
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
