<?php

namespace App\Http\Middleware;

use Closure;

class PhoneverifiedMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->user()->verified_at == null)
        {
            return response()->json(['error' => 'unverified phone'] , 403);
        }
        return $next($request);
    }
}
