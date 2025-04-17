<?php

namespace App\Http\Middleware; // Must be exactly this

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        return redirect('/'); // Redirect non-admins
    }
}