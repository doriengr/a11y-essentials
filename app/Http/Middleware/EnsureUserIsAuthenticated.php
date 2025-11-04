<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Statamic\Facades\User;

class EnsureUserIsAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (! User::current()) {
            return redirect('/login');
        }

        return $next($request);
    }
}
