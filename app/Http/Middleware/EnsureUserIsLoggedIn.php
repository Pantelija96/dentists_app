<?php

namespace App\Http\Middleware;

use App\Models\Notification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('showLogin')->with('error', 'Please login first.');
        }

        $user = Auth::user();

        $notifications = [];

        if($user->role == 'admin'){
            $notifications = Notification::where('region_id', $user->region_id)
                ->where('seen', false)
                ->orderBy('created_at', 'desc')
                ->get();
        }
        else{
            $notifications = Notification::where('user_id', $user->id)
                ->where('seen', false)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        session([
            'notifications' => $notifications,
            'notifications_count' => $notifications->count(),
        ]);


        return $next($request);
    }
}
