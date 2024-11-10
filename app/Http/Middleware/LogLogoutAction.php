<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogLogoutAction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Store user id before logout
        $userId = Auth::id();
        $response = $next($request);

        if ($userId) {
            // Log the logout action
            Log::create([
                'action' => 'logout',
                'event_type' => 'user',
                'description' => 'User logged out',
                'user_id' => $userId,
                'ip_address' => $request->ip(),
                'old_data' => json_encode(['user_id' => $userId]),
                'new_data' => null,
            ]);
        }

        return $response;
    }
}
