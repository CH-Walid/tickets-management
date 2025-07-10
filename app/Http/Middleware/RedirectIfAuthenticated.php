<?php

namespace App\Http\Middleware;

use App\Enums\RolesEnum;
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

            // Redirect based on user role or type
            if ($user->role === RolesEnum::ADMIN->value) {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === RolesEnum::USER_SIMPLE->value) {
                return redirect()->route('user.dashboard');
            } elseif ($user->role === RolesEnum::CHEF_TECHNICIEN->value) {
                return redirect()->route('tech.dashboard');
            } elseif ($user->role === RolesEnum::TECHNICIEN->value) {
                return redirect()->route('chef.dashboard');
            } else {
                return redirect(RouteServiceProvider::HOME);
            }
        }

    }

        return $next($request);
    }
}
