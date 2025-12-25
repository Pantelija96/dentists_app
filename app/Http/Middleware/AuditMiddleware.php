<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        $route = $request->route();

        if ($route) {
            Log::channel('actions')->info('User action', [
                'user_id' => auth()->id(),
                'email'   => auth()->user()?->email ?? 'guest',
                'action'  => $route->getActionName(),
                'time'    => now()->toDateTimeString(),
            ]);
        }

        return $response;
    }
}
