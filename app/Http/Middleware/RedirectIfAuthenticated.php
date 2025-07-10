<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
            
            
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'user-simple') {
                return redirect()->route('user.dashboard');
            } elseif ($user->role === 'technicien') {
                return redirect()->route('tech.dashboard');
            } elseif ($user->role === 'chef') {
                return redirect()->route('chef.dashboard');
            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }

    }

        return $next($request);
    }
}
