<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogLoginAction
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
        // Proceed with the login
        $response = $next($request);

        if (Auth::check()) {
            // Log the login action
            Log::create([
                'action' => 'login',
                'event_type' => 'user',
                'description' => 'User logged in',
                'user_id' => Auth::id(),
                'ip_address' => $request->ip(),
                'old_data' => null,
                'new_data' => json_encode(['user_id' => Auth::id()]),
            ]);
        }

        return $response;
    }
}
